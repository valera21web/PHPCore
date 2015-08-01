<?php
$Form = new \lib\Form("contact", array());

$Form->addHtmlBLock("<div id='form_error_header'><div class='form_error_header_text'></div></div>");

$Form->addInput(
    array(
        "name_id" => "input_name",
        "type" => "text",
        "value" => __("Var Kontakt name"),
        "template" => "form_input_contact",
        "required" => true,
        "pattern" => "[A-Z]{1}[a-z-]{1,}\ [A-Z]{1}[a-z-]{1,}",
        "placeholder" => "Tomasz Tomaszewski"
    )
);

$Form->addInput(
    array(
        "name_id" => "input_email",
        "type" => "email",
        "value" => __("Var Kontakt email"),
        "template" => "form_input_contact",
        "required" => true,
        "pattern" => "[0-9A-Za-z\-\_]{3,}\@[0-9A-Za-z\-\_]{1,}\.[A-Za-z]{2,3}",
        "placeholder" => "name@mail.com"
    )
);

$Form->addInput(
    array(
        "name_id" => "input_phone",
        "type" => "phone",
        "value" => __("Var Kontakt phone"),
        "template" => "form_input_contact",
        "required" => true,
        "pattern" => "((\+|00)(\ |\-|)[0-9]{2}|[0-9]{2}|)(\ |\-|)[0-9]{3}(\ |\-|)[0-9]{3}(\ |\-|)[0-9]{3}",
        "placeholder" => "+48 000 000 000"
    )
);

$Form->addTextarea(
    array(
        "name_id" => "input_message",
        "value" => __("Var Kontakt message"),
        "template" => "form_textarea_contact",
        "class" => "description_texterea",
        "required" => true,
        "placeholder" => __("Var Kontakt message")
    )
);

$Form->addButton(
    array(
        "name_id" => "buttonSend",
        "text" => __("Var Wyslij"),
        "type" => "button",
        "template" => "form_button"
    )
);

echo $Form->printForm();