<?php

namespace Wpjscc\PopupForm;

use Backend;
use Backend\Models\UserRole;
use System\Classes\PluginBase;

/**
 * PopupForm Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'wpjscc.popupform::lang.plugin.name',
            'description' => 'wpjscc.popupform::lang.plugin.description',
            'author'      => 'Wpjscc',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {

    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot(): void
    {

    }

    /**
     * Registers any frontend components implemented in this plugin.
     */
    public function registerComponents(): array
    {
        return []; // Remove this line to activate

        return [
            \Wpjscc\PopupForm\Components\MyComponent::class => 'myComponent',
        ];
    }

    /**
     * Registers any backend permissions used by this plugin.
     */
    public function registerPermissions(): array
    {
        return []; // Remove this line to activate

        return [
            'wpjscc.popupform.some_permission' => [
                'tab' => 'wpjscc.popupform::lang.plugin.name',
                'label' => 'wpjscc.popupform::lang.permissions.some_permission',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
        ];
    }

    /**
     * Registers backend navigation items for this plugin.
     */
    public function registerNavigation(): array
    {
        return []; // Remove this line to activate

        return [
            'popupform' => [
                'label'       => 'wpjscc.popupform::lang.plugin.name',
                'url'         => Backend::url('wpjscc/popupform/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['wpjscc.popupform.*'],
                'order'       => 500,
            ],
        ];
    }
}
