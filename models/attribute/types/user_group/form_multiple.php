

<?php foreach($groupOptions as $gID=>$gName){ ?>
<label><?php echo $form->checkbox($this->controller->field('gID').'[]', $gID, is_array($gIDs) ? in_array($gID, $gIDs) : false); ?> <?php echo $gName ?></label>
<?php } ?>