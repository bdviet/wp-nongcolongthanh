<?php
class woofiltersViewWpf extends viewWpf {
	public static $filterExistsTerms = null;

	public function getTabContent() {
		frameWpf::_()->getModule('templates')->loadJqGrid();
		frameWpf::_()->addScript('admin.woofilters.list', $this->getModule()->getModPath(). 'js/admin.woofilters.list.js');
		frameWpf::_()->addScript('adminCreateTableWpf', $this->getModule()->getModPath(). 'js/create-filter.js', array(), false, true);
		frameWpf::_()->getModule('templates')->loadFontAwesome();
		frameWpf::_()->addJSVar('admin.woofilters.list', 'wpfTblDataUrl', uriWpf::mod('woofilters', 'getListForTbl', array('reqType' => 'ajax')));
		frameWpf::_()->addJSVar('admin.woofilters.list', 'url', admin_url('admin-ajax.php'));
		frameWpf::_()->getModule('templates')->loadBootstrapSimple();
		frameWpf::_()->addStyle('admin.filters', $this->getModule()->getModPath(). 'css/admin.woofilters.css');
		$this->assign('addNewLink', frameWpf::_()->getModule('options')->getTabUrl('woofilters#wpfadd'));

		return parent::getContent('woofiltersAdmin');
	}

	public function getEditTabContent($idIn) {
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();
		if(!$isWooCommercePluginActivated) {
			return;
		}
		$idIn = isset($idIn) ? (int) $idIn : 0;
		$filter = $this->getModel('woofilters')->getById($idIn);
		$settings = unserialize($filter['setting_data']);
		$modPath = $this->getModule()->getModPath();
		frameWpf::_()->getModule('templates')->loadChosenSelects();
		frameWpf::_()->getModule('templates')->loadBootstrapSimple();
		frameWpf::_()->getModule('templates')->loadJqueryUi();
		frameWpf::_()->addScript('notify-js', WPF_JS_PATH. 'notify.js', array(), false, true);
		frameWpf::_()->addScript('chosen.order.jquery.min.js', $modPath. 'js/chosen.order.jquery.min.js');
		frameWpf::_()->addScript('admin.filters', $modPath. 'js/admin.woofilters.js');
		frameWpf::_()->addScript('admin.wp.colorpicker.alhpa.js', $modPath. 'js/admin.wp.colorpicker.alpha.js');
		frameWpf::_()->addScript('adminCreateTableWpf', $modPath.'js/create-filter.js', array(), false, true);
		frameWpf::_()->addJSVar('admin.filters', 'url', admin_url('admin-ajax.php'));
		//frameWpf::_()->addStyle('common.filters', $modPath. 'css/common.woofilters.css');
		frameWpf::_()->addStyle('admin.filters', $modPath. 'css/admin.woofilters.css');
		frameWpf::_()->addStyle('frontend.multiselect', $modPath. 'css/frontend.multiselect.css');
		frameWpf::_()->addScript('frontend.multiselect', $modPath. 'js/frontend.multiselect.js');

		frameWpf::_()->addStyle('frontend.filters', $modPath. 'css/frontend.woofilters.css');
		frameWpf::_()->addScript('frontend.filters', $modPath. 'js/frontend.woofilters.js');
		frameWpf::_()->addStyle('custom.filters', $modPath. 'css/custom.woofilters.css');
		frameWpf::_()->addScript('jquery.slider.js.jshashtable', $modPath. 'js/jquery_slider/jshashtable-2.1_src.js');
		frameWpf::_()->addScript('jquery.slider.js.numberformatter', $modPath. 'js/jquery_slider/jquery.numberformatter-1.2.3.js');
		frameWpf::_()->addScript('jquery.slider.js.tmpl', $modPath. 'js/jquery_slider/tmpl.js');
		frameWpf::_()->addScript('jquery.slider.js.dependClass', $modPath. 'js/jquery_slider/jquery.dependClass-0.1.js');
		frameWpf::_()->addScript('jquery.slider.js.draggable', $modPath. 'js/jquery_slider/draggable-0.1.js');
		frameWpf::_()->addScript('jquery.slider.js', $modPath. 'js/jquery_slider/jquery.slider.js');
		frameWpf::_()->addStyle('jquery.slider.css', $modPath. 'css/jquery.slider.min.css');


		frameWpf::_()->addStyle('loaders', $modPath. 'css/loaders.css');

		dispatcherWpf::doAction('addScriptsContent', true, $settings);

		$link = frameWpf::_()->getModule('options')->getTabUrl( $this->getCode() );
        $linkSetting = frameWpf::_()->getModule('options')->getTabUrl( 'settings' );
		$proLink = frameWpf::_()->getModule('promo')->getWooBeWooPluginLink();
		$this->assign('proLink', $proLink);
		$this->assign('link', $link);
        $this->assign('linkSetting', $linkSetting);
		$this->assign('settings', $settings);
		$this->assign('filter', $filter);
		$this->assign('is_pro', frameWpf::_()->isPro());

		return parent::getContent('woofiltersEditAdmin');
	}

	public function renderHtml($params){
		frameWpf::_()->getModule('templates')->loadCoreJs();
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();

		if(!$isWooCommercePluginActivated) {
			return;
		}
		$html = '';
		$modPath = $this->getModule()->getModPath();
		frameWpf::_()->addScript('jquery-ui-slider');
		frameWpf::_()->addScript('jquery-touch-punch');

		frameWpf::_()->addStyle('frontend.filters', $modPath. 'css/frontend.woofilters.css');
		frameWpf::_()->addScript('frontend.filters', $modPath. 'js/frontend.woofilters.js');
		frameWpf::_()->addStyle('frontend.multiselect', $modPath. 'css/frontend.multiselect.css');
		frameWpf::_()->addScript('frontend.multiselect', $modPath. 'js/frontend.multiselect.js');
		frameWpf::_()->addStyle('loaders', $modPath. 'css/loaders.css');
		frameWpf::_()->addJSVar('frontend.filters', 'url', admin_url('admin-ajax.php'));
		frameWpf::_()->getModule('templates')->loadJqueryUi();
		frameWpf::_()->getModule('templates')->loadFontAwesome();

//		frameWpf::_()->addScript('jquery.slider.js', $modPath. 'js/jquery.slider.min.js');
		frameWpf::_()->addScript('jquery.slider.js.jshashtable', $modPath. 'js/jquery_slider/jshashtable-2.1_src.js');
		frameWpf::_()->addScript('jquery.slider.js.numberformatter', $modPath. 'js/jquery_slider/jquery.numberformatter-1.2.3.js');
		frameWpf::_()->addScript('jquery.slider.js.tmpl', $modPath. 'js/jquery_slider/tmpl.js');
		frameWpf::_()->addScript('jquery.slider.js.dependClass', $modPath. 'js/jquery_slider/jquery.dependClass-0.1.js');
		frameWpf::_()->addScript('jquery.slider.js.draggable', $modPath. 'js/jquery_slider/draggable-0.1.js');
		frameWpf::_()->addScript('jquery.slider.js', $modPath. 'js/jquery_slider/jquery.slider.js');

		frameWpf::_()->addStyle('jquery.slider.css', $modPath. 'css/jquery.slider.min.css');

		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		if(isset($options['move_sidebar']) && isset($options['move_sidebar']['value']) && !empty($options['move_sidebar']['value'])){
			frameWpf::_()->addStyle('move.sidebar.css', $modPath. 'css/move.sidebar.css');
		}

		$id = isset($params['id']) ? (int) $params['id'] : 0;
		if(!$id){
			return false;
		}

		$filter = $this->getModel('woofilters')->getById($id);
		if (isset($params['settings'])) {
			$params['settings']['filters']['order'] = stripcslashes($params['settings']['filters']['order']);
			if(!empty($params['settings']['css_editor'])){
				$params['settings']['css_editor'] = base64_encode($params['settings']['css_editor']);
			}
			$settings = $params;
		} else {
			$settings = unserialize($filter['setting_data']);
		}

		if(!$this->getFilterSetting($settings['settings'], 'disable_plugin_styles', false)){
			frameWpf::_()->addStyle('custom.filters', $modPath. 'css/custom.woofilters.css');
		}
		dispatcherWpf::doAction('addScriptsContent', false, $settings);

		$viewId = $id . '_' . mt_rand(0, 999999);

		$displayShop = false;
		$displayCategory = false;
	    $displayTag = false;
	    $displayMobile = true;

		if(is_admin()) {
			$displayShop = true;
		} else {
			if(!empty($settings["settings"]['display_on_page'])
				&& ( $settings["settings"]['display_on_page'] === 'shop'
				|| $settings["settings"]['display_on_page'] === 'both') ){
				$displayShop = true;
			}
			if(!empty($settings["settings"]['display_on_page'])
				&& ( $settings["settings"]['display_on_page'] === 'category'
					|| $settings["settings"]['display_on_page'] === 'both') ){
				$displayCategory = true;
			}
	        if(!empty($settings["settings"]['display_on_page'])
	            && ( $settings["settings"]['display_on_page'] === 'tag'
	                || $settings["settings"]['display_on_page'] === 'both') ){
	            $displayTag = true;
	        }

			if(!empty($settings["settings"]['display_for'])){
				if($settings["settings"]['display_for'] === 'mobile'){
					$displayMobile = utilsWpf::isMobile();
				}else if($settings["settings"]['display_for'] === 'both'){
					$displayMobile = true;
				}else if($settings["settings"]['display_for'] === 'desktop'){
					$displayMobile = !utilsWpf::isMobile();
				}
			}
		}
		$hideWithoutProducts = $this->getFilterSetting($settings['settings'], 'hide_without_products', false);
		$displayMode = woocommerce_get_loop_display_mode();

		if(!$hideWithoutProducts || $displayMode != 'subcategories') {
			$cat_id = $this->_hasShortcodeProductCatId();
			if(is_product_category() && $displayCategory && $displayMobile){
				$catObj = get_queried_object();
				$html = $this->generateFiltersHtml($settings, $viewId, $catObj->term_id);
			}else if(is_shop() && $displayShop && $displayMobile){
				$html = $this->generateFiltersHtml($settings, $viewId);
			}else if(is_product_tag() && $displayTag && $displayMobile){
				$catObj = get_queried_object();
				$html = $this->generateFiltersHtml($settings, $viewId, false, false, $catObj->term_id);
	        }else if(is_tax('product_brand') && $displayShop && $displayMobile){
	            $catObj = get_queried_object();
	            $html = $this->generateFiltersHtml($settings, $viewId, false, false, false, $catObj->term_id);
	        }else if($displayShop && $displayMobile && !is_product_category() && !is_product_tag()){
				$html = $this->generateFiltersHtml($settings, $viewId, $cat_id, true);
			}
		}

		$this->assign('viewId', $viewId);
		$this->assign('html', $html);

		return parent::getContent('woofiltersHtml');
	}

	private function _hasShortcodeProductCatId(){
        $obj = get_queried_object();
        if ($obj instanceof WP_Post){
        	if (has_shortcode( $obj->post_content, 'products' )) {
                preg_match_all( '/' . get_shortcode_regex(array('products')) . '/', $obj->post_content, $matches, PREG_SET_ORDER );
                if (!empty($matches)) {
                    $attr = shortcode_parse_atts( $matches[0][3] );
                    if (isset($attr['category'])) {
                        $category_name = strpos($attr['category'],',') !== false ? explode(',',$attr['category']) : array($attr['category']);
                        if(is_int($category_name[0])){
                            $cat = get_term_by('id', $category_name[0], 'product_cat');
                        } else {
                            $cat = get_term_by('slug', $category_name[0], 'product_cat');
                            $cat = empty($cat) ? get_term_by('name', $category_name[0], 'product_cat') : $cat;
                        }

                        return !empty($cat) ? $cat->term_id : false;
                    }
                }
            }
        }

        return false;
	}

	//for now after render we run once filtering, in order to display products on custom page.
	public function renderProductsListHtml($params){
		$html = '<div class="woocommerce wpfNoWooPage">';
			$html .= '<p class="woocommerce-result-count"></p>';
			$html .= '<ul class="products columns-4"></ul>';
			$html .= '<nav class="woocommerce-pagination"></nav>';
			$html .= '<script>jQuery(document).ready(function(){ setTimeout(function() {jQuery("body").trigger("wpffiltering"); }, 1000); })</script>';
		$html .= '</div>';

		return $html;
	}

	public function setFilterExistsTerms($settings) {
		if(is_null(self::$filterExistsTerms)) {
			$module = $this->getModule();
			$taxonomies = $module->getFilterTaxonomies($settings);
			$terms = $module->getFilterExistsTerms(null, $taxonomies);
			self::$filterExistsTerms = isset($terms['exists']) ? $terms['exists'] : false;
		}
		return self::$filterExistsTerms;
	}
	public function resetFilterExistsTerms() {
		self::$filterExistsTerms = null;
	}

	public function generateFiltersHtml($filterSettings, $viewId, $prodCatId = false, $noWooPage = false, $productTag = false, $productBrand = false){
		$customCss = '';
		if(!empty($filterSettings['settings']['css_editor'])){
			//$filterSettings['settings']['css_editor'] = stripslashes(base64_decode($filterSettings['settings']['css_editor']));
			$customCss = stripslashes(base64_decode($filterSettings['settings']['css_editor']));
			unset($filterSettings['settings']['css_editor']);
		}
		if(!empty($filterSettings['settings']['js_editor'])){
			$filterSettings['settings']['js_editor'] = stripslashes(base64_decode($filterSettings['settings']['js_editor']));
		}

		$settingsOriginal = $filterSettings;
		$filtersOrder = utilsWpf::jsonDecode($filterSettings["settings"]['filters']['order']);

		$buttonsPosition = (!empty($filterSettings['settings']['main_buttons_position'])) ? $filterSettings['settings']['main_buttons_position'] : 'bottom' ;
		$showCleanButton = (!empty($filterSettings['settings']['show_clean_button'])) ? $filterSettings['settings']['show_clean_button'] : false ;
		$showFilteringButton = (!empty($filterSettings['settings']['show_filtering_button'])) ? $filterSettings['settings']['show_filtering_button'] : false ;
        $filterButtonWord = (!empty($filterSettings['settings']['filtering_button_word'])) ? $filterSettings['settings']['filtering_button_word'] : __('Filter', WPF_LANG_CODE) ;
        $clearButtonWord = ($showCleanButton && !empty($filterSettings['settings']['show_clean_button_word'])) ? $filterSettings['settings']['show_clean_button_word'] : __('Clear', WPF_LANG_CODE) ;
        $enableAjax = (!empty($filterSettings['settings']['enable_ajax'])) ? $filterSettings['settings']['enable_ajax'] : 0 ;
		/*if($enableAjax == 1) {
			$showFilteringButton = false;
		}*/

		global $wp_query;
		$postPerPage = function_exists('wc_get_default_products_per_row') ? wc_get_default_products_per_row() * 4 : get_option('posts_per_page');
		$options = frameWpf::_()->getModule('options')->getModel('options')->getAll();
		if(isset($options['count_product_shop']) && isset($options['count_product_shop']['value']) && !empty($options['count_product_shop']['value']) ){
			$postPerPage = $options['count_product_shop']['value'];
		}

		$paged = isset($wp_query->query_vars['paged']) ? $wp_query->query_vars['paged'] : 1;
		//get all link
		$base = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ));
		//get only base link, remove all query params
		$base = explode( '?', $base );
		$base = $base[0];
		// if ( wc_get_loop_prop( 'is_shortcode' ) ) {
		// 	//get all link
		// 	$base = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ));
		// 	//get only base link, remove all query params
		// 	$base = explode( '?', $base );
		// 	$base = $base[0];
		// } else {
		// 	global $wp;
		// 	$base = home_url( $wp->request );
		// 	$base = $base.'/';
		// 	$base = $this->wpfCurrentLocation();
		// }
		$querySettings = array(
			'posts_per_page' => $postPerPage,
			'paged' => $paged,
			'base' => $base,
			'page_id' => $this->wpfGetPageId(),
		);
		if($prodCatId){
			$querySettings['product_category_id'] = $prodCatId;
		}
		if ($productTag) {
			$querySettings['product_tag'] = $productTag;
		}
        $isPro = frameWpf::_()->isPro();
        if ($isPro && $productBrand) {
            $querySettings['product_brand'] = $productBrand;
        }
		$querySettingsStr =  htmlentities(utilsWpf::jsonEncode($querySettings));
		unset($filterSettings['settings']['styles']);
		$filterSettings = htmlentities(utilsWpf::jsonEncode($filterSettings));
		$noWooPageData = '';
		if($noWooPage){
			$noWooPageData = 'data-nowoo="true"';
		}
		$settings = $this->getFilterSetting($settingsOriginal, 'settings', array());
		$isMobile = utilsWpf::isMobile();

		$width = false;
		$units = false;
		if($isMobile) {
			$width = $this->getFilterSetting($settings, 'filter_width_mobile', false, true);
			$units = $this->getFilterSetting($settings, 'filter_width_in_mobile', false, false, array('%', 'px'));
		}
		if(!$width || !$units) {
			$width = $this->getFilterSetting($settings, 'filter_width', '100', true);
			$units = $this->getFilterSetting($settings, 'filter_width_in', '%', false, array('%', 'px'));
		}

		//$width = $this->getFilterSetting($settingsOriginal['settings'], 'filter_width', '100', true);
		//$style = 'position:relative;width:'.$width.$this->getFilterSetting($settingsOriginal['settings'], 'filter_width_in', '%', false, array('%', 'px')).';';
		$style = 'position:relative;width:'.$width.$units.';';
		$filterId = 'wpfMainWrapper-'.$viewId;
		$html = '<div class="wpfMainWrapper" id="'.$filterId.'" data-viewid="'.$viewId.'" data-settings="'.$querySettingsStr.'" data-filter-settings="'.$filterSettings.'" '.$noWooPageData.' style="'.$style.'">';
		$html = dispatcherWpf::applyFilters('addHtmlBeforeFilter', $html, $settings);

		if( ($buttonsPosition === 'top' || $buttonsPosition === 'both' ) && ($showFilteringButton || $showCleanButton )){
			$html .= '<div class="wpfFilterButtons">';

			if($showFilteringButton){
				$html .= '<button class="wpfFilterButton wpfButton">'. $filterButtonWord. '</button>';
			}
			if($showCleanButton){
				$html .= '<button class="wpfClearButton wpfButton">'. $clearButtonWord. '</button>';
			}
			$html .= '</div>';
		}

		$width = false;
		$units = false;
		if($isMobile) {
			$width = $this->getFilterSetting($settings, 'filter_block_width_mobile', false, true);
			$units = $this->getFilterSetting($settings, 'filter_block_width_in_mobile', false, false, array('%', 'px'));
		}
		if(!$width || !$units) {
			$width = $this->getFilterSetting($settings, 'filter_block_width', '100', true);
			$units = $this->getFilterSetting($settings, 'filter_block_width_in', '%', false, array('%', 'px'));
		}
		$blockWidth = $width.$units;
		//$blockWidth = $this->getFilterSetting($settingsOriginal['settings'], 'filter_block_width', '100', true).$this->getFilterSetting($settingsOriginal['settings'], 'filter_block_width_in', '%', false, array('%', 'px'));
		$blockHeight = $this->getFilterSetting($settingsOriginal['settings'], 'filter_block_height', false, true);
		$blockStyle = 'visibility:hidden;width:'.$blockWidth.';'.($blockWidth == '100%' ? '' : 'float:left;').($blockHeight ? 'height:'.$blockHeight.'px;overflow: hidden;' : '');

		if($isPro) {
			$proView = frameWpf::_()->getModule('woofilterpro')->getView();
		}

		$this->setFilterExistsTerms($filtersOrder);

		$dontRunByLoad = $this->getFilterSetting($settingsOriginal['settings'], 'dont_run_by_load', 0) == 1;
		$runFilterNow = false;
		foreach ($filtersOrder as $key => $filter){
			if($filter['settings']['f_enable'] !== true){
				continue;
			}
			$filter = dispatcherWpf::applyFilters('controlFilterSettings', $filter);
			$filter['blockAttributes'] = empty($filter['blockAttributes']) ? '' : ' '.$filter['blockAttributes'];

			$method = 'generate'. str_replace('wpf', '', $filter['id']). 'FilterHtml';
			if($filter['id'] !== 'wpfCategory'){
				if($isPro && method_exists($proView, $method)) {
					$html .= $proView->{$method}($filter, $settingsOriginal, $blockStyle, $key, $viewId);
				} elseif(method_exists($this, $method)) {
					$html .= $this->{$method}($filter, $settingsOriginal, $blockStyle, $key);
				}
			}else{
				$html .= $this->{$method}($filter, $settingsOriginal, $blockStyle, $prodCatId, $key);
			}

			if (
				(isset($filter['settings']['f_hidden_tags']) && $filter['settings']['f_hidden_tags'])
			||	(isset($filter['settings']['f_hidden_categories']) && $filter['settings']['f_hidden_categories'])
			||	(isset($filter['settings']['f_hidden_attributes']) && $filter['settings']['f_hidden_attributes'])
			){
                $runFilterNow = true;
			}
		}

		if ($runFilterNow && !$dontRunByLoad && !is_admin()){
            frameWpf::_()->addJSVar('frontend.filters', 'run_wpf_filter', '1');
		}

		if( ($buttonsPosition === 'bottom' || $buttonsPosition === 'both' ) && ($showFilteringButton || $showCleanButton )){
			$html .= '<div class="wpfFilterButtons">';

			if($showFilteringButton){
				$html .= '<button class="wpfFilterButton wpfButton">'. $filterButtonWord. '</button>';
			}
			if($showCleanButton){
				$html .= '<button class="wpfClearButton wpfButton">'. $clearButtonWord. '</button>';
			}
			$html .= '</div>';
		}	
		if($isPro && method_exists($proView, 'generateLoaderLayoutHtml')) {
			$html .= $proView->generateLoaderLayoutHtml($options);
		}
		else {
			$html .= '<div class="wpfLoaderLayout" style="position:absolute;top:0;bottom:0;left:0;right:0;background-color: rgba(255, 255, 255, 0.9);z-index: 999;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="position:absolute;z-index:9;top:50%;left:50%;margin-top:-30px;margin-left:-30px;color:rgba(0,0,0,.9);"></i></div>';
			//$html .= '</div>';
		}

		//if loader enable on load
		if(!empty($settingsOriginal['settings']['filter_loader_icon_onload_enable'])){
			$html .= $this->generateLoaderHtml($settingsOriginal);
		}
		//if loader enable on filtering
		if(!empty($settingsOriginal['settings']['enable_overlay'])){
			$html .= $this->generateOverlayHtml($settingsOriginal);
		}

		$html .= '</div>';
		$html = '<style type="text/css" id="wpfCustomCss-'.$viewId.'">'.dispatcherWpf::applyFilters('addCustomCss', $customCss, $settings, $filterId).'</style>'.$html;
		$this->resetFilterExistsTerms();

		return $html;

	}

	public function generateOverlayHtml($settings){
		$settings = $this->getFilterSetting($settings, 'settings', array());
		$overlayBackground = $this->getFilterSetting($settings, 'overlay_background', 'rgba(0,0,0,.5)');

		$html = '';
		$html .= '<style>#wpfOverlay{background-color:'.$overlayBackground.'!important}</style>';
		$html .= '<div id="wpfOverlay">';
		$html .= '<div id="wpfOverlayText">';

		if(!empty($settings['enable_overlay_word']) && !empty($settings['overlay_word'])) {
			$html .= $settings['overlay_word'];
		}
		if(!empty($settings['enable_overlay_icon'])){
			$colorPreview = $this->getFilterSetting($settings, 'filter_loader_icon_color', 'black');
			$iconName = $this->getFilterSetting($settings, 'filter_loader_icon_name', 'default');
			$iconNumber = $this->getFilterSetting($settings, 'filter_loader_icon_number', '0');
		
			if(!frameWpf::_()->isPro()) {
				$iconName = 'default';
			}

			$html .= '<div class="wpfPreview">';
			if($iconName === 'custom'){
				$html .= '<div class="supsystic-filter-loader wpfCustomLoader" style="'.$this->getFilterSetting($settings, 'filter_loader_custom_icon', '').'"></div>';
			}else if($iconName === 'default' || $iconName === 'spinner'){
				$html .= '<div class="supsystic-filter-loader spinner"></div>';
			}else{
				$html .= '<div class="supsystic-filter-loader la-'.$iconName.' la-2x" style="color: '.$colorPreview.'">';
				for($i = 1; $i <= $iconNumber; $i++){
					$html .= '<div></div>';
				}
				$html .= '</div>';
			}
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	public function generateIconCloseOpenTitleHtml($filter, $filterSettings){
		if(empty($filterSettings['settings']['hide_filter_icon'])){
			return '';
		}
		if(!empty($filter['settings']['f_enable_title']) && $filter['settings']['f_enable_title'] === 'yes_open'){
			$icon = '<i class="fa fa-chevron-up"></i>';
		}else if(!empty($filter['settings']['f_enable_title']) && $filter['settings']['f_enable_title'] === 'yes_close'){
			$icon = '<i class="fa fa-chevron-down"></i>';
		}else{
			$icon = '';
		}
		return $icon;
	}
	public function generateDescriptionHtml($filter) {
		$description = $filter['settings']['f_description'] ? $filter['settings']['f_description'] : false;
		if($description){
			$html = '<div class="wfpDescription">'.$description.'</div>';
		}else{
			$html = '';
		}
		return $html;
	}
	public function generateBlockClearHtml($filter, $filterSettings) {
		$html = '';
		if($this->getFilterSetting($filterSettings['settings'], 'show_clean_block', false)) {
			$clearWord = $this->getFilterSetting($filterSettings['settings'], 'show_clean_block_word', false);
            $clearWord = $clearWord ? $clearWord : __('clear', WPF_LANG_CODE);
			$html = ' <label class="wpfBlockClear">'. $clearWord. '</label>';
		}
		return $html;
	}
	public function generateFilterHeaderHtml($filter, $filterSettings) {
		$enableTitle = $this->getFilterSetting($filter['settings'], 'f_enable_title');
		$title = $enableTitle == 'no' ? false : $this->getFilterSetting($filter['settings'], 'f_title', false);

		$html = '';
		if($title) {
			$icon = $this->generateIconCloseOpenTitleHtml($filter, $filterSettings);
			$html .= '<div class="wpfFilterTitle"><div class="wfpTitle'.($this->getFilterSetting($filterSettings['settings'], 'hide_filter_icon', 0) ? ' wfpClickable' : '').'">'.$title.'</div>' . $icon;
		}

		/*$style = '';
		if($enableTitle == 'yes_close'){
			$style = 'style="display: none;"';
		}*/

		$html .= $this->generateBlockClearHtml($filter, $filterSettings);
		if($title) $html .= '</div>';

		$html .= '<div class="wpfFilterContent' . ($enableTitle == 'yes_close' ? ' wpfBlockAnimated wpfHide' : '') . '">';

		return $html;
	}


	public function generatePriceFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
		// Find min and max price in current result set.
		$prices = $this->wpfGetFilteredPrice();

		$settings = $this->getFilterSetting($filter, 'settings', array());
		$filterName = 'min_price,max_price';

		$settings['minPrice'] = $prices->wpfMinPrice === '0' ? '0.01' : $prices->wpfMinPrice;
		$settings['maxPrice'] = $prices->wpfMaxPrice;

		$noActive = reqWpf::getVar('min_price') && reqWpf::getVar('max_price') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-price-skin="default" data-get-attribute="'.$filterName.
			'" data-minvalue="'.$prices->wpfMinPrice.'" data-maxvalue="'.$prices->wpfMaxPrice.'" data-slug="'.__('price', WPF_LANG_CODE).
			'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>'.
			$this->generateFilterHeaderHtml($filter, $filterSettings).
			$this->generateDescriptionHtml($filter).
			'<div id="wpfSliderRange" class="wpfPriceFilterRange"></div>'.
			$this->generatePriceInputsHtml($settings).
			'</div>';
		$html .= '</div>';
		return $html;
	}
	public function generatePriceInputsHtml($settings){
		$dataStep = 1;

		if(class_exists('frameWcu')) {
			$currencySwitcher = frameWcu::_()->getModule('currency');
			if(isset($currencySwitcher)) {
				$currentCurrency = $currencySwitcher->getCurrentCurrency();
				$cryptoCurrencyList = $currencySwitcher->getCryptoCurrencyList();
				if(array_key_exists($currentCurrency, $cryptoCurrencyList)) {
					$dataStep = 0.001;
				}
			}
		}
		$hideInputs = ($this->getFilterSetting($settings, 'f_show_inputs') ? '' : ' wpfHidden');
		if(!isset($settings['minValue']) || is_null($settings['minValue'])) {
			$settings['minValue'] = $settings['minPrice'];
		}
		if(!isset($settings['maxValue']) || is_null($settings['maxValue'])) {
			$settings['maxValue'] = $settings['maxPrice'];
		}

		if( isset($settings['f_currency_show_as']) && ($settings['f_currency_show_as'] === 'symbol') ) {
			$currencyShowAs = get_woocommerce_currency_symbol();
		} else {
			$currencyShowAs = get_woocommerce_currency();
		}

		if( isset($settings['f_currency_position']) && ($settings['f_currency_position'] === 'before') ) {
			$currencySymbolBefore = '<span class="wpfCurrencySymbol">'.$currencyShowAs.'</span>';
			$currencySymbolAfter = '';
		} else {
			$currencySymbolAfter = '<span class="wpfCurrencySymbol">'.$currencyShowAs.'</span>';
			$currencySymbolBefore = '';
		}

		if ( !empty($settings['f_price_tooltip_show_as']) ) {
			$priceTooltip['class'] = 'wpfPriceTooltipShowAsText';
			$priceTooltip['readonly'] = 'readonly';
		}

		$priceTooltip['class'] = isset($priceTooltip['class']) ? $priceTooltip['class'] : '';
		$priceTooltip['readonly'] = isset($priceTooltip['readonly']) ? $priceTooltip['readonly'] : '';

		return '<div class="wpfPriceInputs'.$hideInputs.'">'.$currencySymbolBefore.
			'<div class="input-buffer-min"></div><input '.$priceTooltip['readonly'].' type="number" min="'.$settings['minPrice'].'" max="'.($settings['maxPrice'] - 1) .'" id="wpfMinPrice" class="wpfPriceRangeField '.$priceTooltip['class'].'" value="'.$settings['minValue'].'" />'.
			'<span class="wpfFilterDelimeter"> - </span>'.
			'<div class="input-buffer-max"></div><input '.$priceTooltip['readonly'].' type="number" min="'.$settings['minPrice'].'" max="'.$settings['maxPrice'].'" id="wpfMaxPrice" class="wpfPriceRangeField '.$priceTooltip['class'].'" value="'.$settings['maxValue'].'" /> '.$currencySymbolAfter.
			'<input '.$priceTooltip['readonly'].' type="hidden" id="wpfDataStep" value="'.$dataStep.'" />'.
			'</div>';
	}

	public function generatePriceRangeFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (int) (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
            }
        }

		if($filter['settings']['f_range_by_hands']){
			$ranges = array_chunk(explode(',', $filter['settings']['f_range_by_hands_values']), 2);
			$htmlOpt = $this->generatePriceRangeOptionsHtml($filter, $ranges, $displayItemsInARow);

		}else if($filter['settings']['f_range_automatic']){
			$prices = $this->wpfGetFilteredPrice();

			$minPrice =  $prices->wpfMinPrice === '0' ? '0.01' : $prices->wpfMinPrice;
			$maxPrice =  $prices->wpfMaxPrice;
			$step = !empty($filter['settings']['f_step']) ? $filter['settings']['f_step'] : 50;

			$priceRange = $maxPrice - $minPrice;
			$countElements = ceil($priceRange / $step);
			if($countElements > 100) {
				$step = ceil($priceRange / 1000) * 10;
				$countElements = ceil($priceRange / $step);
			}

			$ranges = array();
			$priceTempOld = 0;
			for($i = 0; $i < $countElements; $i++){
				if($i === 0){
					$priceTemp = $minPrice + $step;
					$ranges[$i] = array($minPrice, $priceTemp - 0.01);
					$priceTempOld = $priceTemp;
				}else if(($priceTempOld + $step) < $maxPrice){
					$priceTemp = $priceTempOld + $step;
					$ranges[$i] = array($priceTempOld, $priceTemp - 0.01);
					$priceTempOld = $priceTemp;
				}else{
					$ranges[$i] = array($priceTempOld, $maxPrice);
				}
			}
			$htmlOpt = $this->generatePriceRangeOptionsHtml($filter, $ranges, $displayItemsInARow);
		}
		if(!$htmlOpt){
			$htmlOpt = __('Price range filter is empty. Please setup filter correctly.', WPF_LANG_CODE);
		}
		$noActive = reqWpf::getVar('min_price') && reqWpf::getVar('max_price') ? '' : 'wpfNotActive';

		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].
			'" data-get-attribute="min_price,max_price" data-slug="'.__('price range', WPF_LANG_CODE).'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>';
		$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$html .= '<div class="wpfCheckboxHier">';
					if($filter['settings']['f_frontend_type'] === 'list'){
						$style = '';
						if(isset($filter['settings']['f_max_height']) && $filter['settings']['f_max_height'] > 0 ){
							$style = 'max-height:' . $filter['settings']['f_max_height'] . 'px';
						}
						$html .= '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="'.$style.'">';
					}
					$html .= $htmlOpt;
					if($filter['settings']['f_frontend_type'] === 'list'){
						$html .= '</ul>';
					}
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>'; //end wpfFilterWrapper

		return $html;
	}

	public function generateSortByFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
		$optionsSelected = reqWpf::getVar('orderby');
		$optionsAll = frameWpf::_()->getModule('woofilters')->getModel('woofilters')->getFilterLabels('SortBy');
		$settings = $this->getFilterSetting($filter, 'settings', array());
		foreach ($optionsAll as $key => $value) {
			$optionsAll[$key] = $this->getFilterSetting($settings, 'f_option_labels['.$key.']', $value);
		}
		$options = $this->getFilterSetting($settings, 'f_options[]', false);
		$options = explode(',', $options);
		$noActive = reqWpf::getVar('orderby') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-get-attribute="orderby" data-slug="'.__('sort by', WPF_LANG_CODE).
			'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
					$html .= '<select>';
						foreach ($options as $option){
							if(!empty($option)) {
								$selected = '';
								if($option === $optionsSelected){
									$selected = 'selected';
								}
								$html .= '<option value="'.$option.'" '.$selected.'>'.(isset($optionsAll[$option]) ? $optionsAll[$option] : '').'</option>';
							}
						}
					$html .= '</select>';
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>'; //end wpfFilterWrapper

		return $html;
	}

	public function generateCategoryFilterHtml($filter, $filterSettings, $blockStyle, $prodCatId = false, $key = 1, $viewId = ''){
		$settings = $this->getFilterSetting($filter, 'settings', array());
		$labels = frameWpf::_()->getModule('woofilters')->getModel('woofilters')->getFilterLabels('Category');
        $hidden_categories = isset($settings['f_hidden_categories']) ? $settings['f_hidden_categories'] : false;
		$includeCategoryId = (!empty($settings['f_mlist[]'])) ? explode(',', $settings['f_mlist[]']) : false;
		
		$excludeIds = !empty($settings['f_exclude_terms']) ? $settings['f_exclude_terms'] : false;
		$hideChild = !empty($settings['f_hide_taxonomy']) ? true : false;
		$args = array(
			'parent' => 0,
			'hide_empty' => $this->getFilterSetting($settings, 'f_hide_empty', false),
			'include' => $includeCategoryId,
		);
		$order = !empty($settings['f_sort_by']) ? $settings['f_sort_by'] : 'asc';
		$orderByInclude = !empty($settings['f_order_custom']) ? 'include' : 'name';
		if($order == 'default' && (!frameWpf::_()->isPro() || $orderByInclude == 'include')) {
			$order = 'asc';
		}
		if($order != 'default') {
			$args['order'] = $order;
			$args['orderby'] = $orderByInclude;
		}

		if($hideChild){
			$args['only_parent'] = $hideChild;
		}
		$showAllCats = $this->getFilterSetting($settings, 'f_show_all_categories', false);
		$taxonomy = 'product_cat';
        list($showedTerms, $countsTerms, $showFilter) = $this->getShowedTerms($taxonomy, $showAllCats);

		$productCategory = $this->getTaxonomyHierarchy($taxonomy, $args);
		if(!$productCategory){
			return '';
		}
		$filterName = 'filter_cat';

		$frontendTypes = array('list', 'dropdown', 'mul_dropdown');
		$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', false, dispatcherWpf::applyFilters('getFrontendFilterTypes', $frontendTypes, $filter['id']));
		$filter['settings']['f_frontend_type'] = $type;

		$isMulti = $type == 'multi';
		//$isHierarchical = $this->getFilterSetting($settings, 'f_show_hierarchical', false);
		if($isMulti && !$hideChild) {
			$filterName .= '_list';
		}
        $filterName .= '_'. $key;
		$catSelected = reqWpf::getVar($filterName);
		if($catSelected){
			$ids = explode('|', $catSelected);
			if(sizeof($ids) <= 1) {
				$ids = explode(',', $catSelected);
			}
			$catSelected = $ids;
		} elseif ($hidden_categories && $includeCategoryId) {
            $catSelected = $includeCategoryId;
		}

        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $ulstyle = $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (int) (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
            }
        }

        $htmlOpt = '';

		if(in_array($type, $frontendTypes) || $isMulti) {
			$htmlOpt = $this->generateTaxonomyOptionsHtml($productCategory, $filter, $catSelected, $excludeIds, '', $displayItemsInARow, $includeCategoryId, $showedTerms, $countsTerms);
        	if($type === 'list' || $type === 'multi'){
				$maxHeight = $this->getFilterSetting($settings, 'f_max_height', 0);
				if($maxHeight > 0 ){
                	$ulstyle .= 'max-height:' . $maxHeight . 'px';
				}
				$htmlOpt = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="' .$ulstyle. '">'.$htmlOpt.'</ul>';
			} else if($type === 'dropdown') {
				$htmlOpt = '<select><option value="" data-slug="">'.__($this->getFilterSetting($settings, 'f_dropdown_first_option_text', 'Select all'), WPF_LANG_CODE).'</option>' . $htmlOpt. '</select>';
			}
		} else {
			$htmlOpt = dispatcherWpf::applyFilters('getTaxonomyOptionsHtml', $htmlOpt, array(
				'type' => $type,
				'settings' => $filter,
				'terms' => $productCategory,
				'selected' => $catSelected,
				'showed' => $showedTerms,
				'counts' => $countsTerms,
				'excludes' => $excludeIds,
				'includes' => $includeCategoryId
			));
		}

		$noActive = $catSelected ? '' : 'wpfNotActive';
        $noActive = $hidden_categories ? 'wpfHidden' : $noActive;
		$showCount = $this->getFilterSetting($settings, 'f_show_count', false) ? ' wpfShowCount' : '';
		$html = '<div class="wpfFilterWrapper '.$noActive.$showCount.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$type.'"'.
			' data-get-attribute="'.$filterName.'" data-query-logic="'.($isMulti ? $this->getFilterSetting($settings, 'f_multi_logic', 'or') : 'or').'"'.
			' data-query-children="'.($isMulti && !$hideChild ? '0' : '1').'" data-slug="'.__('category', WPF_LANG_CODE).'" style="'.(!$showFilter ? 'display:none;' : '').$blockStyle.
			'" data-taxonomy="product_cat" data-show-all="'. (int)$showAllCats.'"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				if($type === 'list' && $this->getFilterSetting($settings, 'f_show_search_input', false)){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.esc_html($this->getFilterSetting($settings, 'f_search_label', $labels['search'])).'"></div>';
				}

				$html .= '<div class="wpfCheckboxHier">';
					$html .= $htmlOpt;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateTagsFilterHtml($filter, $filterSettings, $blockStyle, $key = 0, $viewId = ''){
		$settings = $this->getFilterSetting($filter, 'settings', array());
		$labels = frameWpf::_()->getModule('woofilters')->getModel('woofilters')->getFilterLabels('Tags');

        $hidden_tags = isset($filter['settings']['f_hidden_tags']) ? $filter['settings']['f_hidden_tags'] : false;
		$includeTagsId = !empty($filter['settings']['f_mlist[]']) ? explode(',', $filter['settings']['f_mlist[]']) : false;
		$orderByInclude = !empty($filter['settings']['f_order_custom']) ? 'include' : 'name';
		$order = $filter['settings']['f_sort_by'] ? $filter['settings']['f_sort_by'] : 'asc';
		$excludeIds = !empty($filter['settings']['f_exclude_terms']) ? $filter['settings']['f_exclude_terms'] : false;
		$args = array(
			'order' => $order,
			'orderby' => $orderByInclude,
			'parent' => 0,
			'hide_empty' => !empty($filter['settings']['f_hide_empty']) ? $filter['settings']['f_hide_empty'] : false,
			'include' => $includeTagsId
		);

        $show_all_tags = isset($filter['settings']['f_show_all_tags']) ? $filter['settings']['f_show_all_tags'] : false;
        $taxonomy = 'product_tag';
        list($showedTerms, $countsTerms, $showFilter) = $this->getShowedTerms($taxonomy, $show_all_tags);
        
		$productTag = $this->getTaxonomyHierarchy($taxonomy, $args);
		if(!$productTag){
			return '';
		}

        $tagSelected = reqWpf::getVar('product_tag_'. $key);
        if($tagSelected){
            //$tagSelected = explode(' ', $tagSelected);
            $ids = explode('|', $tagSelected);
			if(sizeof($ids) <= 1) {
				$ids = explode(',', $tagSelected);
			}
			$tagSelected = $ids;

        } elseif($hidden_tags && $includeTagsId){
            $tagSelected = $includeTagsId;
        }

        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $ulstyle = $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (int) (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
            }
        }

		//$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', false, array('list', 'dropdown', 'mul_dropdown'));
		$frontendTypes = array('list', 'dropdown', 'mul_dropdown');
		$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', null, dispatcherWpf::applyFilters('getFrontendFilterTypes', $frontendTypes, $filter['id']));

		$logic = $this->getFilterSetting($settings, 'f_query_logic', 'or', false, array('or', 'and'));
		$htmlOpt = '';

		if(in_array($type, $frontendTypes)) {
			$htmlOpt = $this->generateTaxonomyOptionsHtml($productTag, $filter, $tagSelected, $excludeIds, '', $displayItemsInARow, false, $showedTerms, $countsTerms);
			if($type === 'list') {

				if(isset($filter['settings']['f_max_height']) && $filter['settings']['f_max_height'] > 0 ){
            	    $ulstyle .= 'max-height:' . $filter['settings']['f_max_height'] . 'px;';
				}
				$htmlOpt = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="'.$ulstyle.'">'.$htmlOpt.'</ul>';
			}
			else if($type === 'dropdown') {
				if(!empty($filter['settings']['f_dropdown_first_option_text'])) {
					$htmlOpt = '<option value="" data-slug="">'.__($filter['settings']['f_dropdown_first_option_text'], WPF_LANG_CODE).'</option>' . $htmlOpt;
				} else {
					$htmlOpt = '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>' . $htmlOpt;
				}
				$htmlOpt = '<select>'.$htmlOpt.'</select>';
				$logic = 'or';
			} else {
				$htmlOpt = '<select multiple data-placeholder="'.esc_attr($this->getFilterSetting($settings, 'f_dropdown_first_option_text', __('Select all', WPF_LANG_CODE))).'">'.$htmlOpt.'</select>';
			}
		} else {
			$htmlOpt = dispatcherWpf::applyFilters('getTaxonomyOptionsHtml', $htmlOpt, array(
				'type' => $type,
				'settings' => $filter,
				'terms' => $productTag,
				'selected' => $tagSelected,
				'showed' => $showedTerms,
				'counts' => $countsTerms,
				'excludes' => $excludeIds,
				'includes' => false,
				'display' => $displayItemsInARow,
				'class' => $inLineClass
			));
		}

		$getVars = reqWpf::get('get');
		
		$existGetVar = $this->existGetVarLike($getVars, 'product_tag');
		$noActive = $existGetVar ? '' : 'wpfNotActive';
        if(!$existGetVar && $hidden_tags) $noActive = 'wpfHidden';
		$showCount = $filter['settings']['f_show_count'] ? ' wpfShowCount' : '';
		$html = '<div class="wpfFilterWrapper '.$noActive.$showCount.'" data-filter-type="'.$filter['id'].'" data-query-logic="'.$logic.'" data-display-type="'.$type.
			'" data-get-attribute="product_tag_'. $key. '" data-slug="'.__('tag', WPF_LANG_CODE).'" style="'. (!$showFilter ? 'display:none;' : ''). $blockStyle.
			'" data-taxonomy="product_tag" data-show-all="'. (int)$show_all_tags. '"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				if($type === 'list' && $this->getFilterSetting($settings, 'f_show_search_input', false)){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.esc_html($this->getFilterSetting($settings, 'f_search_label', $labels['search'])).'"></div>';
				}
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $htmlOpt;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateAuthorFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
		$settings = $this->getFilterSetting($filter, 'settings', array());
		$labels = frameWpf::_()->getModule('woofilters')->getModel('woofilters')->getFilterLabels('Author');

		$roleNames = !empty($filter['settings']['f_mlist[]']) ? explode(',', $filter['settings']['f_mlist[]']) : false;
		$filterName = 'pr_author';

		//show all roles if user not make choise
		if(!$roleNames){
			if ( ! function_exists( 'get_editable_roles' ) ) {
				require_once ABSPATH . 'wp-admin/includes/user.php';
			}
			$rolesMain = get_editable_roles();
			foreach($rolesMain as $key => $role){
				$roleNames[] = $key;
			}
		}

		$args = array(
			'role__in' => $roleNames,
			'fields' => array('ID','display_name', 'user_nicename')
		);
		$usersMain = get_users( $args );

		$users = array();
		foreach($usersMain as $key => $user){
			$u = new stdClass;
			$u->term_id = $user->ID;
			$u->name = $user->display_name;
			$u->slug = $user->user_nicename;
			$users[] = $u;
		}

		$authorSelected = reqWpf::getVar('pr_author');

        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $ulstyle = $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (int) (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
            }
            $blockStyle = 'display: inline-block; min-width: auto;';
        }

		$htmlOpt = $this->generateTaxonomyOptionsHtml($users, $filter, array($authorSelected), false, '', $displayItemsInARow);
		$type = $filter['settings']['f_frontend_type'];

		if($type === 'list'){
			if(isset($filter['settings']['f_max_height']) && $filter['settings']['f_max_height'] > 0 ){
                $ulstyle .= 'max-height:' . $filter['settings']['f_max_height'] . 'px';
			}
			$wrapperStart = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="'.$ulstyle.'">';
			$wrapperEnd = '</ul>';
		}else if($type === 'dropdown'){
			$wrapperStart = '<select>';
			if(!empty($filter['settings']['f_dropdown_first_option_text'])){
				$htmlOpt = '<option value="" data-slug="">'.__($filter['settings']['f_dropdown_first_option_text'], WPF_LANG_CODE).'</option>' . $htmlOpt;
			}else{
				$htmlOpt = '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>' . $htmlOpt;
			}
			$wrapperEnd = '</select>';
		}

		$noActive = reqWpf::getVar('pr_author') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$type.'" data-get-attribute="'.$filterName.
			'" data-slug="'.__('author', WPF_LANG_CODE).'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				if($type === 'list' && $this->getFilterSetting($settings, 'f_show_search_input', false)){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.esc_html($this->getFilterSetting($settings, 'f_search_label', $labels['search'])).'"></div>';
				}
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;

	}

	public function generateFeaturedFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
		$filterName = 'pr_featured';

        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $ulstyle = $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (int) (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
            }
            $blockStyle .= 'display: inline-block; min-width: auto;';
        }

		//$wrapperStart = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="'.$ulstyle.'">';
		//$wrapperEnd = '</ul>';

		$u = new stdClass;
		$u->term_id = '1';
		$u->name = 'Featured';
		$u->slug = '1';
		$feature[] = $u;

		$featureSelected = array(reqWpf::getVar($filterName));

		$frontendTypes = array('list');
		$type = $this->getFilterSetting($filter['settings'], 'f_frontend_type', 'list', null, dispatcherWpf::applyFilters('getFrontendFilterTypes', $frontendTypes, $filter['id']));
		$filter['settings']['f_frontend_type'] = $type;

		$htmlOpt = '';
		if(in_array($type, $frontendTypes)) {
			$htmlOpt = $this->generateTaxonomyOptionsHtml($feature, $filter, $featureSelected, false, '', $displayItemsInARow);
			$htmlOpt = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="'.$ulstyle.'">'.$htmlOpt.'</ul>';
		} else {
			$htmlOpt = dispatcherWpf::applyFilters('getTaxonomyOptionsHtml', $htmlOpt, array(
				'type' => $type,
				'settings' => $filter,
				'terms' => $feature,
				'selected' => $featureSelected,
				'display' => $displayItemsInARow,
				'class' => $inLineClass
			));
		}

		$noActive = reqWpf::getVar('pr_featured') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="'.$filterName.
			'" data-slug="'.__('featured', WPF_LANG_CODE).'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $htmlOpt;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper


		return $html;
	}

	public function generateOnSaleFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
		$filterName = 'pr_onsale';
		$settings = $this->getFilterSetting($filter, 'settings', array());

        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $ulstyle = $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (int) (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
            }
            $blockStyle .= 'display: inline-block; min-width: auto;';
        }

		$labels = frameWpf::_()->getModule('woofilters')->getModel('woofilters')->getFilterLabels('OnSale');
		
		$label = $this->getFilterSetting($settings, 'f_checkbox_label', $labels['onsale']);

		$u = new stdClass;
		$u->term_id = '1';
		$u->name = $label;
		$u->slug = '1';
		$onSale[] = $u;

		$onSaleSelected = array(reqWpf::getVar('pr_onsale'));
		$frontendTypes = array('list');
		$type = $this->getFilterSetting($filter['settings'], 'f_frontend_type', 'list', null, dispatcherWpf::applyFilters('getFrontendFilterTypes', $frontendTypes, $filter['id']));
		$filter['settings']['f_frontend_type'] = $type;
		$htmlOpt = '';
		if(in_array($type, $frontendTypes)) {
			$htmlOpt = $this->generateTaxonomyOptionsHtml($onSale, $filter, $onSaleSelected, false, '', $displayItemsInARow);
			$htmlOpt = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="' .$ulstyle. '">'.$htmlOpt.'</ul>';
		} else {
			$htmlOpt = dispatcherWpf::applyFilters('getTaxonomyOptionsHtml', $htmlOpt, array(
				'type' => $type,
				'settings' => $filter,
				'terms' => $onSale,
				'selected' => $onSaleSelected,
				'display' => $displayItemsInARow,
				'class' => $inLineClass
			));
		}

		$noActive = reqWpf::getVar('pr_onsale') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].'" data-get-attribute="'.$filterName.
			'" data-slug="'.__('on sale', WPF_LANG_CODE).'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $htmlOpt;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateInStockFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
		$optionsAll = frameWpf::_()->getModule('woofilters')->getModel('woofilters')->getFilterLabels('InStock');

		$settings = $this->getFilterSetting($filter, 'settings', array());
		$options = $this->getFilterSetting($settings, 'f_options[]', '');
		$options = explode(',', $options);

		$stockSelected = reqWpf::getVar('pr_stock');
        if($stockSelected){
			$stockSelected = explode('|', $stockSelected);
        }

		$inStock = array();
		
		$changeNames = ($this->getFilterSetting($settings, 'f_status_names', '') == 'on');
		//$realOptions = array('' => $this->getFilterSetting($settings, 'f_dropdown_first_option_text', __('Select all', WPF_LANG_CODE)));
		$names = array('instock' => 'in', 'outofstock' => 'out', 'onbackorder' => 'on');
		$i = 0;
		foreach ($options as $key) {
			if(isset($optionsAll[$key])) {
				$i++;
				$u = new stdClass;
				$u->term_id = $i;
				$u->name = $changeNames ? $this->getFilterSetting($settings, 'f_stock_statuses['.$names[$key].']', $optionsAll[$key]) : $optionsAll[$key];
				$u->slug = $key;
				$inStock[] = $u;

				//$realOptions[$key] = $changeNames ? $this->getFilterSetting($settings, 'f_stock_statuses['.$names[$key].']', $optionsAll[$key]) : $optionsAll[$key];
			}
		}

		$frontendTypes = array('dropdown', 'list');
		$type = $this->getFilterSetting($filter['settings'], 'f_frontend_type', 'dropdown', null, dispatcherWpf::applyFilters('getFrontendFilterTypes', $frontendTypes, $filter['id']));
		$filter['settings']['f_frontend_type'] = $type;
		$htmlOpt = '';
		if(in_array($type, $frontendTypes)) {
			$htmlOpt = $this->generateTaxonomyOptionsHtml($inStock, $filter, $stockSelected, false, '', false);
			if($type === 'list') {
				$htmlOpt = '<ul class="wpfFilterVerScroll">'.$htmlOpt.'</ul>';
			}
			else if($type === 'dropdown') {
				$htmlOpt = '<option value="" data-slug="">'.$this->getFilterSetting($settings, 'f_dropdown_first_option_text', __('Select all', WPF_LANG_CODE)).'</option>' . $htmlOpt;
				$htmlOpt = '<select>'.$htmlOpt.'</select>';
				$logic = 'or';
			}
		} else {
			$htmlOpt = dispatcherWpf::applyFilters('getTaxonomyOptionsHtml', $htmlOpt, array(
				'type' => $type,
				'settings' => $filter,
				'terms' => $inStock,
				'selected' => $stockSelected
			));
		}

		$noActive = reqWpf::getVar('pr_stock') ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$filter['settings']['f_frontend_type'].
			'" data-get-attribute="pr_stock" data-slug="'.__('stock status', WPF_LANG_CODE).'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
					//$html .= htmlWpf::selectbox('', array('options' => $realOptions, 'value' => $optionsSelected));
					$html .= $htmlOpt;
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>'; //end wpfFilterWrapper

		return $html;
	}
	public function generateRatingFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = ''){
		$filterName = 'pr_rating';
		$ratingSelected = reqWpf::getVar($filterName);

		$settings = $this->getFilterSetting($filter, 'settings', array());
		$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', null, array('list', 'dropdown', 'mul_dropdown'));
		$filter['settings']['f_frontend_type'] = $type;
		$addText = $this->getFilterSetting($settings, 'f_add_text', __('and up', WPF_LANG_CODE));
		$addText5 = $this->getFilterSetting($settings, 'f_add_text5', __('5 only', WPF_LANG_CODE));

		$wrapperStart = '<ul class="wpfFilterVerScroll">';
		$wrapperEnd = '</ul>';

		$ratingItems = array(
			array('1', $addText5, '5-5'),
			array('2', '4 '.$addText, '4-5'),
			array('3', '3 '.$addText, '3-5'),
			array('4', '2 '.$addText, '2-5'),
			array('5', '1 '.$addText, '1-5'),
		);

		$rating = array();

		foreach($ratingItems as $item){
			$u = new stdClass;
			$u->term_id = $item[2];
			$u->name = $item[1];
			$u->slug = $item[2];
			$rating[] = $u;
		}
        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $ulstyle = $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (int) (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
            }
        }

		$htmlOpt = $this->generateTaxonomyOptionsHtml($rating, $filter, array($ratingSelected), false, '', $displayItemsInARow);

		if($type === 'list') {
			$wrapperStart = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="' .$ulstyle. '">';
			$wrapperEnd = '</ul>';
		} else if($type === 'dropdown'){
			$wrapperStart = '<select>';
			$text = $this->getFilterSetting($settings, 'f_dropdown_first_option_text');

			if(!empty($text)){
				$htmlOpt = '<option value="" data-slug="">'.__($text, WPF_LANG_CODE).'</option>' . $htmlOpt;
			} else {
				$htmlOpt = '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>' . $htmlOpt;
			}
			$wrapperEnd = '</select>';
		} else if($type === 'mul_dropdown') {
			$wrapperStart = '<select multiple>';
			$wrapperEnd = '</select>';
		}

		$noActive = reqWpf::getVar($filterName) ? '' : 'wpfNotActive';
		$html = '<div class="wpfFilterWrapper '.$noActive.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$type.'" data-get-attribute="'.$filterName.
			'" data-slug="'.__('rating', WPF_LANG_CODE).'" style="'.$blockStyle.'"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);

				$html .= '<div class="wpfCheckboxHier">';
					$html .= $wrapperStart;
						$html .= $htmlOpt;
					$html .= $wrapperEnd;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper

		return $html;
	}

	public function generateAttributeFilterHtml($filter, $filterSettings, $blockStyle, $key = 1, $viewId = '') {
		$settings = $this->getFilterSetting($filter, 'settings', array());
		$labels = frameWpf::_()->getModule('woofilters')->getModel('woofilters')->getFilterLabels('Attribute');

		//$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', null, array('list', 'dropdown', 'mul_dropdown'));
		$frontendTypes = array('list', 'dropdown', 'mul_dropdown');
		$type = $this->getFilterSetting($settings, 'f_frontend_type', 'list', null, dispatcherWpf::applyFilters('getFrontendFilterTypes', $frontendTypes, $filter['id']));

		$filter['settings']['f_frontend_type'] = $type;

		//$addHtml = dispatcherWpf::applyFilters('addHtmlBeforeFilter', '', $filter);

        $hidden_atts = isset($filter['settings']['f_hidden_attributes']) ? $filter['settings']['f_hidden_attributes'] : false;
        $includeAttsId = (!empty($settings['f_mlist[]'])) ? explode(',', $settings['f_mlist[]']) : false;
		$attrId = $this->getFilterSetting($settings, 'f_list', 0, true);
		$order = $this->getFilterSetting($settings, 'f_sort_by', 'asc');
        $orderByInclude = !empty($settings['f_order_custom']) ? 'include' : 'name';
		$excludeIds = $this->getFilterSetting($settings, 'f_exclude_terms', false);
		$args = array(
			'parent' => 0,
			'hide_empty' => !empty($filter['settings']['f_hide_empty']) ? $filter['settings']['f_hide_empty'] : false,
            'orderby' => $orderByInclude,
			'order' => $order,
            'include' => $includeAttsId
		);

        $attrName = wc_attribute_taxonomy_name_by_id((int)$attrId);

        $show_all_atts = isset($filter['settings']['f_show_all_attributes']) ? $filter['settings']['f_show_all_attributes'] : false;
        list($showedTerms, $countsTerms, $showFilter) = $this->getShowedTerms($attrName, $show_all_atts);
        
        //doing the sorting through the hook while some themes/plugins impose their own
        if($includeAttsId !== false && $orderByInclude == 'include') {
			$args['wpf_orderby'] = implode(',', $includeAttsId);
			add_filter('get_terms_orderby', array($this, 'wpfGetTermsOrderby'), 99, 2);
		}
		$productAttr = $this->getTaxonomyHierarchy($attrName, $args);
		remove_filter('get_terms_orderby', array($this, 'wpfGetTermsOrderby'), 99, 2);

		if(!$productAttr){
			return '';
		}

        $filterNameSlug = str_replace('pa_', '', $attrName);
        $filterName = 'filter_' . $filterNameSlug;
        $attrLabel = strtolower(wc_attribute_label($attrName));

        $attrSelected = reqWpf::getVar($filterName);
        if($attrSelected){
            $slugs = explode('|', $attrSelected);
            if(sizeof($slugs) <= 1) {
                $slugs = explode(',', $attrSelected);
            }
            $attrSelected = $slugs;
        } elseif ($hidden_atts && $includeAttsId){
            $attrSelected = $includeAttsId;
        }

		$logic = $this->getFilterSetting($settings, 'f_query_logic', 'or', false, array('or', 'and'));

        $displayItemsInARow = (!empty($filterSettings['settings']['display_items_in_a_row'])) ? $filterSettings['settings']['display_items_in_a_row'] : 0 ;
        $ulstyle = $inLineClass = '';
        if($displayItemsInARow) {
            $inLineClass .= ' wpfFilterAsRow';
            $displayColsInARow = (!empty($filterSettings['settings']['display_cols_in_a_row'])) ? $filterSettings['settings']['display_cols_in_a_row'] : 1 ;
            if ($displayColsInARow > 1) {
                $displayItemsInARow = array($displayItemsInARow, $displayColsInARow);
			}
        }
        $htmlOpt = '';

		if(in_array($type, $frontendTypes)) {

			$htmlOpt = $this->generateTaxonomyOptionsHtml($productAttr, $filter, $attrSelected, $excludeIds, '', $displayItemsInARow, false, $showedTerms, $countsTerms);

			if($type == 'list'){
				$height = $this->getFilterSetting($settings, 'f_max_height', 0, true);
				if($height > 0){
	                $ulstyle .= 'max-height:'.$height.'px; ';
				}
				$htmlOpt = '<ul class="wpfFilterVerScroll'. $inLineClass. '" style="' .$ulstyle. '">'.$htmlOpt.'</ul>';
			} else if($type == 'dropdown'){
				$htmlOpt = '<select><option value="" data-slug="">'.$this->getFilterSetting($settings, 'f_dropdown_first_option_text', __('Select all', WPF_LANG_CODE)).'</option>' . $htmlOpt.'</select>';
			}else if($type == 'mul_dropdown'){
				$htmlOpt = '<select multiple data-placeholder="'.esc_attr($this->getFilterSetting($settings, 'f_dropdown_first_option_text', __('Select all', WPF_LANG_CODE))).'">'.$htmlOpt.'</select>';
			}   
		} else {
			$htmlOpt = dispatcherWpf::applyFilters('getTaxonomyOptionsHtml', $htmlOpt, array(
				'type' => $type,
				'settings' => $filter,
				'terms' => $productAttr,
				'selected' => $attrSelected,
				'showed' => $showedTerms,
				'counts' => $countsTerms,
				'excludes' => $excludeIds,
				'includes' => false,
				'display' => $displayItemsInARow,
				'class' => $inLineClass
			));
		}

		$noActive = reqWpf::getVar($filterName) ? '' : 'wpfNotActive';
        $noActive = !reqWpf::getVar($filterName) && $hidden_atts ? 'wpfHidden' : $noActive;
		$showCount = $filter['settings']['f_show_count'] ? ' wpfShowCount' : '';
		$html = '<div class="wpfFilterWrapper '.$noActive.$showCount.'" data-filter-type="'.$filter['id'].'" data-display-type="'.$type.'" data-get-attribute="'.$filterName.
			'" data-query-logic="'.$logic.'" data-slug="'.__($filterNameSlug, WPF_LANG_CODE).'" style="'. (!$showFilter ? 'display:none;' : ''). $blockStyle.
			'" data-taxonomy="'.$attrName.'" data-show-all="'. (int)$show_all_atts. '"'.$filter['blockAttributes'].'>';
			$html .= $this->generateFilterHeaderHtml($filter, $filterSettings);
				$html .= $this->generateDescriptionHtml($filter);
				if($type === 'list' && $this->getFilterSetting($settings, 'f_show_search_input', false)){
					$html .= '<div class="wpfSearchWrapper"><input class="wpfSearchFieldsFilter" type="text" placeholder="'.esc_html($this->getFilterSetting($settings, 'f_search_label', $labels['search'])).'"></div>';
				}
				$html .= '<div class="wpfCheckboxHier">';
					$html .= $htmlOpt;
				$html .= '</div>';//end wpfCheckboxHier
			$html .= '</div>';//end wpfFilterContent
		$html .= '</div>';//end wpfFilterWrapper
		return $html;
	}

	public function getShowedTerms($taxonomy, $showAll) {
		$showFilter = true;
		$showedTerms = false;
		$countsTerms = false;
		$terms = self::$filterExistsTerms;
		if(is_array($terms)) {
			$countsTerms = array();
			if(isset($terms[$taxonomy])) {
				if(!$showAll) $showedTerms = array_keys($terms[$taxonomy]);
				$countsTerms = $terms[$taxonomy];
			} else if(!$showAll) $showedTerms = array();
			if(!$showAll && empty($showedTerms)) $showFilter = false;
		}
		return array($showedTerms, $countsTerms, $showFilter);
	}

	public function getFilterSetting($settings, $name, $default = '', $num = false, $arr = false, $zero = false) {
		//if(!isset($settings[$name]) || empty($settings[$name])) return $default;
		if(!isset($settings[$name])) return $default;
		if(empty($settings[$name])) return ( $zero && ( '0' === $settings[$name] ) ) ? '0' : $default;
		$value = $settings[$name];
		if($num && !is_numeric($value)) {
			$value = str_replace(',', '.', $value);
			if(!is_numeric($value)) return $default;
		}
		if($arr !== false && !in_array($value, $arr)) return $default;
		return $value;
	}

	public function showEditTablepressFormControls() {
		parent::display('woofiltersEditFormControls');
	}

	public function wpfGetTermsOrderby( $orderby, $args ) {
		return isset($args['wpf_orderby']) ? 'FIELD( t.term_id, ' . $args['wpf_orderby'] . ')' : $orderby;
	}


	/**
	 * Recursively get taxonomy and its children
	 *
	 * @param string $taxonomy
	 * @param int $parent - parent term id
	 * @return array
	 */
	public function getTaxonomyHierarchy( $taxonomy, $argsIn, $parent = true) {
		// only 1 taxonomy
		$taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;
		// get all direct decendants of the $parent
		$args = array(
			'hide_empty' => $argsIn['hide_empty'],
		);
		if(isset($argsIn['order'])) {
			$args['orderby'] = !empty($argsIn['orderby']) ? $argsIn['orderby'] : 'name';
			$args['order'] = $argsIn['order'];
		}

		if(!empty($argsIn['include'])){
			$args['include'] = $argsIn['include'];
		}

		if(!empty($argsIn['parent']) && $argsIn['parent'] !== 0){
			$args['parent'] = $argsIn['parent'];
		} else {
			$args['parent'] = 0;
		}

		if($taxonomy === ''){
			return false;
		}

		if ($taxonomy === 'product_cat' && $parent)  {
			$args['parent'] = 0;
		}

		if(!empty($argsIn['include'])){
			$args['include'] = $argsIn['include'];
			$args['parent'] = '';
			$argsIn['only_parent'] = true;
			if(!empty($argsIn['wpf_orderby'])){
				$args['wpf_orderby'] = $argsIn['wpf_orderby'];
			}
		}

		$terms = get_terms( $taxonomy, $args );
		// prepare a new array.  these are the children of $parent
		// we'll ultimately copy all the $terms into this new array, but only after they
		// find their own children

		$children = array();
		// go through all the direct decendants of $parent, and gather their children
		foreach ( $terms as $term ){
			if(empty($argsIn['only_parent'])){
				if(!empty($term->term_id)){
					$args = array(
						'hide_empty' => $argsIn['hide_empty'],
						'parent' => $term->term_id,
					);
					if(isset($argsIn['order'])) {
						//$args['orderby'] = 'name';
						$args['order'] = $argsIn['order'];
						$args['orderby'] = !empty($argsIn['orderby']) ? $argsIn['orderby'] : 'name';
					}

					// recurse to get the direct decendants of "this" term
					$term->children = $this->getTaxonomyHierarchy( $taxonomy, $args, false );
				}
			}
			// add the term to our new array
			$children[ $term->term_id ] = $term;
		}
		// send the results back to the caller
		return $children;
	}

	public function wpfGetFilteredPrice() {
		global $wpdb;
		global $woocommerce;
		$module = frameWpf::_()->getModule('woofilters');

		$args       = isset( $woocommerce->query->get_main_query()->query_vars ) ? $woocommerce->query->get_main_query()->query_vars : false;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();
		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$add_query = $module->addHiddenFilterQuery(array());

		$meta_query = new WP_Meta_Query($meta_query);
		$tax_query  = new WP_Tax_Query($tax_query);
		$add_query  = new WP_Tax_Query($add_query);

		$meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
		$tax_query_sql  = $tax_query->get_sql($wpdb->posts, 'ID');
		$add_query_sql  = $add_query->get_sql($wpdb->posts, 'ID');

		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as wpfMinPrice, max( CEILING( price_meta.meta_value ) ) as wpfMaxPrice FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'].$add_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND price_meta.meta_value > '' ".$add_query_sql['where'];
		$price = $wpdb->get_row( $sql );

		$price->wpfMaxPrice = $module->getCurrencyPrice($price->wpfMaxPrice);
		$price->wpfMinPrice = $module->getCurrencyPrice($price->wpfMinPrice);
		return $price;
	}

	protected function generateTaxonomyOptionsHtmlFromPro($productCategory, $filter = false, $selectedElem, $excludeIds = false, $pre = '', $displayItemsInARow = 0, $includeIds = false, $showedTerms = false, $countsTerms = false) {
		return $this->generateTaxonomyOptionsHtml($productCategory, $filter, $selectedElem, $excludeIds, $pre, $displayItemsInARow, $includeIds, $showedTerms, $countsTerms);
	}

	private function generateTaxonomyOptionsHtml($productCategory, $filter = false, $selectedElem, $excludeIds = false, $pre = '', $displayItemsInARow = 0, $includeIds = false, $showedTerms = false, $countsTerms = false){
		$html = '';
		if($excludeIds && !is_array($excludeIds) ){
			$excludeIds = explode(',', $excludeIds);
		}
        if($includeIds && !is_array($includeIds) ){
            $includeIds = explode(',', $includeIds);
        }
		$showCount = $this->getFilterSetting($filter['settings'], 'f_show_count');
        $showImage = frameWpf::_()->isPro() && $this->getFilterSetting($filter['settings'], 'f_show_images', false);
        if($showImage) {
        	$imgSize = array($this->getFilterSetting($filter['settings'], 'f_images_width', 20), $this->getFilterSetting($filter['settings'], 'f_images_height', 20));
        }
		$type = $this->getFilterSetting($filter['settings'], 'f_frontend_type', 'list');
		$isMulti = $type === 'multi';
		$isCollapsible = $isMulti && $this->getFilterSetting($filter['settings'], 'f_multi_collapsible', false);

		$isHierarchical = $this->getFilterSetting($filter['settings'], 'f_show_hierarchical', false);
        $hideParent = $isHierarchical && $this->getFilterSetting($filter['settings'], 'f_hide_parent', false);

		foreach ($productCategory as $cat) {
			if( !empty($excludeIds) && in_array($cat->term_id, $excludeIds)){
				continue;
			}

			if (!empty($includeIds) && !in_array($cat->term_id, $includeIds)){
                continue;
			}
            if (!isset($cat->parent)) {
                $cat->parent = 0;
			}
			$termId = isset($cat->term_id) ? $cat->term_id : '';

			if($type === 'dropdown' || $type === 'mul_dropdown'){

				$selected = '';
				if(is_array($selectedElem) && (in_array($cat->slug, $selectedElem) || in_array($cat->term_id, $selectedElem))){
					$selected = 'selected';
				}
                $hideTerm = is_array($showedTerms) && (empty($showedTerms) || !in_array($cat->term_id, $showedTerms)) ? ' style="display:none"' : '';

				$slug = isset($cat->slug) ? urldecode($cat->slug) : '';
				$name = isset($cat->name) ? urldecode($cat->name) : '';
				$count = isset($cat->count) ? $cat->count : '';
				//$showCount = isset($showCount) && $showCount ? '<span class="wpfCount">('.$count.')</span>' : '';
				$countHtml = $showCount ? '<span class="wpfCount">('.(isset($countsTerms[$termId]) ? $countsTerms[$termId] : $count).')</span>' : '';
				if ((empty($cat->children) && $cat->parent != 0) || !$hideParent || $cat->parent != 0) {
					$img = '';
					if ($showImage) {
                        $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                        $img = wp_get_attachment_url($thumbnail_id);
					}
                    $html .= '<option data-term-name="' . $name . '" data-term-slug="' . $slug . '" data-count="' . $count . '" data-term-id="' . $termId . '" value="' . $termId . '" data-slug="' . $slug . '" ' . $selected. $hideTerm. ' data-img="'. $img. '">'. $pre. $name. $countHtml. '</option>';
                }
				if(!empty($cat->children)){
					$tmpPre = $isHierarchical ? $pre.'&nbsp;&nbsp;&nbsp;' : $pre;
					$html .= $this->generateTaxonomyOptionsHtml($cat->children, $filter, $selectedElem, false, $tmpPre, $displayItemsInARow, $includeIds, $showedTerms, $countsTerms);
				}
			} else {
                $displayInARow = $displayItemsInARow && is_array($displayItemsInARow) ? $displayItemsInARow[0] : $displayItemsInARow;
                $displayColsInARow = $displayItemsInARow && is_array($displayItemsInARow) ? $displayItemsInARow[1] : 1;
                $style = '';
                if($displayInARow) {
                    $style = 'margin-right:10px;';

                    if ($displayColsInARow > 1) {
                        $width = number_format(100 / $displayColsInARow, '4', '.', '');
                        $style = 'width:'. $width. '%;padding-right:10px;';
                    }
                }
                if (is_array($showedTerms) && (empty($showedTerms) || !in_array($cat->term_id, $showedTerms))) {
                    $style .= 'display:none;';
                }
                $hasChildren = !empty($cat->children);
                if ((empty($cat->children) && $cat->parent != 0) || !$hideParent || $cat->parent != 0) {
                	$displayName = $cat->name;

                    $html .= '<li data-term-id="' . $cat->term_id . '" data-parent="' . $cat->parent . '" data-term-id="' . $cat->term_id . '" data-term-slug="' . urldecode($cat->slug) . '" style="' . $style . '">';
                    $html .= '<label>';
                    //$html .= '<span class="wpfCheckbox' . ($isMulti ? ' wpfMulti' : '') . '">';
                    $cheched = '';

                    if (is_array($selectedElem) && (in_array($cat->slug, $selectedElem) || in_array($cat->term_id, $selectedElem))) {
                        $cheched = 'checked';
                    }
                    $rand = rand(1, 99999);
                    $checkId = 'wpfTaxonomyInputCheckbox' . $cat->term_id . $rand;
                    
                    $checkbox = '<span class="wpfCheckbox' . ($isMulti ? ' wpfMulti' : '') . '"><input type="checkbox" id="' . $checkId . '" ' . $cheched . '><label for="' . $checkId . '"></label></span>';
                    $html .= dispatcherWpf::applyFilters('getOneTaxonomyOptionHtml', $checkbox, array('type' => $type, 'id' => $checkId, 'checked' => $cheched));


                    //$html .= '<input type="checkbox" id="wpfTaxonomyInputCheckbox' . $cat->term_id . $rand . '" ' . $cheched . '>';
                    //$html .= '<label for="wpfTaxonomyInputCheckbox' . $cat->term_id . $rand . '"></label>';
                    //$html .= '</span>';
                    $html .= '<span class="wpfDisplay">';
                    $img = '';
                    if ($showImage) {
                        $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                        $img = wp_get_attachment_image($thumbnail_id, $imgSize, false, array('alt' => $displayName));
                        //$image = wp_get_attachment_url($thumbnail_id);
                        //$img = $image ? '<img src="'. $image. '" width="30" height="" alt="'. $cat->name. '">' : '';
                    }
                    $html .= '<span class="wpfValue">'. $img. $displayName. '</span>';
                    if ($showCount) {
                        $html .= '<span class="wpfCount">(' . (isset($countsTerms[$termId]) ? $countsTerms[$termId] : $cat->count) . ')</span>';
                    }
                    $html .= '</span>';

                    if ($isCollapsible && $hasChildren && $isHierarchical) {
                        $html .= '<span class="wpfCollapsible">+</span>';
                    }

                    $html .= '</label>';
                }
				if($hasChildren){
                    $tmpPre = $isHierarchical ? $pre.'&nbsp;&nbsp;&nbsp;' : $pre;
					if($isHierarchical && !$hideParent){ $html .= '<ul'.($isCollapsible ? ' class="wpfHidden"' : '').'>'; }
					elseif($isHierarchical && $hideParent && $cat->parent != 0){ $html .= '<ul class="wpfHideParent'.($isCollapsible ? ' wpfHidden' : '').'">'; }
					$html .= $this->generateTaxonomyOptionsHtml($cat->children, $filter, $selectedElem, $excludeIds, $tmpPre, $displayItemsInARow, $includeIds, $showedTerms, $countsTerms);
					if($isHierarchical && !$hideParent){ $html .= '</ul>'; }
					elseif($isHierarchical && $hideParent && $cat->parent != 0){ $html .= '</ul>'; }
				}
                if ((empty($cat->children) && $cat->parent != 0) || !$hideParent || $cat->parent != 0) {
                    $html .= '</li>';
                }
			}
		}
		return $html;
	}

	private function generatePriceRangeOptionsHtml($filter, $ranges, $displayItemsInARow = false){
		$html = '';

		$minValue = reqWpf::getVar('min_price');
		$maxValue = reqWpf::getVar('max_price');
		$urlRange = $minValue . ',' . $maxValue;

		if($filter['settings']['f_frontend_type'] === 'list'){
            $displayInARow = $displayItemsInARow && is_array($displayItemsInARow) ? $displayItemsInARow[0] : $displayItemsInARow;
            $displayColsInARow = $displayItemsInARow && is_array($displayItemsInARow) ? $displayItemsInARow[1] : 1;
            $style = '';
            if($displayInARow) {
                $style = 'margin-right:10px;';

                if ($displayColsInARow > 1 && count($ranges) >= $displayColsInARow) {
                    $width = number_format(100 / $displayColsInARow, '4', '.', '');
                    $style = 'width:'. $width. '%;padding-right:10px;';
                }
            }
			foreach ($ranges as $range){
				if(!empty($range['1']) && !empty($range['0'])){
					if($range['1'] === 'i'){
					$price = $this->wpf_get_filtered_price();
						$range['1'] = $price->max_price;
					}
					$module = frameWpf::_()->getModule('woofilters');
					$priceRange = wc_price($range[0]) . ' - ' . wc_price($range[1]);
					$dataRange = $module->getCurrencyPrice($range[0]) . ',' . $module->getCurrencyPrice($range[1]);
					$checked = '';
					if($dataRange === $urlRange){
						$checked = 'checked';
					}
					$html .= '<li data-range="'. $dataRange .'" style="'. $style. '">';
						$html .= '<label>';
							$html .= '<span class="wpfCheckbox">';
								$html .= '<input type="checkbox" '.$checked.'>';
							$html .= '</span>';
							$html .= '<span class="wpfDisplay">';
								$html .= '<span class="wpfValue">'.$priceRange.'</span>';
							$html .= '</span>';
						$html .= '</label>';
					$html .= '</li>';
				}
				?>
				<?php
			}
		}else if($filter['settings']['f_frontend_type'] === 'dropdown'){
			$html .= '<select>';

			if(!empty($filter['settings']['f_dropdown_first_option_text'])){
				$html .= '<option value="" data-slug="">'.__($filter['settings']['f_dropdown_first_option_text'], WPF_LANG_CODE).'</option>';
			}else{
				$html .= '<option value="" data-slug="">'.__('Select all', WPF_LANG_CODE).'</option>';
			}

			foreach ($ranges as $range){
				if(!empty($range['1']) && !empty($range['0'])){
					$priceRange = wc_price($range[0]) . ' - ' . wc_price($range[1]);
					$dataRange = $range[0] . ',' . $range[1];
					$selected = '';
					if($dataRange === $urlRange){
						$selected = 'selected';
					}
					$html .= '<option data-range="'. $dataRange .'" '.$selected.'>'.$priceRange.'</option>';
				}
				?>
				<?php
			}
			$html .= '</select>';
		}

		return $html;
	}

	private function generateLoaderHtml($settings){
		$settings = $this->getFilterSetting($settings, 'settings', array());
		$colorPreview = $this->getFilterSetting($settings, 'filter_loader_icon_color', 'black');
		$iconName = $this->getFilterSetting($settings, 'filter_loader_icon_name', 'default');
		$iconNumber = $this->getFilterSetting($settings, 'filter_loader_icon_number', '0');
		if(!frameWpf::_()->isPro()) {
			$iconName = 'default';
		}
		$htmlPreview = '<div class="wpfPreview wpfPreviewLoader wpfHidden">';
		if($iconName === 'custom'){
			$htmlPreview .= '<div class="supsystic-filter-loader wpfCustomLoader" style="'.$this->getFilterSetting($settings, 'filter_loader_custom_icon', '').'"></div>';
		}else if($iconName === 'spinner' || $iconName === 'default'){
			$htmlPreview .= '<div class="supsystic-filter-loader spinner" ></div>';
		}else{
			$htmlPreview .= '<div class="supsystic-filter-loader la-'.$iconName.' la-2x" style="color: '.$colorPreview.'">';
			for($i = 1; $i <= $iconNumber; $i++){
				$htmlPreview .= '<div></div>';
			}
			$htmlPreview .= '</div>';
		}
		$htmlPreview .= '</div>';
		return $htmlPreview;
	}

	private function wpf_get_filtered_price() {
		global $wpdb;

		$args       = wc()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
			AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}

	public function wpfGetPageId() {
	    global $wp_query, $post;
	    $page_id = false;
	    if (is_home() && get_option('page_for_posts')) {
	        $page_id = get_option('page_for_posts');
	    } elseif (is_front_page() && get_option('page_on_front')) {
	        $page_id = get_option('page_on_front');
	    } else {
	        if (function_exists('is_shop') && is_shop() && get_option('woocommerce_shop_page_id') != '') {
	            $page_id = get_option('woocommerce_shop_page_id');
	        } else {
	            if (function_exists('is_cart') && is_cart() && get_option('woocommerce_cart_page_id') != '') {
	                $page_id = get_option('woocommerce_cart_page_id');
	            } else {
	                if (function_exists('is_checkout') && is_checkout() && get_option('woocommerce_checkout_page_id') != '') {
	                    $page_id = get_option('woocommerce_checkout_page_id');
	                } else {
	                    if (function_exists('is_account_page') && is_account_page() && get_option('woocommerce_myaccount_page_id') != '') {
	                        $page_id = get_option('woocommerce_myaccount_page_id');
	                    } else {
	                        if ($wp_query && !empty($wp_query->queried_object) && !empty($wp_query->queried_object->ID)) {
	                            $page_id = $wp_query->queried_object->ID;
	                        } else {
	                            if (!empty($post->ID)) {
	                                $page_id = $post->ID;
	                            }
	                        }
	                    }
	                }
	            }
	        }
	    }
	    return $page_id;
	}
	public function wpfCurrentLocation() {
		if (isset($_SERVER['HTTPS']) &&
			($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
			isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
			$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
		return $protocol . $_SERVER['HTTP_HOST'] . $uri_parts[0];
	}

	protected function getCatsByGetVar($getVars, $slugs = true){
        $cats = array();
        foreach ($getVars as $getVar => $items) {
            if (strpos($getVar, 'filter_cat') !== false) {
                $ids = explode('|', $items);
                if (sizeof($ids) <= 1) {
                    $ids = explode(',', $items);
                }
                if($slugs) {
                	$cats = array_merge($cats, array_map(function($id){
                    	return get_term_by('id', $id, 'product_cat', 'ARRAY_A')['slug'];
                	},$ids));
                } else {
                	$cats = array_merge($cats, $ids);
                }
            }
        }

        return $cats;
	}

	protected function existGetVarLike($getVars, $field){
        foreach ($getVars as $getVar => $items) {
            if (strpos($getVar, $field) !== false) return true;
        }
        return false;
	}

}
