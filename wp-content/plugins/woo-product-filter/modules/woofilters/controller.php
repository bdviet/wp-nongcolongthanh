<?php
class woofiltersControllerWpf extends controllerWpf {

	protected $_code = 'woofilters';

	protected function _prepareTextLikeSearch($val) {
		$query = '(title LIKE "%'. $val. '%"';
		if(is_numeric($val)) {
			$query .= ' OR id LIKE "%'. (int) $val. '%"';
		}
		$query .= ')';
		return $query;
	}
	public function _prepareListForTbl($data){
		foreach($data as $key => $row){
			$id = $row['id'];
			$shortcode = "[".WPF_SHORTCODE." id=".$id."]";
			$showPrewiewButton = "<button data-id='".$id."' data-shortcode='".$shortcode."' class='button button-primary button-prewiew' style='margin-top: 1px;'>".__('Prewiew', WPF_LANG_CODE)."</button>";
            $titleUrl = "<a href=".$this->getModule()->getEditLink( $id ).">".$row['title']." <i class='fa fa-fw fa-pencil'></i></a> <a data-filter-id='".$id."' class='wpfDuplicateFilter' href='' title='".__('Duplicate filter', WPF_LANG_CODE)."'><i class='fa fa-fw fa-clone'></i></a>";

            $data[$key]['shortcode'] = $shortcode;
			$data[$key]['rewiew'] = $showPrewiewButton;
			$data[$key]['title'] = $titleUrl;
		}
		return $data;
	}

	public function drawFilterAjax(){
        $res = new responseWpf();
        $data = reqWpf::get('post');
        if (isset($data) && $data) {
			/*$html = '';

			$isPro = frameWpf::_()->isPro();

			$styles[] = 'css/frontend.woofilters.css';
			$styles[] = 'css/frontend.multiselect.css';
			$styles[] = 'css/loaders.css';
			$styles[] = 'css/jquery.slider.min.css';
			$styles[] = 'css/move.sidebar.css';

			$scripts[] = 'js/frontend.woofilters.js';
			$scripts[] = 'js/frontend.multiselect.js';
			$scripts[] = 'js/jquery_slider/jshashtable-2.1_src.js';
			$scripts[] = 'js/jquery_slider/jquery.numberformatter-1.2.3.js';
			$scripts[] = 'js/jquery_slider/tmpl.js';
			$scripts[] = 'js/jquery_slider/jquery.dependClass-0.1.js';
			$scripts[] = 'js/jquery_slider/draggable-0.1.js';
			$scripts[] = 'js/jquery_slider/jquery.slider.js';
			$scripts[] = 'js/jquery_slider/tmpl.js';

			foreach ($styles as $style) {
				$html .= "<link rel='stylesheet' href='". frameWpf::_()->getModule('woofilters')->getModPath(). $style. "' type='text/css' media='all' />";
			}
			foreach ($scripts as $script) {
				$html .= "<script type='text/javascript' src='". frameWpf::_()->getModule('woofilters')->getModPath(). $script. "'></script>";
			}

			if ($isPro) {
					$stylesPro[] = 'css/frontend.woofilters.pro.css';
					$stylesPro[] = 'css/jquery-ui-autocomplete.css';
					$stylesPro[] = 'css/common.woofilters.pro.css';

					$stylesPro[] = 'css/ion.rangeSlider.css';
					$scriptsPro[] = 'js/frontend.woofilters.pro.js';
					$scriptsPro[] = 'js/ion.rangeSlider.min.js';

					foreach ($stylesPro as $style) {
						$html .= "<link rel='stylesheet' href='". frameWpf::_()->getModule('woofilterpro')->getModPath(). $style. "' type='text/css' media='all' />";
					}
					foreach ($scriptsPro as $script) {
						$html .= "<script type='text/javascript' src='". frameWpf::_()->getModule('woofilterpro')->getModPath(). $script. "'></script>";
					}
			}
			$html .= frameWpf::_()->getModule('woofilters')->render($data);*/
			$isPro = frameWpf::_()->isPro();

			$html = frameWpf::_()->getModule('woofilters')->render($data);
			$html .= '<script type="text/javascript">window.wpfFrontendPage.init();'.($isPro ? 'window.wpfFrontendPage.eventsFrontendPro();' : '').'</script>';
            $res->setHtml($html);
        } else {
			$res->pushError($this->getModule('woofilters')->getErrors());
			//$res->pushError(__('Empty or invalid data procided', WCU_LANG_CODE));
		}

        $res->ajaxExec();
    }

	public function save(){
		$res = new responseWpf();
		if(($id = $this->getModel('woofilters')->save(reqWpf::get('post'))) != false) {
			$res->addMessage(__('Done', WPF_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel('woofilters')->getErrors());
		return $res->ajaxExec();
	}

	public function deleteByID(){
		$res = new responseWpf();

		if($this->getModel('woofilters')->delete(reqWpf::get('post')) != false){
			$res->addMessage(__('Done', WPF_LANG_CODE));
		}else{
			$res->pushError ($this->getModel('woofilters')->getErrors());
		}
		return $res->ajaxExec();
	}

	public function createTable(){
		$res = new responseWpf();
		if(($id = $this->getModel('woofilters')->save(reqWpf::get('post'))) != false) {
			$res->addMessage(__('Done', WPF_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel('woofilters')->getErrors());
		return $res->ajaxExec();
	}

	public function filtersFrontend(){
		$res = new responseWpf();
		$params = reqWpf::get('post');
		$filterSettings = utilsWpf::jsonDecode(stripslashes($params['settings']));
		$settings = utilsWpf::jsonDecode(stripslashes($params['options']));
        $generalSettings = utilsWpf::jsonDecode(stripslashes($params['general']));
		$queryvars = utilsWpf::jsonDecode(stripslashes($params['queryvars']));
		$filterQueryVars = utilsWpf::jsonDecode(stripslashes($params['queryvars']));
		$curUrl = $params['currenturl'];
        $queryvars['posts_per_page'] = isset($filterSettings['count_product_shop']) && !empty($filterSettings['count_product_shop']) ? $filterSettings['count_product_shop'] : $queryvars['posts_per_page'];
		$args = $this->createArgsForFilteringBySettings($settings, $queryvars, $filterSettings, $generalSettings);
		//$paged = empty($params['runbyload']) || empty($queryvars['paged']) ? 1 : $queryvars['paged'];
		$paged = empty($queryvars['paged']) ? 1 : $queryvars['paged'];
		if(empty($params['runbyload']) && empty($queryvars['pagination'])) $paged = 1;

		$args['paged'] = $paged;
		class_exists('WC_pif') && add_filter('post_class', array($this->getModule(), 'WC_pif_product_has_gallery'));
		
		$categoryHtml = '';
		$productsHtml = '';

		//Prepare params for WooCommerce Shop and Category template variants.
		$shopPageId = wc_get_page_id('shop');
		$currentPageId = isset($queryvars['page_id']) ? $queryvars['page_id'] : 0;
		$categoryPageId = isset($queryvars['product_category_id']) ? $queryvars['product_category_id'] : 0;
		
		$calcParentCategory = null;
		$showProducts = true;
		if($shopPageId == $currentPageId) {
			$pageDisplay = get_option('woocommerce_shop_page_display', '');
			if($pageDisplay == 'subcategories' || $pageDisplay == 'both') {
				$calcParentCategory = 0;
				if($pageDisplay == 'subcategories') $showProducts = false;
			}
		} else if($categoryPageId) {
			$archiveDisplay = get_option('woocommerce_category_archive_display', '');
			$productTag = isset($productTag) ? $productTag : false;
			
			$termProductCategory = get_term_by('id', $categoryPageId, 'product_cat');
			
			if ($termProductCategory && ($archiveDisplay == 'subcategories' || $archiveDisplay == 'both')) {
				$calcParentCategory = $termProductCategory->term_id;
				if($archiveDisplay == 'subcategories') $showProducts = false;
			}
		}
		$recount = isset($filterSettings['filter_recount']) && $filterSettings['filter_recount'];

		$module = $this->getModule();
		$taxonomies = $module->getFilterTaxonomies($generalSettings, !is_null($calcParentCategory));
		if(!$recount) $taxonomies['count'] = array();
		$terms = $module->getFilterExistsTerms($args, $taxonomies, $calcParentCategory);

		$categoryIn = isset($terms['categories']) ? $terms['categories'] : array();
		if(count($categoryIn) > 0) {
			ob_start();
			foreach ( $categoryIn as $id => $cnt) {
				$category = get_term($id, 'product_cat');
				$category->count = $cnt;
				wc_get_template('content-product_cat.php', array('category' => $category));
			}
			$categoryHtml .= ob_get_clean();
		}

		$loopFoundPost = 0;
		if($showProducts || empty($categoryHtml)) {
			//get products
			$loop = new WP_Query($args);
			$loopFoundPost = $loop->found_posts;
			if ($loop->have_posts()) {
				ob_start();
				while ($loop->have_posts()) : $loop->the_post();
					wc_get_template_part('content', 'product');
				endwhile;
				$productsHtml = ob_get_clean();
			} else {
				$productsHtml = $filterSettings['text_no_products'];
			}
			if($terms !== false) {
				if($recount) {
					$productsHtml .= '<script type="text/javascript">wpfChangeFiltersCount('.json_encode($terms['exists']).');</script>';
        		}
        		$productsHtml .= '<script type="text/javascript">wpfShowHideFiltersAtts('.json_encode($terms['exists']).');</script>';
        	}
		}		

		ob_start();
		wc_get_template( 'loop/loop-start.php' );
		$loopStart = ob_get_clean();

		//get result count
		ob_start();
		$args = array(
			'total'    => $loopFoundPost,
			'per_page' => $queryvars['posts_per_page'],
			'current'  => 1,//$queryvars['paged'],
		);
		wc_get_template( 'loop/result-count.php', $args );
		$resultCountHtml = ob_get_clean();

		//get pagination
		ob_start();
		$base    =  $queryvars['base'];

		//get query params
		$curUrl = explode( '?', $curUrl );
		$curUrl = isset($curUrl[1]) ? $curUrl[1] : '';

		// $getArray = array();
		// parse_str($curUrl, $getArray);
		// $getArray['product-page'] = '%#%';
		// $curUrl = http_build_query($getArray);
		// $curUrl = urldecode($curUrl);

		//add quary params to base url
		$fullBaseUrl =  $base . '?' . $curUrl;

		$format  = '';
		$total = ceil($loopFoundPost / $queryvars['posts_per_page']);

		//after filtering we always start from 1 page
		$args = array(
			'base'         => $fullBaseUrl,
			'format'       => $format,
			'add_args'     => false,
			'current'      => $paged,//1,//$queryvars['paged'],
			'total'        => $total,
			'prev_text'    => '&larr;',
			'next_text'    => '&rarr;',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3,
		);
		wc_get_template( 'loop/pagination.php', $args );
		$paginationHtml = ob_get_clean();
		wp_reset_postdata();
		$paginationLeer = '';
		if(empty($paginationHtml)) {
			ob_start();
			$args['current'] = 1;
			$args['total'] = 2;
			wc_get_template( 'loop/pagination.php', $args);
			$paginationLeer = ob_get_clean();
		}
		
		$res->addData('categoryHtml', $categoryHtml);
		$res->addData('productHtml', $productsHtml);
		$res->addData('paginationHtml', $paginationHtml);
		$res->addData('resultCountHtml', $resultCountHtml);
		$res->addData('loopStartHtml', $loopStart);
		$res->addData('paginationLeerHtml', $paginationLeer);

		return $res->ajaxExec();
	}

	public function order_by_popularity_post_clauses_clone( $args ) {
		global $wpdb;
		$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
		return $args;
	}

	public function getTaxonomyTerms(){
		$res = new responseWpf();
		$attrId = reqWpf::getVar('attr_id');

		$data = array();
		if(!is_null($attrId)) {

			$attrName = wc_attribute_taxonomy_name_by_id((int)$attrId);
			$args = array(
				'hide_empty' => false,
			);
			$terms = get_terms( $attrName, $args);
			foreach($terms as $term ){
				if(!empty($term->term_id)){
					$data[] = array('id' => $term->term_id, 'name' => $term->name);
				}
			}
		}
		$res->addData('terms', $data);
		return $res->ajaxExec();
	}

	public function createArgsForFilteringBySettings($settings, $queryvars, $filterSettings = array(), $generalSettings = array()){
		$queryvars['product_tag'] = isset($queryvars['product_tag']) ? $queryvars['product_tag'] : false;
        $queryvars['product_brand'] = isset($queryvars['product_brand']) ? $queryvars['product_brand'] : false;
        $asDefaultCats = array();
        $settingIds = array_column($settings, 'id');
        $settingCats = array_keys($settingIds, 'wpfCategory');
        if (!count($settingCats)) {
            foreach ($generalSettings as $generalSingle) {
                if ($generalSingle['id'] == 'wpfCategory' && $generalSingle['settings']['f_filtered_by_selected'] && !empty($generalSingle['settings']['f_mlist[]'])) {
                    $asDefaultCats = array_merge($asDefaultCats, explode(',', $generalSingle['settings']['f_mlist[]']));
                    break;
                }
            }
        }
		$args = array(
			'post_status' => 'publish',
			'post_type' => 'product',
			'paged' => 1,
			'posts_per_page' => $queryvars['posts_per_page'],
			'ignore_sticky_posts' => true,
			'tax_query' => array()
		);
		$args['tax_query'] = $this->getModule()->addHiddenFilterQuery($args['tax_query']);
		if( ( isset($queryvars['product_category_id']) || $asDefaultCats ) && !$queryvars['product_tag'] && !$queryvars['product_brand'] ){
			$args["tax_query"][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => isset($queryvars['product_category_id']) ? $queryvars['product_category_id'] : $asDefaultCats,
				'include_children' => true
			);
		} elseif ($queryvars['product_tag']) {
			$args["tax_query"][] = array(
				'taxonomy' => 'product_tag',
				'field'    => 'id',
				'terms'    => $queryvars['product_tag'],
				'include_children' => true
			);
		} elseif ($queryvars['product_brand']) {
            $args["tax_query"][] = array(
                'taxonomy' => 'product_brand',
                'field'    => 'id',
                'terms'    => $queryvars['product_brand'],
                'include_children' => true
            );
        }
		$temp = array();
		foreach ($settings as $setting){
			if(!empty($setting['settings'])) {
				switch ($setting['id']){
					case 'wpfPrice':
						$priceStr = $setting['settings'][0];
						$priceVal = explode(',', $priceStr);
						if($priceVal[0] !== false && $priceVal[1]){
							$temp['wpfPrice']['min_price'] = $priceVal[0];
							$temp['wpfPrice']['max_price'] = $priceVal[1];
						}
						break;
					case 'wpfPriceRange':
						$priceStr = $setting['settings'][0];
						$priceVal = explode(',', $priceStr);
						if($priceVal[0] !== false && $priceVal[1]){
							$temp['wpfPrice']['min_price'] = $priceVal[0];
							$temp['wpfPrice']['max_price'] = $priceVal[1];
						}
						break;
					case 'wpfSortBy':
						switch ( $setting['settings'] ) {
							case 'title':
								$args['orderby'] = 'title';
								$args['order'] = 'ASC';
								break;
							case 'rand':
								$args['orderby'] = 'rand';
								break;
							case 'date':
								$args['orderby'] = 'date ID';
								$args['order'] = 'DESC';
								break;
							case 'price':
								$args['meta_key'] = '_price';
								$args['orderby'] = 'meta_value_num';
								$args['order'] = 'ASC';
								break;
							case 'price-desc':
								$args['meta_key'] = '_price';
								$args['orderby'] = 'meta_value_num';
								$args['order'] = 'DESC';
								break;
							case 'popularity':
								$args['orderby'] = 'meta_value_num';
								$args['order'] = 'DESC';
								$args['meta_key'] = 'total_sales';
								break;
							case 'rating':
								$args['meta_key'] = '_wc_average_rating'; // @codingStandardsIgnoreLine
								$args['orderby']  = array(
									'meta_value_num' => 'DESC',
									'ID'             => 'ASC',
								);
								break;
						}
						break;
					case 'wpfCategory':
						$categoryIds = $setting['settings'];
						$args["tax_query"][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $categoryIds,
							'operator' => ((isset($setting['logic']) && $setting['logic'] == 'or') || sizeof($categoryIds) <= 1 ? 'IN' : 'AND'),
							'include_children' => (isset($setting['children']) && $setting['children'] == '1'),
						);
						break;
					case 'wpfTags':
						$tagsIdStr = $setting['settings'];
						if($tagsIdStr){
							$args["tax_query"][] = array(
								'taxonomy' => 'product_tag',
								'field'    => 'id',
								'terms'    => $tagsIdStr,
								'operator' => ((isset($setting['logic']) && $setting['logic'] == 'or') || sizeof($tagsIdStr) <= 1 ? 'IN' : 'AND'),
							);
						}
						break;
					case 'wpfAttribute':
						$attrIds = $setting['settings'];
						if($attrIds){
                            $taxonomy = '';
                            foreach ($attrIds as $attr) {
                                $term = get_term( $attr );
                                $taxonomy = $term->taxonomy;
                                break;
                            }
							$args["tax_query"][] = array(
								'taxonomy' => $taxonomy,
								'field'    => 'id',
								'terms'    => $attrIds,
								'operator' => (isset($setting['logic']) && $setting['logic'] == 'or' ? 'IN' : 'AND')
							);
						}
						break;
					case 'wpfAuthor':
						$authorId = $setting['settings'][0];
						if($authorId){
							$args['author'] = $authorId;
						}
						break;
					case 'wpfFeatured':
						$enable = $setting['settings'][0];
						if($enable === '1'){
							$args["tax_query"][] = array(
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',
								'operator' => 'IN',
							);
						}
						break;
					case 'wpfOnSale':
						$enable = $setting['settings'][0];
						if($enable === '1'){
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
							$args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
						}
						break;
					case 'wpfInStock':
						$stockstatus = $setting['settings'];
						if($stockstatus){
							$metaQuery = array(
								'key' => '_stock_status',
								'value' => $stockstatus,
								'compare' => 'IN'
							);
							$args['meta_query'][] = $metaQuery;
						}
						break;
					case 'wpfRating':
						$ratingRange = $setting['settings'];
						if(is_array($ratingRange)){
							foreach($ratingRange as $range){
								$range = explode('-', $range);
								break;
							}
							if(intval($range[1]) !== 5){
								$range[1] = $range[1] - 0.001;
							}
							if($range[0] && $range[1]){
								$metaQuery = array(
									'key' => '_wc_average_rating',
									'value' => array($range[0], $range[1]),
									'type' => 'DECIMAL',
									'compare' => 'BETWEEN'
								);
								$args['meta_query'][] = $metaQuery;
							}
						}
						break;
                    case 'wpfBrand':
                        $brandsIdStr = $setting['settings'];
                        if($brandsIdStr){
                            $args["tax_query"][] = array(
                                'taxonomy' => 'product_brand',
                                'field'    => 'id',
                                'terms'    => $brandsIdStr,
                                'operator' => "AND"
                            );
                        }
                        break;
				}
			}
		}
		dispatcherWpf::doAction('addArgsForFilteringBySettings', $settings);

		if(isset($args["tax_query"]) && !empty($args["tax_query"])) {
			$args["tax_query"]['relation'] = 'AND';
		}
		if(isset($temp['wpfPrice'])) {
			$args['meta_query'][] = $this->getModule()->preparePriceFilter($temp['wpfPrice']['min_price'], $temp['wpfPrice']['max_price']);
		}
		/*if (!isset($temp['wpfInStock'])) {
            $args['meta_query'][] = array('key' => '_stock_status', 'value' => 'instock');
        }*/
		$filterSettings['sort_by_title'] = !empty( $filterSettings['sort_by_title'] ) ? $filterSettings['sort_by_title'] : false;
		if ( $filterSettings['sort_by_title'] ) {
			$args['orderby'] = !empty( $args['orderby'] ) ? $args['orderby'].' title' : 'title';
		}
		if(empty($args['orderby'])) {
			$WC_Query = new WC_Query();
			$vars = $WC_Query->get_catalog_ordering_args();
			if(is_array($vars) && !empty($vars['orderby'])) {
				$args['orderby'] = $vars['orderby'];
				$args['order'] = empty($vars['order']) ? 'ASC' : $vars['order'];
			} else {
				$args['orderby'] = 'menu_order title';
				$args['order'] = 'ASC';
			}
        }
        $args = $this->getModule()->addWooOptions($args);

		return $args;
	}



}
