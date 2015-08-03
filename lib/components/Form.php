<?php
namespace lib;

require_once(DIR_HOME.SP."lib".SP."View.php");

class Form
{

    private $ITEMS;
    private $SETTINGS;

    public function __construct($_name, $settings)
    {
        $this->ITEMS = new \ArrayObject();
        $this->SETTINGS = array(
            "name" => !empty($_name) ? $_name : "form_1",
            "action" => !empty($settings['action']) ? $settings['action'] : "?1",
            "method" => !empty($settings['method']) ? $settings['method'] : "POST",
            "target" => !empty($settings['target']) ? $settings['target'] : "_self",
            "style" => !empty($settings['style']) ? $settings['style'] : "",
            "enctype" => !empty($settings['enctype']) ? $settings['enctype'] : "multipart/form-data"
        );
    }
    /*
        public function printForm()
        {
            $html = "<form name='". $this->SETTINGS['name'] ."' ".
                " action='". $this->SETTINGS['action'] ."' ".
                " method='". $this->SETTINGS['method'] ."' ".
                " target='". $this->SETTINGS['target'] ."'".
                " style='". $this->SETTINGS['style'] ."'".
                " enctype='". $this->SETTINGS['enctype'] ."'
                >";
            foreach ($this->ITEMS AS $item) {
                $html .= View::getTemplate("form/".$item->template, (array) $item);
            }
            $html .= "</form>";

            return $html;
        }*/
    public function printForm()
    {
        $html = "<form name='". $this->SETTINGS['name'] ."' ".
            " action='". $this->SETTINGS['action'] ."' ".
            " method='". $this->SETTINGS['method'] ."' ".
            " target='". $this->SETTINGS['target'] ."'".
            " style='". $this->SETTINGS['style'] ."'".
            " enctype='". $this->SETTINGS['enctype'] ."'
                >";
        foreach ($this->ITEMS AS $item)
        {
            if($item->template == "htmlBlock")
                $html .= $item->value;
            else
                $html .= View::getTemplate("form/".$item->template, (array) $item);
        }
        $html .= "</form>";

        return $html;
    }

    /**
     * @param $values array
     * (
    "name_id" => "",
    "type" => "text",
    "label" => "",
    "value" => "",
    "template" => "",
    "disabled" => false,
    "error" => "",
    "class" => "",
    "style" => "",
    "password" => false,
    "required" => false
     *
     * )
     */
    public function addInput($values)
    {
        $input = (object) array(
            "name_id" => !empty($values['name_id']) ? $values['name_id'] : "",
            "type" => !empty($values['type']) ? $values['type'] : "text",
            "label" => !empty($values['label']) ? $values['label'] : "",
            "value" => !empty($values['value']) ? $values['value'] : "",
            "template" => !empty($values['template']) ? $values['template'] : "",
            "disabled" => !empty($values['disabled']) ? !!$values['disabled'] : false,
            "error" => !empty($values['error']) ? $values['error'] : "",
            "class" => !empty($values['class']) ? $values['class'] : "",
            "style" => !empty($values['style']) ? $values['style'] : "",
            "password" => !empty($values['password']) ? !!$values['password'] : false,
            "pattern" => !empty($values['pattern']) ? $values['pattern'] : "",
            "placeholder" => !empty($values['placeholder']) ? $values['placeholder'] : "",
            "required" => !empty($values['required']) ? !!$values['required'] : false
        );
        $this->ITEMS->append($input);
    }

    public function addSelected($values)
    {
        $input = (object) array(
            "name_id" => !empty($values['name_id']) ? $values['name_id'] : "",
            "label" => !empty($values['label']) ? $values['label'] : "",
            "values" => !empty($values['values']) ? $values['values'] : array(),
            "selected" => !empty($values['selected']) ? $values['selected'] : "",
            "template" => !empty($values['template']) ? $values['template'] : "",
            "error" => !empty($values['error']) ? $values['error'] : "",
            "class" => !empty($values['class']) ? $values['class'] : "",
            "style" => !empty($values['style']) ? $values['style'] : "",
            "required" => !empty($values['required']) ? !!$values['required'] : false
        );
        $this->ITEMS->append($input);
    }


    /**
     * @param $values array
     * (
    "name_id" => "",
    "label" => "",
    "image" => "",
    "template" => "",
    "error" => "",
    "class" => "",
    "style" => ""
     * )
     */
    public function addPhotoInput($values)
    {
        $input = (object) array(
            "name_id" => !empty($values['name_id']) ? $values['name_id'] : "",
            "label" => !empty($values['label']) ? $values['label'] : "",
            "image" => !empty($values['image']) ? $values['image'] : "",
            "template" => !empty($values['template']) ? $values['template'] : "",
            "error" => !empty($values['error']) ? $values['error'] : "",
            "class" => !empty($values['class']) ? $values['class'] : "",
            "style" => !empty($values['style']) ? $values['style'] : ""
        );
        $this->ITEMS->append($input);
    }

    public function addTextarea($values)
    {
        $input = (object) array(
            "name_id" => !empty($values['name_id']) ? $values['name_id'] : "",
            "label" => !empty($values['label']) ? $values['label'] : "",
            "value" => !empty($values['value']) ? $values['value'] : "",
            "template" => !empty($values['template']) ? $values['template'] : "",
            "error" => !empty($values['error']) ? $values['error'] : "",
            "class" => !empty($values['class']) ? $values['class'] : "",
            "style" => !empty($values['style']) ? $values['style'] : "",
            "pattern" => !empty($values['pattern']) ? $values['pattern'] : "",
            "placeholder" => !empty($values['placeholder']) ? $values['placeholder'] : "",
            "required" => !empty($values['required']) ? !!$values['required'] : false
        );
        $this->ITEMS->append($input);
    }

    public function addButton($values)
    {
        $input = (object) array(
            "name_id" => !empty($values['name_id']) ? $values['name_id'] : "button_1",
            "text" => !empty($values['text']) ? $values['text'] : "Button",
            "type" => !empty($values['type']) ? $values['type'] : "button",
            "multiple" => !empty($values['multiple']) ? $values['multiple'] : "",
            "template" => !empty($values['template']) ? $values['template'] : "",
            "error" => !empty($values['error']) ? $values['error'] : "",
            "class" => !empty($values['class']) ? $values['class'] : "",
            "style" => !empty($values['style']) ? $values['style'] : ""
        );
        $this->ITEMS->append($input);
    }

    /**
     * @param $html - HTML code
     */
    public function addHtmlBLock($html)
    {
        $val = (object) array(
            "template" => "htmlBlock",
            "value" => $html
        );
        $this->ITEMS->append($val);

    }
}
