<?php
$ACTIVE_PAGE = !empty($_GET['p']) ? $_GET['p'] : null;
$ACTIVE_SUB_PAGE = !empty($_GET['sp']) ? $_GET['sp'] : null;
function getActiveName1()
{
    global $ACTIVE_PAGE, $ACTIVE_SUB_PAGE;
    if($ACTIVE_SUB_PAGE != null)
        return strtoupper($ACTIVE_PAGE ." ". $ACTIVE_SUB_PAGE);
    else if($ACTIVE_PAGE != null)
        return strtoupper($ACTIVE_PAGE);
    else
        return null;
}


$FORM = new \lib\Form("Lang", array("action" => "/languages/save"));
if(!empty($_GET['val']))
{
    $NAME = $_GET['val'];
    if($NAME == 'new')
    {
        $FORM->addInput(
            array(
                "name_id" => "new_id",
                "value" => getActiveName1()." ",
                "label" => "Var name: ",
                "template" => "form_input"
            )
        );
    } 
    else 
    {
        $FORM->addInput(
            array(
                "name_id" => "lang_name",
                "value" => $NAME,
                "label" => "Var name: ",
                "template" => "form_input"
            )
        );
    }


    foreach($LANG->getLanguages() AS $language)
    {
        $FORM->addTextarea(
            array(
                "name_id" => "textarea_".$language,
                "label" => "Language [".$language."]",
                "value" => $LANG->getValue($NAME, $language),
                "class" => "editor",
                "template" => "form_textarea"
            )
        );
    }

    $FORM->addInput(
        array(
            "name_id" => "id_value",
            "value" => $NAME,
            "template" => "form_input_hidden"
        )
    );

    $FORM->addButton(
        array(
            "name_id" => "button_save",
            "text" => "Save",
            "type" => "submit",
            "template" => "form_button"
        )
    );
    echo '<div>';
    echo $FORM->printForm();
    echo '</div>';

    if($_GET['val'] !== 'new')
    {
        echo '<div>';
        $FORM_DEL = new \lib\Form("Lang_del", array( "action" => "/languages/delete" )
        );
        $FORM_DEL->addInput(
            array(
                "name_id" => "id_value_delete",
                "value" => $NAME,
                "template" => "form_input_hidden"
            )
        );
        $FORM_DEL->addButton(
            array(
                "name_id" => "button_delete",
                "text" => "Delete",
                "type" => "submit",
                "template" => "form_button"
            )
        );
        echo $FORM_DEL->printForm();
        echo '</div>';
    }
}