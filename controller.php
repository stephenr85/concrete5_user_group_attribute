<?php       

defined('C5_EXECUTE') or die(_("Access Denied."));

class UserGroupAttributePackage extends Package {

	protected $pkgHandle = 'user_group_attribute';
	protected $appVersionRequired = '5.5.1';
	protected $pkgVersion = '0.9.0';
	
	public function getPackageDescription() {
		return t("Attribute that allows the selection of one or more user groups.");
	}
	
	public function getPackageName() {
		return t("User Group Attribute");
	}	
	
	public function install() {		
		$pkg = parent::install();
		$this->configurePackage($pkg);
	}
	
	public function upgrade() {
        $pkg = $this;
        parent::upgrade();
        $this->configurePackage($pkg);
    }
	
	public function configurePackage($pkg){
		$helper = Loader::helper('user_group_attribute/package', 'user_group_attribute');
		$helper->addAttributeType('user_group', t('User Group'), $pkg);
	}
}