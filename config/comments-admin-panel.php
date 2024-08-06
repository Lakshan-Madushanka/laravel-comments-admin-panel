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

    /**
     * Commentable models(must implement CommentableContract) that the admin panel must track
     * Keep empty to use models auto-discovery
     */
    'commentable_models' => [],
];
