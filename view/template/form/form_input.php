<div class="<?=$class;?>">
    <div class="form_label_input row">
        <? if(!empty($label)) {?>
            <div class="form_label"><label for="<?=$name_id;?>"><?=$label;?></label></div>
        <? } ?>
        <div class="form_input"><input data-value="<?=$value;?>" type="<?=$type;?>" value="<?=$value;?>" name="<?=$name_id;?>" id="<?=$name_id;?>" <?=($disabled ? "disabled" : "")?> <?=($required ? "required" : "")?> <?=(!empty($pattern) ? "pattern='".$pattern."'" : "")?> <?=(!empty($placeholder) ? "placeholder='".$placeholder."'" : "")?>  /></div>
    </div>
    <div class="form_error">
        <div class="form_error_text_<?=$name_id;?>"><?=$error;?></div>
    </div>
</div>