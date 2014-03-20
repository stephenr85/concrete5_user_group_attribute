<?php 
$opts = array(''=>'Select...') + $groupOptions;
echo $form->select($this->controller->field('gID'), $opts, is_array($gIDs) ? reset($gIDs) : $gIDs);