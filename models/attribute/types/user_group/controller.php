<?php  
defined('C5_EXECUTE') or die("Access Denied.");

class UserGroupAttributeTypeController extends AttributeTypeController  {
	
	private $akSelectAllowMultipleValues;
	
	protected $searchIndexFieldDefinition = 'I 11 DEFAULT 0 NULL';

	protected function load() {
		$ak = $this->getAttributeKey();
		if (!is_object($ak)) {
			return false;
		}
		
		$db = Loader::db();
		$row = $db->GetRow('select akUserGroupAllowMultipleValues from atUserGroupSettings where akID = ?', $ak->getAttributeKeyID());
		$this->akUserGroupAllowMultipleValues = $row['akUserGroupAllowMultipleValues'];
		$this->set('akUserGroupAllowMultipleValues', $this->akUserGroupAllowMultipleValues);		
	}
	
	public function getValue() {
		$db = Loader::db();
		$avID = $this->getAttributeValueID();
		$gIDs = $db->GetCol("select gID from atUserGroupSelected where avID = ?", array($avID));
		if(!$this->getAllowMultipleValues()){
			return Group::getByID(reset($gIDs));
		}else{
			$groups = array();
			foreach($values as $gID){
				$group = Group::getByID($gID);
				if(!is_object($group) || $group->isError()){
					//auto prune
					$db->Execute('delete from atUserGroupSelected where gID = ?', array($gID));	
				}else{
					$groups[] = $group;	
				}
			}
		}
		return $groups;	
	}
	
	public function searchForm($list) {
		$userID = $this->request('value');
		$list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $userID, '=');
		return $list;
	}

	
	public function type_form() {
		$this->set('form', Loader::helper('form'));		
		$this->load();		
	}
	
	
	public function search() {
		$helper = Loader::helper('user_group_attribute', 'user_group_attribute');
		$this->set('groupOptions', $helper->getGroupsInputOptions());
		
	}
	
	public function form() {
		$helper = Loader::helper('user_group_attribute', 'user_group_attribute');
		$db = Loader::db();
		
		$this->load();
		$avID = $this->getAttributeValueID();
		if ($avID) {
			$gIDs = $db->GetCol('select gID from atUserGroupSelected where avID = ?', array($avID));
		}
		$this->set('groupOptions', $helper->getGroupsInputOptions());
		$this->set('gIDs', $gIDs);
		$this->set('form', Loader::helper('form'));
	}
	
	public function validateForm($post) {
		$error = Loader::helper('validation/error');
		
		foreach($post['gID'] as $gID){
			$group = Group::getByID($gID);	
			if(!is_object($group) || $group->isError()){
				$error->add(t('Group %s does not exist.', $gID));	
			}
		}
		return $error;
	}

	public function saveValue($data) {
		$db = Loader::db();
		$avID = $this->getAttributeValueID();
		
		if(isset($data['gID'])){
			$db->Execute('delete from atUserGroupSelected where avID = ?', array($avID));
			if(is_array($data['gID'])){
				foreach($data['gID'] as $gID){
					$this->saveSelectedGroup($gID);
				}
			}else{
				$this->saveSelectedGroup($data['gID']);
			}
		}
	}
	
	public function saveSelectedGroup($gID){
		$db = Loader::db();
		return $db->Execute('insert into atUserGroupSelected (avID, gID) values (?, ?)', array($this->getAttributeValueID(), $gID));
	}
	
	public function saveForm($data) {
		$db = Loader::db();
		$this->saveValue($data);
	}
	
	public function deleteValue() {
		$db = Loader::db();
		$db->Execute('delete from atUserGroup where avID = ?', array($this->getAttributeValueID()));
		$db->Execute('delete from atUserGroupSelected where avID = ?', array($this->getAttributeValueID()));
	}
	
	
	public function getAllowMultipleValues() {
		if (is_null($this->akUserGroupAllowMultipleValues)) {
			$this->load();
		}
		return $this->akUserGroupAllowMultipleValues;
	}
	
	
	public function saveKey($data){
		$ak = $this->getAttributeKey();
		
		$db = Loader::db();
		
		$akUserGroupAllowMultipleValues = $data['akUserGroupAllowMultipleValues'];
		
		if ($data['akUserGroupAllowMultipleValues'] != 1) {
			$akUserGroupAllowMultipleValues = 0;
		}
				
		// now we have a collection attribute key object above.
		$db->Replace('atUserGroupSettings', array(
			'akID' => $ak->getAttributeKeyID(), 
			'akUserGroupAllowMultipleValues' => $akUserGroupAllowMultipleValues
		), array('akID'), true);
	}
	
	public function deleteKey() {
		$db = Loader::db();		
		
		$ak = $this->getAttributeKey();
		$db->Execute('delete from atUserGroupSettings where akID = ?', array($ak->getAttributeKeyID()));
		
		$avIDs = $this->attributeKey->getAttributeValueIDList();
		foreach($avIDs as $avID) {
			$db->Execute('delete from atUserGroup where avID = ?', array($avID));
			$db->Execute('delete from atUserGroupSelected where avID = ?', array($avID));
		}
	}
	
	
}