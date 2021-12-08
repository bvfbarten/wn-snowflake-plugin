<?php namespace Skripteria\Snowflake\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Skripteria\Snowflake\Widgets\Dropdown;
use Skripteria\Snowflake\Models\Layout;
use Skripteria\Snowflake\Models\Settings;

/**
 * Elements Back-end Controller
 */
class ElementsLayouts extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */
    public $listConfig = 'config_list.yaml';

    protected $dropdownWidget;

    public function __construct()
    {

        parent::__construct();

        BackendMenu::setContext('Skripteria.Snowflake', 'snowflake', 'elements');

        $this->dropdownWidget = new Dropdown($this);
        $this->dropdownWidget->alias = 'layouts';
        $this->dropdownWidget->setListItems(Layout::lists( 'filename', 'id'));
        $this->dropdownWidget->bindToController();

    }

    public function listExtendQuery($query)
    {
        $query->withLayout($this->dropdownWidget->getActiveIndex());
    }

    public function formExtendFieldsBefore($form)
    {
        $md_mode = 'tab';
        if (Settings::get('markdown_mode')) $md_mode = 'split';

        switch($form->model->attributes["type_id"]) {
            case 1:
                $form->fields = $form->fields + ['content' => ['type' => 'text', 'label' => 'Content', 'span' => 'full']];
            break;
            case 2:
                $form->fields = $form->fields + ['content' => ['type' => 'text', 'label' => 'Link', 'span' => 'full']];
            break;
            case 3:
                $form->fields = $form->fields + ['image' => ['type' => 'fileupload', 'label' => 'image','mode' => 'image', 'span' => 'left']];
                $form->fields = $form->fields + ['alt' => ['type' => 'text', 'label' => 'Alt Attribute', 'span' => 'left']];
            break;
            case 4:
                $form->fields = $form->fields + ['content' => ['type' => 'colorpicker', 'span' => 'left', 'label' => 'Color']];
            break;
            case 5:
                $form->fields = $form->fields + ['content' => ['type' => 'markdown', 'mode'=> $md_mode, 'size' => 'huge']];
            break;
            case 6:
                $form->fields = $form->fields + ['content' => ['type' => 'richeditor', 'size' => 'huge']];
            break;
            case 7:
                $form->fields = $form->fields + ['content' => ['type' => 'codeeditor', 'size' => 'huge']];
            break;
            case 8:
                $form->fields = $form->fields + ['content' => ['type' => 'datepicker', 'mode' => 'date', 'span' => 'left']];
            break;
            case 9:
                $form->fields = $form->fields + ['content' => ['type' => 'textarea', 'label' => 'Content', 'size' => 'huge']];
            break;
            case 10:
                $form->fields = $form->fields + ['file' => ['type' => 'fileupload', 'label' => 'file', 'mode'=>'file', 'span' => 'left']];
                $form->fields = $form->fields + ['filename' => ['type' => 'text', 'label' => 'Filename', 'span' => 'left']];
            break;
        }
    }

}
