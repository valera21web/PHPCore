<div id="<?=$name_id;?>"  class="form_label_input row">
    <div class="form-label-img <?=$name_id;?>"></div>
    <div class="form_input">
        <input data-value="<?=$value;?>" type="<?=$type;?>" value="<?=$value;?>" name="<?=$name_id;?>" <?=($disabled ? "disabled" : "")?> <?=($required ? "required" : "")?> <?=(!empty($pattern) ? "pattern='".$pattern."'" : "")?> <?=(!empty($placeholder) ? "placeholder='".$placeholder."'" : "")?>  /></div>
</div>
