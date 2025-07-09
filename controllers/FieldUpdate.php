<?php namespace Wpjscc\PopupForm\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Wpjscc\PopupForm\Models\FieldConfig;
use Yaml;

/**
 * Field Update Backend Controller
 */
class FieldUpdate extends Controller
{
    use \Wpjscc\PopupForm\Traits\PopupFormTrait;
    // use \Shadow\User\Traits\TraitController;

    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\RelationController::class,
        \Backend\Behaviors\ListController::class,
        \Wpjscc\PopupForm\Behaviors\PopupFormController::class,
    ];

    /**
     * @var array Permissions required to view this page.
     */
    protected $requiredPermissions = [
        'wpjscc.popupform.fieldupdate.manage_all',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig;
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        $mode = post('mode');

        if (!$mode) {
            $mode = request()->input('mode');
        }

        if (!$mode) {
            throw new \ApplicationException('mode is required');
        }

        if (!is_string($mode)) {
            throw new \ApplicationException('mode must be a string');
        }

        $fieldConfig = FieldConfig::where('mode', $mode)->first();

        if (!$fieldConfig) {
            throw new \ApplicationException('field config not found');
        }

        $this->formConfig = Yaml::parse($fieldConfig->config_form);
        $relationConfig = Yaml::parse($fieldConfig->config_relation) ?: [];
        if ($relationConfig) {
            $this->relationConfig = $relationConfig;
        }
        $listConfig = Yaml::parse($fieldConfig->config_list) ?: [];
        if ($listConfig) {
            $this->listConfig = [
                'list' => $listConfig
            ];
        }

        $permissions = $fieldConfig->permissions ?: [];

        $this->vars['mode'] = $mode;
        parent::__construct();

        $permissons = array_filter($permissions, function($permission) {
            return $permission;
        });
        $permissions = array_keys($permissons);

        if ($permissions) {
            if (!$this->user->hasAnyAccess($permissions)) {
                throw new \ApplicationException('you do not have permission to access this page');
            }
        }

       

        BackendMenu::setContext('Wpjscc.PopupForm', 'popupform', 'fieldupdate');
    }

    public function index_onDelete()
    {
        throw new \ApplicationException('403');
    }

    public function update_onDelete()
    {
        throw new \ApplicationException('403');
    }

}
