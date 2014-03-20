
<fieldset>
<legend><?php echo t('Select Options')?></legend>

<div class="clearfix">
<label><?php echo t("Multiple Values")?></label>
<div class="input">
<ul class="inputs-list">
<li><label><?php echo $form->checkbox('akUserGroupAllowMultipleValues', 1, $akUserGroupAllowMultipleValues)?> <span><?php echo t('Allow multiple options to be chosen.')?></span></label></li>
</ul>
</div>
</div>
</fieldset>