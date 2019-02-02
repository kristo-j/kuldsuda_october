<?php namespace Kuldsuda\Tolked;

use Backend;
use System\Classes\PluginBase;

/**
 * tolked Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'tolked',
            'description' => 'No description provided yet...',
            'author'      => 'Kuldsuda',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Kuldsuda\Tolked\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'kuldsuda.tolked.some_permission' => [
                'tab' => 'tolked',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'tolked' => [
                'label'       => 'tolked',
                'url'         => Backend::url('kuldsuda/tolked/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['kuldsuda.tolked.*'],
                'order'       => 500,
            ],
        ];
    }
}
