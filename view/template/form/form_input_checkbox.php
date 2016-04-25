<div class="form_row">
    <div class="form_checkbox row">
        <input type="checkbox" name="<?=$name_id;?>" id="<?=$name_id;?>" <?=($disabled ? "disabled" : "")?> <?= !!$value ? "checked" : "";?> />
        <label for="<?=$name_id;?>"><span></span><?=$label;?></label>
    </div>
    <div class="form_error">
        <div class="form_error_text <?=$name_id;?>"><?=$error;?></div>
    </div>
</div>