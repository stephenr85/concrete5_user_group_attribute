<?php defined('C5_EXECUTE') or die;

class UserGroupAttributePackageHelper {
	
	
	public function addAttributeType($handle, $name, $pkg){
		$attributeType = AttributeType::getByHandle($handle);
		if(!is_object($attributeType)) {
			$attributeType = AttributeType::add($handle, $name, $pkg);
		}else{
			$path = $attributeType->getAttributeTypeFilePath(FILENAME_ATTRIBUTE_DB);
			if ($path) {
				Package::installDB($path);
			}
		}
		return $attributeType;
	}

}