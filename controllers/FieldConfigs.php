<?php namespace Wpjscc\PopupForm\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Field Configs Backend Controller
 */
class FieldConfigs extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var array Permissions required to view this page.
     */
    protected $requiredPermissions = [
        'wpjscc.popupform.fieldconfigs.manage_all',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Wpjscc.PopupForm', 'popupform', 'fieldconfigs');
    }

    public function formExtendFields($form)
    {
        $form->addTabFields($this->generatePermissionsField());

    }

        /**
     * Adds the permissions editor widget to the form.
     * @return array
     */
    protected function generatePermissionsField()
    {
        return [
            'permissions' => [
                'tab' => 'backend::lang.user.permissions',
                'type' => 'Backend\FormWidgets\PermissionEditor',
            ]
        ];
    }
}
