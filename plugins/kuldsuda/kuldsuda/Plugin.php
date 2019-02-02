<?php namespace Kuldsuda\Kuldsuda;

use Backend;
use System\Classes\PluginBase;

/**
 * Kuldsuda Plugin Information File
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
            'name'        => 'Kuldsuda',
            'description' => 'Kuldsuda tunnustatud kasutajad',
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
            'Kuldsuda\Kuldsuda\Components\MyComponent' => 'myComponent',
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
            'kuldsuda.kuldsuda.some_permission' => [
                'tab' => 'Kuldsuda',
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
        return [
            'kuldsuda' => [
                'label'       => 'Kuldsuda',
                'url'         => Backend::url('kuldsuda/kuldsuda/acknowledgeduser'),
                'icon'        => 'icon-leaf',
                'permissions' => ['kuldsuda.kuldsuda.*'],
                'order'       => 500,
            ],
        ];
    }
}
