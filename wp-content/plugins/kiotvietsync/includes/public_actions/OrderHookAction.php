<?php

/**
 * Created by PhpStorm.
 * User: tuyenvv
 * Date: 3/8/19
 * Time: 4:26 PM
 */

class OrderHookAction
{
    protected $wpdb;
    protected $kiotvietApi;
    protected $orderBranch;
    private $retailer, $KiotvietWcProduct;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->kiotvietApi = new Kiotviet_Sync_Service_Auth();

        $data = kiotviet_sync_get_data('config_branch_order');
        $data = json_decode(html_entity_decode(stripslashes($data)), true);
        if (is_array($data) && array_key_exists('id', $data)) {
            $this->orderBranch = $data['id'];
        }

        $this->retailer = kiotviet_sync_get_data('retailer', "");
        $this->KiotvietWcProduct = new KiotvietWcProduct();
    }

    public function order_processed($orderId, $auto = false)
    {
        $auto_sync_order = kiotviet_sync_get_data('auto_sync_order', "");
        if ($auto_sync_order == "true" || $auto) {
            try {
                $productAddKv = 0;
                $orderObj = wc_get_order($orderId);

                if (!$orderObj) {
                    return [
                        'status' => 'error',
                        'msg' => 'Không tìm thấy đơn hàng trên website',
                    ];
                }

                $orderData = $orderObj->get_data();

                $orderItems = $orderObj->get_items();
                $orderDescription = "Ghi chú của khách hàng:\n" . (!empty($orderData['customer_note']) ? $orderData['customer_note'] : 'Không có') . "\n\n";

                $productItems = [];
                foreach ($orderItems as $orderItem) {
                    if ($orderItem) {
                        $orderItemData = $orderItem->get_data();
                        $productId = $orderItemData['product_id'];
                        $productObj = wc_get_product($productId);
                        // product variant
                        if ($productObj->get_type() == "variable") {
                            $productId = $orderItemData['variation_id'];
                            $productObj = wc_get_product($productId);
                        }

                        if ($productObj) {
                            $product = $productObj->get_data();
                            $kvProductId = $this->get_kv_product_id_from_wc_product_id($productId);
                            if ($kvProductId == -1) {
                                $kvProductId = $this->create_kv_product_from_wc_product($productObj);
                                $productAddKv = $kvProductId;
                                $product = $productObj->get_data();
                                // $orderDescription .= 'Sản phẩm [' . $product['name'] . "] không tồn tại trên kiotviet.\nĐã tạo sản phẩm mới với SKU: " . $product['sku'] . "\n\n";
                            }

                            $productItems[] = [
                                'productId' => $kvProductId,
                                'productCode' => $product['sku'],
                                'quantity' => $orderItemData['quantity'],
                                'price' => $product['price'],
                                'discount' => 0,
                                'discountRatio' => 0,
                            ];
                        } else {
                            return [
                                'status' => 'error',
                                'msg' => 'Sản phẩm trong đơn hàng đã bị xóa trên website.',
                            ];
                        }
                    }
                }

                $customerId = $this->get_customer_id_from_contact_number($orderData['billing']['phone']);
                if ($customerId == -1) {
                    $customerId = $this->create_kv_customer($orderData['billing']);
                }

                $orderDescription = "Đơn hàng từ website, mã đơn hàng " . $orderObj->get_order_number();

                $kvOrderData = [
                    'branchId' => $this->orderBranch,
                    // 'purchaseDate' => date('Y-m-d H:i:s'),
                    'customerId' => $customerId,
                    'totalPayment' => 0,
                    'discount' => 0,
                    'makeInvoice' => false,
                    'description' => $orderDescription,
                    'method' => 'CASH',
                    'status' => 0,
                    'orderDetails' => $productItems,
                    'orderDelivery' => array(
                        'type' => 1,
                        'price' => 0,
                        'receiver' => $orderData['billing']['first_name'] . ' ' . $orderData['billing']['last_name'],
                        'contactNumber' => $orderData['billing']['phone'],
                        'address' => $orderData['billing']['address_1'] . ' ' . $orderData['billing']['city'] . ' ' . $orderData['billing']['country'],
                    ),
                ];

                $response = $this->kiotvietApi->request('POST', 'https://public.kiotapi.com/orders', $kvOrderData, 'json', [
                    "Partner" => "KVSync"
                ]);

                if ($response['status'] != 'error') {
                    $data = [
                        'order_id' => $orderId,
                        'order_kv_id' => $response['data']['id'],
                        'data_raw' => json_encode($response['data']),
                        'created_at' => kiotviet_sync_get_current_time(),

                    ];
                    $format = array('%d', '%d', '%s', '%d');

                    $this->wpdb->insert("{$this->wpdb->prefix}kiotviet_sync_orders", $data, $format);
                    kv_sync_log('Website', 'KiotViet', 'Tạo đơn hàng trên KiotViet thành công, mã đơn hàng: #' . $response['data']['code'], json_encode($response['data']), 2, $orderId);
                } else {
                    // $this->remove_kv_product($productAddKv);
                    return [
                        'status' => 'error',
                        'msg' => $response['error']['responseStatus']['message'],
                    ];
                }
                return $response;
            } catch (Exception $e) {
                var_dump($e);
            }
        }
    }

    public function update_stock_order($orderId)
    {
        try {
            $productParents = [];
            $orderObj = wc_get_order($orderId);
            if (!$orderObj) {
                return [
                    'status' => 'error',
                    'msg' => 'Không tìm thấy đơn hàng trên website',
                ];
            }

            $orderItems = $orderObj->get_items();
            foreach ($orderItems as $orderItem) {
                if ($orderItem) {
                    $orderItemData = $orderItem->get_data();
                    $productId = $orderItemData['product_id'];
                    $productObj = wc_get_product($productId);
                    // product variant
                    if ($productObj->get_type() == "variable") {
                        $productId = $orderItemData['variation_id'];
                        $productObj = wc_get_product($productId);
                        $productParents[] = $productObj->get_parent_id();
                    }
                }
            }

            // update stock product master
            foreach ($productParents as $productParent) {
                $productParentObj = wc_get_product($productParent);
                if ($productParentObj) {
                    $this->KiotvietWcProduct->updateStockProductParent($productParentObj);
                }
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function remove_kv_product($productId)
    {
        $this->kiotvietApi->request('delete', 'https://public.kiotapi.com/products/' . $productId, []);
    }

    public function get_kv_product_id_from_wc_product_id($productId)
    {
        $product = $this->wpdb->get_row("SELECT * FROM `{$this->wpdb->prefix}kiotviet_sync_products` WHERE `product_id` = $productId AND `retailer` = '" . $this->retailer . "'", ARRAY_A);
        if (is_array($product)) {
            return $product['product_kv_id'];
        }
        return -1;
    }

    public function create_kv_product_from_wc_product(&$productObj)
    {

        $product = $productObj->get_data();
        $attrs = [];
        $inventories = [];
        $sku = "";

        if ($productObj->get_type() == 'variation') {
            foreach ($productObj->get_variation_attributes() as $attribute => $slug_value) {
                $term_attribute = str_replace('attribute_', '', $attribute);
                // Get the term object for the attribute value
                $attribute_value_object = get_term_by('slug', $slug_value, $term_attribute);
                // Get the attribute name value (instead of the slug)
                $attrs[] = [
                    'attributeName' => strtoupper(str_replace('pa_', '', $attribute_value_object->taxonomy)),
                    'attributeValue' => $attribute_value_object->name,
                ];
            }
        }

        $inventories[] = [
            'branchId' => $this->orderBranch,
            'onHand' => $productObj->get_stock_quantity(),
            'minQuantity' => $productObj->get_low_stock_amount(),
            'cost' => (float) $product['price'],
            'reserved' => 0,
        ];

        if (empty($product['sku'])) {
            $sku = 'Website' . strtoupper(time());
            $productObj->set_sku($sku);
            $productObj->save();
        } else {
            $sku = 'Website' . $product['sku'];
        }

        if ($productObj->get_type() == 'variation') {
            $productParent = wc_get_product($productObj->get_parent_id());
            $productName = $productParent->get_name();
        } else {
            $productName = $product['name'];
        }

        // images
        $images = [];
        $imageProducts = $productObj->get_gallery_image_ids();
        if ($productObj->get_image_id()) {
            array_unshift($imageProducts, $productObj->get_image_id());
        }

        foreach ($imageProducts as $attachment_id) {
            // Display the image URL
            $images[] = wp_get_attachment_url($attachment_id);
        }

        $data = [
            'code' => $sku,
            'name' => $productName . " - " . "Website",
            'categoryId' => $this->get_website_category_on_kv($productObj), //?? Don't know how i set the fucking category
            'allowsSale' => true,
            'description' => strip_tags($product['description']),
            'hasVariants' => count($attrs) ? true : false,
            'attributes' => $attrs,
            'inventories' => $inventories,
            'images' => $images,
            'basePrice' => (float) $product['price'],
            'weight' => 5,
        ];

        $response = $this->kiotvietApi->request('POST', 'https://public.kiotapi.com/products', $data, 'json');
        $data = [
            'product_id' => $product['id'],
            'product_kv_id' => $response['data']['id'],
            'data_raw' => json_encode($response['data']),
            'retailer' => $this->retailer,
            'created_at' => kiotviet_sync_get_current_time(),
        ];
        $format = array('%d', '%d', '%s', '%s', '%d');

        $this->wpdb->insert("{$this->wpdb->prefix}kiotviet_sync_products", $data, $format);

        if ($response['status'] != 'error') {
            return $response['data']['id'];
        }
        return -1;
    }

    public function get_customer_id_from_contact_number($contactNumber)
    {

        $response = $this->kiotvietApi->request('GET', 'https://public.kiotapi.com/customers', [
            'contactNumber' => $contactNumber,
        ]);

        foreach ($response['data']['data'] as $item) {
            if ($item['contactNumber'] == $contactNumber && $item['branchId'] == $this->orderBranch) {
                return $item['id'];
            }
        }

        return -1;
    }

    public function get_website_category_on_kv($productObj)
    {
        $categoryIds = $productObj->get_category_ids();
        if ($categoryIds) {
            $wcCategoryAsync = $this->wpdb->get_row("SELECT `category_kv_id` FROM {$this->wpdb->prefix}kiotviet_sync_categories WHERE `retailer` = '" . $this->retailer . "' AND `category_id` = " . $categoryIds[count($categoryIds) - 1] . "", ARRAY_A);
            if ($wcCategoryAsync) {
                return $wcCategoryAsync['category_kv_id'];
            } else {
                return $this->getCategoryOther();
            }
        } else {
            return $this->getCategoryOther();
        }

        return -1;
    }

    public function getCategoryOther()
    {
        $response = $this->kiotvietApi->request('GET', 'https://public.kiotapi.com/categories', []);
        if (!empty($response['data']['data'])) {
            foreach ($response['data']['data'] as $item) {
                if ($item['categoryName'] == "Khác") {
                    return $item['categoryId'];
                }
            }
        }

        $response = $this->kiotvietApi->request('POST', 'https://public.kiotapi.com/categories', [
            'categoryName' => 'Khác',
        ]);

        if ($response['data']['data']) {
            return $response['data']['data']['categoryId'];
        }
    }

    public function create_kv_customer($billing)
    {

        $data = [
            'code' => 'KH' . strtoupper(substr(md5(time()), rand(0, strlen(md5(time())) - 5), 9)),
            'name' => $billing['first_name'] . ' ' . $billing['last_name'],
            'gender' => true,
            'branchId' => $this->orderBranch,
            'contactNumber' => $billing['phone'],
            'address' => $billing['address_1'],
            'comments' => 'Khách hàng tạo từ website, email: ' . $billing['email'],
        ];

        $response = $this->kiotvietApi->request('POST', 'https://public.kiotapi.com/customers', $data);

        if ($response['status'] != 'error') {
            return $response['data']['data']['id'];
        }
        return -1;
    }
}
