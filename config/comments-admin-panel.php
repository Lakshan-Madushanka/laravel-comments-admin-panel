<?php


use LakM\CommentsAdminPanel\Middleware\AdminPanelAccessMiddleware;

return [
        'enabled' => true,
        'routes' => [
            'middlewares' => [
                'web',
                AdminPanelAccessMiddleware::class, // Removing this, allow users to access admin panel if enabled is set to true !
            ],
            'prefix' => 'admin',
        ],
];
