<?php defined('C5_EXECUTE') or die('Access denied.');

class UserGroupAttributeHelper {
	
	public function getGroupsInputOptions(){
		Loader::model('search/group');
		$groupSearch = new GroupSearch;
		$allGroups = $groupSearch->get();
		$options = array();
		foreach($allGroups as $group){
			$options[$group['gID']] = $group['gName'];	
		}
		return $options;	
	}
	
}