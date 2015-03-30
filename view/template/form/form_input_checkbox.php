<div class="form_row">
    <div class="form_checkbox">
        <label><?=$label;?>:</label>
        <input type="<?=$type;?>" name="<?=$name_id;?>" <?=($disabled ? "disabled" : "")?> <?= (int)$value == 1 ? "checked" : "";?> />
    </div>
    <div class="form_error">
        <div class="form_error_text <?=$name_id;?>"><?=$error;?></div>
    </div>
</div>