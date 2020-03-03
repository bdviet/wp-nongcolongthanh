<?php
class optionsWpf extends moduleWpf {
	private $_tabs = array();
	private $_options = array();
	private $_optionsToCategoires = array();	// For faster search

	public function init() {
//		dispatcherWpf::addAction('afterModulesInit', array($this, 'initAllOptValues'));
		add_action('init', array($this, 'initAllOptValues'), 99);	// It should be init after all languages was inited (frame::connectLang)
		dispatcherWpf::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
	}
	public function initAllOptValues() {
		// Just to make sure - that we loaded all default options values
		$this->getAll();
	}
    /**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
    public function get($code) {
        return $this->getModel()->get($code);
    }
	/**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
	public function isEmpty($code) {
		return $this->getModel()->isEmpty($code);
	}
	public function getAllowedPublicOptions() {
		$allowKeys = array('add_love_link', 'disable_autosave');
		$res = array();
		foreach($allowKeys as $k) {
			$res[ $k ] = $this->get($k);
		}
		return $res;
	}
	public function getAdminPage() {
		if(!installerWpf::isUsed()) {
			installerWpf::setUsed();	// Show this welcome page - only one time
			frameWpf::_()->getModule('promo')->getModel()->bigStatAdd('Welcome Show');
			frameWpf::_()->getModule('options')->getModel()->save('plug_welcome_show', time());	// Remember this
		} /*else {
		 * // No actually welcome page for now
			return frameWpf::_()->getModule('promo')->showWelcomePage();
		}*/
		return $this->getView()->getAdminPage();
	}
	public function addAdminTab($tabs) {
		$tabs['settings'] = array(
			'label' => __('Settings', WPF_LANG_CODE), 'callback' => array($this, 'getSettingsTabContent'), 'fa_icon' => 'fa-gear', 'sort_order' => 30,
		);
		return $tabs;
	}
	public function getSettingsTabContent() {
		return $this->getView()->getSettingsTabContent();
	}
	public function getTabs() {
		if(empty($this->_tabs)) {
			$this->_tabs = dispatcherWpf::applyFilters('mainAdminTabs', array(
				//'main_page' => array('label' => __('Main Page', WPF_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'wp_icon' => 'dashicons-admin-home', 'sort_order' => 0),
			));
			foreach($this->_tabs as $tabKey => $tab) {
				if(!isset($this->_tabs[ $tabKey ]['url'])) {
					$this->_tabs[ $tabKey ]['url'] = $this->getTabUrl( $tabKey );
				}
			}
			uasort($this->_tabs, array($this, 'sortTabsClb'));
		}
		return $this->_tabs;
	}
	public function sortTabsClb($a, $b) {
		if(isset($a['sort_order']) && isset($b['sort_order'])) {
			if($a['sort_order'] > $b['sort_order'])
				return 1;
			if($a['sort_order'] < $b['sort_order'])
				return -1;
		}
		return 0;
	}
	public function getTab($tabKey) {
		$this->getTabs();
		return isset($this->_tabs[ $tabKey ]) ? $this->_tabs[ $tabKey ] : false;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getActiveTab() {
		$reqTab = sanitize_text_field(reqWpf::getVar('tab'));
		return empty($reqTab) ? 'woofilters' : $reqTab;
	}
	public function getTabUrl($tab = '') {
		static $mainUrl;
		if(empty($mainUrl)) {
			$mainUrl = frameWpf::_()->getModule('adminmenu')->getMainLink();
		}
		return empty($tab) ? $mainUrl : $mainUrl. '&tab='. $tab;
	}
	public function getRolesList() {
		if(!function_exists('get_editable_roles')) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );
		}
		return get_editable_roles();
	}
	public function getAvailableUserRolesSelect() {
		$rolesList = $this->getRolesList();
		$rolesListForSelect = array();
		foreach($rolesList as $rKey => $rData) {
			$rolesListForSelect[ $rKey ] = $rData['name'];
		}
		return $rolesListForSelect;
	}
	public function getAll() {
		if(empty($this->_options)) {
			$defSendmailPath = @ini_get('sendmail_path');
			 if (empty($defSendmailPath) && !stristr($defSendmailPath, 'sendmail')) {
				$defSendmailPath = '/usr/sbin/sendmail';
			}
			$this->_options = dispatcherWpf::applyFilters('optionsDefine', array(
				'general' => array(
					'label' => __('General', WPF_LANG_CODE),
					'opts' => array(
						'send_stats' => array('label' => __('Send usage statistics', WPF_LANG_CODE), 'desc' => __('Send information about what plugin options you prefer to use, this will help us make our solution better for You.', WPF_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
						'count_product_shop' => array('label' => __('Set number of displayed products', WPF_LANG_CODE), 'desc' => __('Set number of displayed products. Leave blank for the default value.', WPF_LANG_CODE), 'def' => '', 'html' => 'input'),
						'move_sidebar' => array('label' => __('Move Sidebar To Top For Mobile', WPF_LANG_CODE), 'desc' => __('Turn on if you want the sidebar to appear above content on mobile devices.', WPF_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
					),
				),
			));
			$isPro = frameWpf::_()->getModule('promo')->isPro();
			foreach($this->_options as $catKey => $cData) {
				foreach($cData['opts'] as $optKey => $opt) {
					$this->_optionsToCategoires[ $optKey ] = $catKey;
					if(isset($opt['pro']) && !$isPro) {
						$this->_options[ $catKey ]['opts'][ $optKey ]['pro'] = frameWpf::_()->getModule('promo')->generateMainLink('utm_source=plugin&utm_medium='. $optKey. '&utm_campaign=popup');
					}
				}
			}
			$this->getModel()->fillInValues( $this->_options );
		}
		return $this->_options;
	}
	public function getFullCat($cat) {
		$this->getAll();
		return isset($this->_options[ $cat ]) ? $this->_options[ $cat ] : false;
	}
	public function getCatOpts($cat) {
		$opts = $this->getFullCat($cat);
		return $opts ? $opts['opts'] : false;
	}
}
