<?php

use LakM\Comments\AdminPanel\Middleware\AdminPanelAccessMiddleware;

return [
        'enabled' => true,
        'routes' => [
            'middlewares' => [
                'web',
                AdminPanelAccessMiddleware::class,
            ],
            'prefix' => 'admin',
        ],
];
