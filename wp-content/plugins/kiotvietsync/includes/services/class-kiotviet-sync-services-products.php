<?php
require_once plugin_dir_path(dirname(__FILE__)) . '.././vendor/autoload.php';

use Kiotviet\Kiotviet\HttpClient;

class Kiotviet_Sync_Service_Product
{
    private $KiotvietWcProduct, $wpdb, $retailer, $response;
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->response = [];
        $this->retailer = kiotviet_sync_get_data('retailer', "");
        $this->KiotvietWcProduct = new KiotvietWcProduct();
        $this->HttpClient = new HttpClient();
    }

    public function getProductMap()
    {
        $product_id = kiotviet_sync_get_request('product_id', []);
        $product = [];
        if ($product_id) {
            $product = $this->wpdb->get_results("SELECT * FROM `{$this->wpdb->prefix}kiotviet_sync_products` WHERE `product_kv_id` IN (" . implode(",", $product_id) . ") AND `status` = 1 AND `retailer` = '" . $this->retailer . "'", ARRAY_A);
        }
        wp_send_json($this->HttpClient->responseSuccess($product));
    }

    public function productMap($products)
    {
        $productId = [];
        $productMap = [];
        foreach ($products as $product) {
            $productId[] = $product['kv_id'];
        }

        if ($productId) {
            $productSync = $this->wpdb->get_results("SELECT * FROM {$this->wpdb->prefix}kiotviet_sync_products WHERE `product_kv_id` IN (" . implode(",", $productId) . ") AND `retailer` = '" . $this->retailer . "'");
            foreach ($productSync as $product) {
                $productMap[$product->product_kv_id] = $product;
            }
        }

        return $productMap;
    }

    public function getCategoryIdMap($products)
    {
        $categoryId = [];
        $categoryMap = [];
        foreach ($products as $product) {
            $categoryId[] = $product['category_kv'];
        }

        if ($categoryId) {
            $categorySync = $this->wpdb->get_results("SELECT * FROM {$this->wpdb->prefix}kiotviet_sync_categories WHERE `category_kv_id` IN (" . implode(",", $categoryId) . ") AND `retailer` = '" . $this->retailer . "'");
            foreach ($categorySync as $category) {
                $categoryMap[$category->category_kv_id] = $category->category_id;
            }
        }

        return $categoryMap;
    }

    private function handleResponse($result)
    {
        if($result){
            if (is_wp_error($result)) {
                $this->response['error'][] = $result;
            } else {
                $this->response['data'][] = $result;
            }
        }
    }

    public function add()
    {
        $products = kiotviet_sync_decode_json(kiotviet_sync_get_request('data', []));
        $categorySync = $this->getCategoryIdMap($products);
        foreach ($products as $product) {
            $result = [];
            $product['category_ids'] = array(!empty($categorySync[$product['category_kv']]) ? $categorySync[$product['category_kv']] : []);
            if ($product['type'] == "simple") {
                $result = $this->KiotvietWcProduct->productSimple($product);
            } else if ($product['type'] == "variable") {
                $result = $this->KiotvietWcProduct->productVariable($product);
            } else if ($product['type'] == "variation") {
                $result = $this->KiotvietWcProduct->productVariation($product);
            }

            $this->handleResponse($result);
        }

        wp_send_json($this->HttpClient->responseSuccess($this->response));
    }

    public function update()
    {
        $products = kiotviet_sync_decode_json(kiotviet_sync_get_request('data', []));
        $productSync = $this->productMap($products);
        $categorySync = $this->getCategoryIdMap($products);
        foreach ($products as $product) {
            $result = [];
            $updateProduct = !empty($productSync[$product['kv_id']]) && $productSync[$product['kv_id']]->status == 1;
            $addProductVariant = $product['type'] == "variation" && empty($productSync[$product['kv_id']]);
            if ($updateProduct || $addProductVariant || $product['type'] == 'variable') {
                $product['category_ids'] = array(!empty($categorySync[$product['category_kv']]) ? $categorySync[$product['category_kv']] : []);
                if ($product['type'] == "simple") {
                    $result = $this->KiotvietWcProduct->productSimple($product);
                } else if ($product['type'] == "variable") {
                    if (!empty($productSync[$product['kv_id']])) {
                        $result = $this->KiotvietWcProduct->productVariable($product);
                    }
                } else if ($product['type'] == "variation") {
                    $result = $this->KiotvietWcProduct->productVariation($product);
                }
            }
            $this->handleResponse($result);
        }

        wp_send_json($this->HttpClient->responseSuccess($this->response));
    }

    public function delete()
    {
        $wcProductSync = $this->wpdb->get_results("SELECT product_id FROM {$this->wpdb->prefix}kiotviet_sync_products");
        $productSyncIds = [];
        foreach ($wcProductSync as $item) {
            $productSyncIds[] = $item->product_id;
        }

        $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}term_relationships WHERE `object_id` IN (SELECT ID FROM {$this->wpdb->prefix}posts WHERE ID IN (" . implode(",", $productSyncIds) . ") AND post_type IN ('product','product_variation'))");
        $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}postmeta WHERE post_id IN (SELECT ID FROM {$this->wpdb->prefix}posts WHERE ID IN (" . implode(",", $productSyncIds) . ") AND post_type IN ('product','product_variation'))");
        $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}posts WHERE ID IN (" . implode(",", $productSyncIds) . ") AND post_type IN ('product','product_variation')");
        $this->wpdb->query("DELETE FROM {$this->wpdb->prefix}kiotviet_sync_products");
        wp_send_json($this->HttpClient->responseSuccess(true));
    }

    public function updateStatus()
    {
        $productId = kiotviet_sync_get_request('product_id', 0);
        $productKvId = kiotviet_sync_get_request('product_kv_id', 0);
        $status = kiotviet_sync_get_request('status', 0);
        if ($productId) {
            $this->updateStatusById($productId, $status);
        }

        if ($productKvId) {
            $this->updateStatusByKvId($productKvId, $status);
        }

        wp_send_json($this->HttpClient->responseSuccess($status));
    }

    public function updateStatusByKvId($productKvId, $status)
    {
        $productSync = $this->wpdb->get_results("SELECT * FROM {$this->wpdb->prefix}kiotviet_sync_products WHERE `product_kv_id` IN (" . implode(",", $productKvId) . ") AND `retailer` = '" . $this->retailer . "'");
        foreach ($productSync as $product) {
            $update = [
                "status" => $status,
            ];

            $this->wpdb->update($this->wpdb->prefix . "kiotviet_sync_products", $update, array("id" => $product->id));
            $productObj = wc_get_product($product->product_id);
            if ($productObj->get_type() == 'variation') {
                $parentId = $productObj->get_parent_id();
                // update parent product
                $this->wpdb->update($this->wpdb->prefix . "kiotviet_sync_products", $update, array("product_id" => $parentId));
                $productParent = wc_get_product($parentId);
                if ($productParent) {
                    $productChildId = $productParent->get_children();
                    if ($productChildId) {
                        // update child product
                        $query = "UPDATE {$this->wpdb->prefix}kiotviet_sync_products SET `status` = " . $status . " WHERE `product_id` IN (" . implode(",", $productChildId) . ")";
                        $this->wpdb->query($query);
                    }
                }
            }
        }
    }

    public function updateStatusById($productId, $status)
    {
        $productSync = $this->wpdb->get_row("SELECT * FROM {$this->wpdb->prefix}kiotviet_sync_products WHERE `product_id` = " . $productId . " AND `retailer` = '" . $this->retailer . "'", ARRAY_A);
        if ($productSync) {
            $update = [
                "status" => $status,
            ];

            $this->wpdb->update($this->wpdb->prefix . "kiotviet_sync_products", $update, array("id" => $productSync['id']));

            // update product child variant
            if ($productSync['product_kv_id'] == 0) {
                $productParent = wc_get_product($productSync['product_id']);
                if ($productParent) {
                    $productChildId = $productParent->get_children();
                    if ($productChildId) {
                        $query = "UPDATE {$this->wpdb->prefix}kiotviet_sync_products SET `status` = " . $status . " WHERE `product_id` IN (" . implode(",", $productChildId) . ")";
                        $this->wpdb->query($query);
                    }
                }
            }
        }
    }

    public function updatePrice()
    {
        $data = kiotviet_sync_get_request('data', []);
        $result = [];
        $productMapIds = [];
        foreach ($data as $item) {
            $productIDs[] = $item['productKvId'];
        }

        $wcProductSync = $this->wpdb->get_results("SELECT * FROM {$this->wpdb->prefix}kiotviet_sync_products WHERE `product_kv_id` IN (" . implode(",", $productIDs) . ") AND `status` = 1 AND  `retailer` = '" . $this->retailer . "'");
        foreach ($wcProductSync as $item) {
            $productMapIds[$item->product_kv_id] = $item->product_id;
        }

        foreach ($data as $item) {
            $productId = !empty($productMapIds[$item['productKvId']]) ? $productMapIds[$item['productKvId']] : 0;
            if ($productId) {
                $product = wc_get_product($productId);
                if ($product) {
                    $product->set_regular_price($item['regularPrice']);
                    $product->set_sale_price($item['salePrice']);
                    $product->save();
                    $result[] = $product;
                }
            }
        }
        wp_send_json($this->HttpClient->responseSuccess($result));
    }

    public function updateStock()
    {
        $data = kiotviet_sync_get_request('data', []);
        $result = [];
        $productMapIds = [];
        foreach ($data as $item) {
            $productIDs[] = $item['productKvId'];
        }

        $wcProductSync = $this->wpdb->get_results("SELECT * FROM {$this->wpdb->prefix}kiotviet_sync_products WHERE `product_kv_id` IN (" . implode(",", $productIDs) . ") AND `status` = 1 AND`retailer` = '" . $this->retailer . "'");
        foreach ($wcProductSync as $item) {
            $productMapIds[$item->product_kv_id] = $item->product_id;
        }

        foreach ($data as $item) {
            $productId = !empty($productMapIds[$item['productKvId']]) ? $productMapIds[$item['productKvId']] : 0;
            if ($productId) {
                $product = wc_get_product($productId);
                if ($product) {
                    $product->set_stock_quantity($item['stock']);
                    $product->save();
                    $result[] = $product;

                    // update stock parent
                    if ($product->get_type() == 'variation') {
                        $parentId = $product->get_parent_id();
                        $wcParentProduct = wc_get_product($parentId);
                        if ($wcParentProduct) {
                            $this->KiotvietWcProduct->updateStockProductParent($wcParentProduct);
                        }
                    }
                }
            }
        }

        wp_send_json($this->HttpClient->responseSuccess($result));
    }

    public function getProductParent($productKvId)
    {
        $productSync = $this->wpdb->get_row("SELECT * FROM {$this->wpdb->prefix}kiotviet_sync_products WHERE `parent` = " . $productKvId . " AND `status` = 1 AND `retailer` = '" . $this->retailer . "'", ARRAY_A);
        $parentId = 0;
        if ($productSync) {
            $parentId = $productSync['product_id'];
        }

        return $parentId;
    }
}
