<?php
class woofiltersWpf extends moduleWpf {
	public $mainWCQuery = '';
	public function init() {
		dispatcherWpf::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		add_shortcode(WPF_SHORTCODE, array($this, 'render'));
		add_shortcode(WPF_SHORTCODE_PRODUCTS, array($this, 'renderProductsList'));
		if(is_admin()) {
			add_action('admin_notices', array($this, 'showAdminErrors'));
		}
		frameWpf::_()->addScript('jquery-ui-autocomplete', '', array('jquery'), false, true);

		add_action('woocommerce_product_query', array($this, 'loadProductsFilter'));
		add_filter('woocommerce_product_query_tax_query', array($this, 'customProductQueryTaxQuery'), 10, 2);

		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		add_filter('loop_shop_per_page', array($this, 'newLoopShopPerPage'), 20 );

        class_exists( 'WC_pif' ) && add_filter( 'post_class', array( $this, 'WC_pif_product_has_gallery' ) );
       	add_filter('yith_woocompare_actions_to_check_frontend', array($this, 'addAjaxFilterForYithWoocompare'), 20 );
	}

	public function newLoopShopPerPage($count) {
		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		if(isset($options['count_product_shop']) && isset($options['count_product_shop']['value']) && !empty($options['count_product_shop']['value'])){
			$count  = $options['count_product_shop']['value'];
		}
		return $count ;
	}

	public function addWooOptions($args) {
		if(get_option('woocommerce_hide_out_of_stock_items') == 'yes') {
			$args['meta_query'][] = array(
				array(
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => '!='
				)
			);
		}
		return $args;
	}

	public function loadProductsFilter($q){
		$metaQuery = $this->preparePriceFilter(reqWpf::getVar('min_price'), reqWpf::getVar('max_price'));
		if($metaQuery != false) {
			//$q->set('meta_query', array_merge(WC()->query->get_meta_query(), $metaQuery));
			$q->set('meta_query', array_merge($q->get('meta_query'), $metaQuery));
			remove_filter( 'posts_clauses', array(WC()->query, 'price_filter_post_clauses' ), 10, 2);
		}
		if(reqWpf::getVar('pr_stock')) {
			$slugs = explode('|', reqWpf::getVar('pr_stock'));
			if($slugs){
				$metaQuery = array(
					array(
						'key' => '_stock_status',
						'value' => $slugs,
						'compare' => 'IN'
					)
				);
			}
			/*$metaQuery = array(
				array(
					'key'     => '_stock_status',
					'value'   => reqWpf::getVar('pr_stock'),
					'compare' => '='
				)
			);*/
			//$q->set( 'meta_query', array_merge( WC()->query->get_meta_query(), $metaQuery ) );
			$q->set('meta_query', array_merge($q->get('meta_query'), $metaQuery));
		}
		if(reqWpf::getVar('pr_onsale')) {
			/*$metaQuery = array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			);
			$args['meta_query'][] = $metaQuery;*/
			$q->set('post__in', array_merge(array(0), wc_get_product_ids_on_sale()));
		}

		if(reqWpf::getVar('pr_author')) {
			$author_obj = get_user_by('slug', reqWpf::getVar('pr_author'));
			if(isset($author_obj->ID)){
				$q->set( 'author', $author_obj->ID );
			}
		}
		if(reqWpf::getVar('pr_rating')) {
			$ratingRange = reqWpf::getVar('pr_rating');
			$range = explode('-', $ratingRange);
			if(intval($range[1] ) !== 5){
				$range[1] = $range[1] - 0.001;
			}
			$metaQuery = array(
				array( // Simple products type
					'key' => '_wc_average_rating',
					'value' => array($range[0], $range[1]),
					'type' => 'DECIMAL',
					'compare' => 'BETWEEN'
				)
			);
			//$q->set( 'meta_query', array_merge( WC()->query->get_meta_query(), $metaQuery ) );
			$q->set('meta_query', array_merge($q->get('meta_query'), $metaQuery));
		}
		if(reqWpf::getVar('wpf_count')) {
			$q->set('posts_per_page', reqWpf::getVar('wpf_count'));
		}
		$this->mainWCQuery = $q;
	}
	public function customProductQueryTaxQuery($tax_query) {
		foreach($tax_query as $i => $tax) {
			if(is_array($tax) && isset($tax['field']) && $tax['field'] == 'slug') {
				$name = str_replace('pa_', 'filter_', $tax['taxonomy']);
				$param = reqWpf::getVar($name);
				if(!is_null($param)) {
					$slugs = explode('|', $param);
					if(sizeof($slugs) > 1) {
						$tax_query[$i]['terms'] = $slugs;
						$tax_query[$i]['operator'] = 'IN';
					}
				}
			}
		}
		if(reqWpf::getVar('pr_featured')) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured'
			);
		}
		$getGet = reqWpf::get('get');
		foreach ($getGet as $key => $value) {
		    if (strpos($key, 'filter_cat_list') !== false) {
                $param = reqWpf::getVar($key);
                if(!is_null($param)) {
                    $idsAnd = explode(',', $param);
                    $idsOr = explode('|', $param);
                    $isAnd = sizeof($idsAnd) > sizeof($idsOr);
                    $tax_query[] = array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $isAnd ? $idsAnd : $idsOr,
                        'operator' => $isAnd ? 'AND' : 'IN',
                        'include_children' => false,
                    );
                }
            } elseif (strpos($key, 'filter_cat') !== false) {
                $param = reqWpf::getVar($key);
                if(!is_null($param)) {
                    $idsAnd = explode(',', $param);
                    $idsOr = explode('|', $param);
                    $isAnd = sizeof($idsAnd) > sizeof($idsOr);
                    $tax_query[] = array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $isAnd ? $idsAnd : $idsOr,
                        'operator' => $isAnd ? 'AND' : 'IN',
                        'include_children' => true,
                    );
                }
            } else if(strpos($key, 'product_tag') !== false) {
                $param = reqWpf::getVar($key);
                if(!is_null($param)) {
                    $idsAnd = explode(',', $param);
                    $idsOr = explode('|', $param);
                    $isAnd = sizeof($idsAnd) > sizeof($idsOr);

                    $tax_query[] = array(
                        'taxonomy' => 'product_tag',
                        'field'    => 'slug',
                        'terms'    => $isAnd ? $idsAnd : $idsOr,
                        'operator' => $isAnd ? 'AND' : 'IN',
                        'include_children' => true,
                    );
                }
            } 

        }

		return $tax_query;
	}
	public function addAdminTab($tabs) {
		$tabs[ $this->getCode(). '#wpfadd' ] = array(
			'label' => __('Add New Filter', WPF_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-plus-circle', 'sort_order' => 10, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode(). '_edit' ] = array(
			'label' => __('Edit', WPF_LANG_CODE), 'callback' => array($this, 'getEditTabContent'), 'sort_order' => 20, 'child_of' => $this->getCode(), 'hidden' => 1, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode() ] = array(
			'label' => __('Show All Filters', WPF_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-list', 'sort_order' => 20, //'is_main' => true,
		);
		return $tabs;
	}
	public function getCurrencyPrice($price) {
		return apply_filters('raw_woocommerce_price', $price);
	}
	public function preparePriceFilter($minPrice = null, $maxPrice = null, $rate = null) {
		if(is_null($minPrice) && is_null($maxPrice)) return false;

		if(is_null($rate)) {
			$rate = $this->getCurrentRate();
		}
		$metaQuery = array('key' => '_price', 'price_filter' => true, 'type' => ($rate == 1 ? 'NUMERIC' : 'DECIMAL'));
		if(is_null($minPrice)) {
			$metaQuery['compare'] = '<=';
			$metaQuery['value'] = $minPrice / $rate;
		} elseif(is_null($maxPrice)) {
			$metaQuery['compare'] = '>=';
			$metaQuery['value'] = $maxPrice / $rate;
		} else {
			$metaQuery['compare'] = 'BETWEEN';
			$metaQuery['value'] = array($minPrice / $rate, $maxPrice / $rate);
		}

		return array('price_filter' => $metaQuery);
	}
	public function getCurrentRate() {
		$price = 1000;
		$newPrice = $this->getCurrencyPrice($price);
		return $newPrice / $price;
	}
	public function addHiddenFilterQuery($query) {
		if($hidden_term = get_term_by('name', 'exclude-from-catalog', 'product_visibility')) {
			$query[] = array(
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => array($hidden_term->term_taxonomy_id),
				'operator' => 'NOT IN'
			);
		}
		return $query;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getEditTabContent() {
		$id = reqWpf::getVar('id', 'get');
		return $this->getView()->getEditTabContent( $id );
	}
	public function getEditLink($id, $tableTab = '') {
		$link = frameWpf::_()->getModule('options')->getTabUrl( $this->getCode(). '_edit' );
		$link .= '&id='. $id;
		if(!empty($tableTab)) {
			$link .= '#'. $tableTab;
		}
		return $link;
	}
	public function render($params){
		return $this->getView()->renderHtml($params);
	}
	public function renderProductsList($params){
		return $this->getView()->renderProductsListHtml($params);
	}
	public function showAdminErrors() {
		// check WooCommerce is installed and activated
		if(!$this->isWooCommercePluginActivated()) {
			// WooCommerce install url
			$wooCommerceInstallUrl = add_query_arg(
				array(
					's' => 'WooCommerce',
					'tab' => 'search',
					'type' => 'term',
				),
				admin_url( 'plugin-install.php' )
			);
			$tableView = $this->getView();
			$tableView->assign('errorMsg',
				$this->translate('For work with "')
				. WPF_WP_PLUGIN_NAME
				. $this->translate('" plugin, You need to install and activate <a target="_blank" href="' . $wooCommerceInstallUrl . '">WooCommerce</a> plugin')
			);
			// check current module
			if(reqWpf::getVar('page') == WPF_SHORTCODE) {
				// show message
				echo $tableView->getContent('showAdminNotice');
			}
		}
	}
	public function isWooCommercePluginActivated() {
		return class_exists('WooCommerce');
	}

    public function WC_pif_product_has_gallery( $classes ) {
        global $product;

        $post_type = get_post_type( get_the_ID() );

        if ( wp_doing_ajax() ) {

            if ( $post_type == 'product' ) {

                if ( is_callable( 'WC_Product::get_gallery_image_ids' ) ) {
                    $attachment_ids = $product->get_gallery_image_ids();
                } else {
                    $attachment_ids = $product->get_gallery_attachment_ids();
                }

                if ( $attachment_ids ) {
                    $classes[] = 'pif-has-gallery';
                }
            }
        }

        return $classes;
    }

    public function getFilterTaxonomies($settings, $calcCategories = false) {
    	$taxonomies = array();
    	$forCount = array();
		if($calcCategories) {
			$taxonomies[] = 'product_cat';
		}
		foreach($settings as $filter) {
			$taxonomy = '';
			switch ($filter['id']) {
				case 'wpfCategory':
					$taxonomy = 'product_cat';
					break;
				case 'wpfTags':
					$taxonomy = 'product_tag';
					break;
				case 'wpfAttribute':
					if(!empty($filter['settings']['f_list'])) {
						$taxonomy = wc_attribute_taxonomy_name_by_id((int)$filter['settings']['f_list']);
					}
					break;
				default:
					break;
			}
			if(!empty($taxonomy)) {
				//if(!empty($filter['settings']['f_show_count']) || empty($filter['settings']['f_show_all_categories'])) 
				$taxonomies[] = $taxonomy;
				if(!empty($filter['settings']['f_show_count'])) $forCount[] = $taxonomy;
			}
		}
		return array('names' => array_unique($taxonomies), 'count' => array_unique($forCount));
	}

    public function getFilterExistsTerms($args, $taxonomies, $calcCategory = null) {
    	if(empty($taxonomies['names'])) return false;

    	if(is_null($args)) {
    		if(!empty($this->mainWCQuery)) {
    			$args = $this->mainWCQuery->query_vars;
				//$filterPosts = $this->mainWCQuery->get_posts(array('numberposts' => -1, 'offset' => 1));
			}
    	}
    	if(is_null($args) || empty($args)) return false;

    	$args['nopaging'] = true;
		$args['posts_per_page'] = -1;
		$args['hide_empty'] = 1;
		$args['fields'] = 'ids';

		$filterLoop = new WP_Query($args);
		$existTerms = array();
		$countProducts = array();
		$termsObjs = array();

		$forCount = $taxonomies['count'];
		$isCalcCategory = !is_null($calcCategory);
    	$withCount = !empty($forCount) || $isCalcCategory;
    	$calcCategories = array();
    	$childs = array();
    	$names = array();

		if($filterLoop->have_posts()) {
			$productList = implode(',', $filterLoop->posts);
			$taxonomyList = "'".implode("','", $taxonomies['names'])."'";
			global $wpdb;
			$sql = 'SELECT '.($withCount ? '' : 'DISTINCT ').'tr.term_taxonomy_id, tt.term_id, tt.taxonomy, tt.parent'.($withCount ? ', COUNT(*) as cnt' : ''). 
				" FROM $wpdb->term_relationships tr 
				INNER JOIN $wpdb->term_taxonomy tt ON (tt.term_taxonomy_id=tr.term_taxonomy_id) 
				WHERE tr.object_id in (".$productList.') AND tt.taxonomy IN ('.$taxonomyList.')';
			if($withCount) $sql .= ' GROUP BY tr.term_taxonomy_id';

			$termProducts = $wpdb->get_results($sql);
			foreach($termProducts as $term) {
				$taxonomy = $term->taxonomy;
				$isCat = $taxonomy == 'product_cat';

				$name = urldecode($taxonomy);
				$names[$name] = $taxonomy;

				//if($isCat) $name = 'category';
				//else $name = ($taxonomy == 'product_tag' ? 'tag' : urldecode(str_replace('pa_', '', $taxonomy)));       		
				if(!isset($existTerms[$name])) $existTerms[$name] = array();

				$termId = $term->term_id;
				$cnt = $withCount ? intval($term->cnt) : 0;
				$existTerms[$name][$termId] = $cnt;

				$parent = $term->parent;
				if($isCat && $isCalcCategory && $calcCategory == $parent) $calcCategories[$termId] = $cnt;

				if($parent != 0) {
					$children = array($termId);
					do {
						if(!isset($existTerms[$name][$parent])) $existTerms[$name][$parent] = 0;
						if(isset($childs[$parent])) array_merge($childs[$parent], $children);
						else $childs[$parent] = $children;
						$parentTerm = get_term($parent, $taxonomy);
						$children[] = $parent;
						if($parentTerm && isset($parentTerm->parent)) {
							$parent = $parentTerm->parent;
							if($isCat && $isCalcCategory && $calcCategory == $parent) $calcCategories[$parentTerm->term_id] = 0;
						} else $parent = 0;
					} while($parent != 0);
				}
			}

			if($withCount) {
				foreach($existTerms as $taxonomy => $terms) {
					$allCalc = in_array($taxonomy, $forCount);
					if(!($allCalc || ($isCalcCategory && $taxonomy == 'product_cat'))) continue;
					foreach($terms as $termId => $cnt) {
						if(empty($cnt)) {
							if(isset($childs[$termId]) && ($allCalc || isset($calcCategories[$termId]))) {
								$sql = "SELECT count(DISTINCT tr.object_id)
									FROM $wpdb->term_relationships tr
									INNER JOIN $wpdb->term_taxonomy tt ON (tt.term_taxonomy_id=tr.term_taxonomy_id)
					        		WHERE tr.object_id in (".$productList.") 
					        		AND tt.taxonomy='" . $names[$taxonomy] . "'
									AND tt.term_id in (".$termId.','.implode(',', $childs[$termId]).')';
								$cnt = intval($wpdb->get_var($sql));
								$existTerms[$taxonomy][$termId] = $cnt;
								if(isset($calcCategories[$termId])) $calcCategories[$termId] = $cnt;
							}
						}
					}
				}
			}
		}
		return array('exists' => $existTerms, 'categories' => $calcCategories);
    }
    public function addAjaxFilterForYithWoocompare($actions) {
		return array_merge($actions, array('filtersFrontend'));
	}
}
