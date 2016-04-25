<?php
$FORM = new \lib\Form("Lang", array("action" => "/lang/save"));

if(!empty($_GET['page']))
{
    if($_GET['page'] == "save")
    {
        if(!empty($_POST['id_value']))
        {
            if($_POST['id_value'] == 'new') {
                foreach($LANG->getLanguages() AS $language)
                {
                    if(!empty($_POST["textarea_".$language]))
                    {
                        $LANG->addValue($_POST['new_id'], $_POST["textarea_".$language], $language);
                    }
                }
                header("Location: /lang?lang=". $_POST['new_id']);
            } else {
                foreach($LANG->getLanguages() AS $language)
                {
                    if(!empty($_POST["textarea_".$language]))
                    {
                        $LANG->setValue($_POST['id_value'], $_POST["textarea_".$language], $language);
                    }
                }
                header("Location: /lang?lang=". $_POST['id_value']);
            }
        }
    } else if($_GET['page'] == "delete") {
        if(!empty($_POST['id_value_delete']))
        {
            $LANG->deleteValue($_POST['id_value_delete']);
            header("Location: /lang");
        }
    }

}

if(!empty($_GET['lang']))
{
    if($_GET['lang'] == 'new')
    {
        $FORM->addInput(
            array(
                "name_id" => "new_id",
                "value" => "",
                "label" => "Var name",
                "template" => "form_input"
            )
        );
    } else {
        $FORM->addInput(
            array(
                "name_id" => "lang_name",
                "value" => $_GET['lang'],
                "label" => "Var name",
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
                "value" => $LANG->getValue($_GET['lang'], $language),
                "class" => "editor",
                "template" => "form_textarea"
            )
        );
    }

    $FORM->addInput(
        array(
            "name_id" => "id_value",
            "value" => $_GET['lang'],
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

    if($_GET['lang'] !== 'new')
    {
        echo '<div>';
        $FORM_DEL = new \lib\Form("Lang_del",
            array(
                "action" => "/lang/delete",
                "style" => "float: right; position: relative; top: 10px;"
            )
        );
        $FORM_DEL->addInput(
            array(
                "name_id" => "id_value_delete",
                "value" => $_GET['lang'],
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