<div class="<?=$class;?>">
    <div class="form_label_input">
        <? if(!empty($label)) {?>
            <div class="form_label"><label for="<?=$name_id;?>"><?=$label;?></label></div>
        <? } ?>
        <div class="form_input">
            <textarea id="<?=$name_id;?>" data-value="<?=$value;?>" name="<?=$name_id;?>" <?=($required ? "required" : "")?> <?=(!empty($pattern) ? "pattern='".$pattern."'" : "")?> <?=(!empty($placeholder) ? "placeholder='".$placeholder."'" : "")?>><?=$value;?></textarea>
        </div>
    </div>
    <div class="form_error">
        <div class="form_error_text_<?=$name_id;?>"><?=$error;?></div>
    </div>
</div>