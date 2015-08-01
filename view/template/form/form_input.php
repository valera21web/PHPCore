<div class="<?=$css_class.' ';?>form_row">
    <div class="form_label_input  row">
        <? if(!empty($label)) {?>
            <div class="form_label"><label><?=$label;?>: </label></div>
        <? } ?>
        <div class="form_input"><input id="<?=$name_id;?>" type="<?=$type;?>" value="<?=$value;?>" name="<?=$name_id;?>" <?=($disabled ? "disabled" : "")?> /></div>
    </div>
    <div class="form_error">
        <div class="form_error_text <?=$name_id;?>"><?=$error;?></div>
    </div>
</div>
