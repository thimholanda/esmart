<?php
use Cake\Routing\Router;

Router::plugin('AuditLog', function ($routes) {
    $routes->prefix('admin', function ($routes) {
        $routes->fallbacks('DashedRoute');
    });
    $routes->fallbacks('DashedRoute');
});
