<?php defined('C5_EXECUTE') or die(_("Access Denied."));

$htmlId = uniqid('ccm-user-group-attr');

?>
<div id="<?php echo $htmlId ?>">    
<?php
if($akUserGroupAllowMultipleValues){
	require('form_multiple.php');
}else{
	require('form_single.php');	
}
?>
</div>

		