<div class="form_row">
    <div class="form_selected">
<? if(!empty($label)) {?>
            <label><?=$label;?>:</label>
<? } ?>
        <select name="<?=$name_id;?>">
<?
            if(!empty($values) && is_array($values))
            {
                $_selected = !empty($selected);
                foreach($values AS $row)
                {
                    echo '<option '.($_selected && $selected == $row['id'] ? "selected" : ""   ).' value="'.$row['id'].'">'.$row['value'].'</option>';
                }
            }
?>
        </select>
    </div>
    <div class="form_error">
        <div class="form_error_text <?=$name_id;?>"><?=$error;?></div>
    </div>
</div>

