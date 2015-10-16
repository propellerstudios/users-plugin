<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * Based on the configuration file for this plugin, it may append to the root
 * route scope ('/') or use its plugin notation ('/propeller/users')
 */

if (Configure::read('Users.useMainRouteScope')) {
    Router::scope('/', function ($routes) {
        $routes->connect('/users', [
            'plugin' => 'Propeller/Users',
            'controller' => 'Users',
            'action' => 'index'
        ]);
        
        $routes->connect('/admin', [
            'plugin' => 'Propeller/Users',
            'controller' => 'Admins',
            'action' => 'index'
        ]);
        
        if (Configure::read('Users.useDashboardRoute')) {
            $routes->connect('/users/dashboard', [
               'plugin' => 'Propeller/Users',
               'controller' => 'Users',
               'action' => 'index'
            ]);
            
            $routes->connect('/admin/dashboard', [
                'plugin' => 'Propeller/Users',
                'controller' => 'Admins',
                'action' => 'index'
            ]);
        }
        
        $routes->connect('/users/:action/*', [
            'plugin' => 'Propeller/Users',
            'controller' => 'Users'
        ]);
        
        $routes->connect('/admin/:action/*', [
            'plugin' => 'Propeller/Users',
            'controller' => 'Admins'
        ]);
    });
} else {
    Router::plugin('Propeller/Users', function ($routes) {
        $routes->connect('/:action', ['controller' => 'Users']);
        $routes->connect('/admin/:action', ['controller' => 'Admins']);
        
        $routes->fallbacks('DashedRoute');
    });
}

