<div id="<?=$name_id;?>" class="row">
    <div class="form-label-img <?=$name_id;?>"></div>
    <div class="form_textarea">
        <textarea data-value="<?=$value;?>" name="<?=$name_id;?>" <?=($required ? "required" : "")?> <?=(!empty($pattern) ? "pattern='".$pattern."'" : "")?> <?=(!empty($placeholder) ? "placeholder='".$placeholder."'" : "")?>><?=$value;?></textarea>
    </div>
</div>