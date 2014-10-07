<?php 
$opts = array(''=>t('Select...')) + $groupOptions;
echo $form->select($this->controller->field('gID'), $opts);