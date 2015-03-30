<div class="<?=$name_id.' ';?>form_row">
    <? if(!empty($label)) {?>
        <div class="form_label">
            <label for="<?=$name_id;?>"><?=$label;?>: </label>
        </div>
    <? } ?>
    <div class="form_textarea">
        <textarea id="<?=$name_id;?>" name="<?=$name_id;?>"><?=$value;?></textarea>
    </div>
    <div class="form_error">
        <div class="form_error_text <?=$name_id;?>"><?=$error;?></div>
    </div>
</div>