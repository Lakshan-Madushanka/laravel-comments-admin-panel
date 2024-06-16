<div align="center">

# Commenter Admin Panel

***Admin panel for the [commenter](https://github.com/Lakshan-Madushanka/laravel-comments)***

[Description](#description) |
[Requirements](#requirements) |
[Installation](#installation) |
[Usage](#usage) |
[Config](#config) |
[Customization](#customization) |
[Changelog](#changelog) |
[Security](#security) |
[License](#license) |

[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/Lakshan-Madushanka/laravel-comments-admin-panel/tests.yml)](https://github.com/Lakshan-Madushanka/laravel-comments-admin-panel/actions?query=workflow%3ATests+branch%3Amain)
[![Packagist Version](https://img.shields.io/packagist/v/lakm/laravel-comments-admin-panel)](https://packagist.org/packages/lakm/laravel-comments-admin-panel)
[![GitHub License](https://img.shields.io/github/license/Lakshan-Madushanka/laravel-comments-admin-panel)](https://github.com/Lakshan-Madushanka/laravel-comments-admin-panel/blob/main/LICENSE.md)

</div>

# Description

Provides an ideal admin panel to manage the [Commenter package](https://github.com/Lakshan-Madushanka/laravel-comments).

<img src="https://github.com/Lakshan-Madushanka/laravel-comments-admin-panel/assets/47297673/49bc9302-abe1-488e-a035-1c8571dfeae7" width="800">

# Requirements

- **PHP** : ^8.1 | ^8.2 | ^8.3
- **laravel/framework** : ^ 10.0 | ^11.0
- **lakm/laravel-comments** : *
- **filament/notifications** : ^3.2
- **filament/tables** : ^3.2

# Installation

```bash
    composer require lakm/laravel-comments-admin-panel -W
```

```bash
    composer commenter-admin-panel:install
```

**Optional**

You can publish views using below command.

```bash
    php artisan vendor:publish --tag=comments-admin-panel-resources
```

# Usage

All you need to do is navigate to the package's default route end point `/admin/comments/dashboard`

# Config

Config file is published as `comments-admin-panel.php` in the config directory.

If it is missing, you can re-publish it using the command below.

```bash
php artisan vendor:publish --tag=comments-admin-panel-config
```

This will publish following config file

```php
//config/comments-admin-panel.php
<?php

use LakM\Comments\AdminPanel\Middleware\AdminPanelAccessMiddleware;

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
```

## Authorization

By default, routes are enabled if it is not in production environment. You can change this behaviour by setting
enabled variable to false in the config file.

```php
'enabled' => false
````

### Production

Before deploying to production (in production mode), the `canAccessAdminPanel` method must be implemented in the auth
model; otherwise, you will receive a 404 status. This behaviour is handled by the `AdminPanelAccessMiddleware::class`
middleware.

```php
//config/comments-admin-panel.php
  public function canAccessAdminPanel(): bool
  {
     return true;
  }
```

> [!Note]
> Don't forget to set ``'enabled' => true`` in the config file.

### Route Prefix

By default, **admin** prefix is added to package's routes you can change it as following example.

```php
'routes' => ['prefix' => 'admin'],
```
### Route Middlewares

By default, `web` and `AdminPanelAccessMiddleware::class` middlewares are applied you can change it as following example.

```php
 'routes' => [
            'middlewares' => [
                'web',
                AdminPanelAccessMiddleware::class, // Removing this, allow users to access admin panel if enabled is set to true !
            ],
        ],
```

## Customization

You can customize all the views by publishing them using below commands,

```bash
    php artisan vendor:publish --tag=comments-admin-panel-resources
```
This will publish views to the ``\resources\views\comments-admin-panel`` directory.

### Assets
Package install command `comments-admin-panel:install`, publish assets to the `\public\vendor\lakm\comments-admin-panel` directory.

Here is the command to publish them manually.

```bash
    php artisan vendor:publish --tag=comments-admin-panel-assets
```

#### Build assets

You can build assets using any assets building tool. By default, package has used laravel vite plugin.
Make sure to use right build directory `public\vendor\lakm\comments-admin-panel\build`

> [!Important]
> When buiding assets the re should be only one input file and that is should be `app.js`.
> You can do that as following using vite.

 ```js
// vite.config.js

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            hotFile: 'public/vendor/lakm/comments-admin-panel/comments-admin-panel.hot',
            buildDirectory: 'vendor/lakm/comments-admin-panel/build', // This is important
            input: ['resources/js/app.js'], // This is important
            refresh: true,
        }),
    ],
});
```

## Changelog
Please see [CHANGELOG](https://github.com/Lakshan-Madushanka/laravel-comments-admin-panel/blob/main/CHANGELOG.md) for more information what has changed recently.

## Security
Please see [here](https://github.com/Lakshan-Madushanka/laravel-comments-admin-panel/blob/main/SECURITY.md) for our security policy.

## License
The MIT License (MIT). Please see [License File](https://github.com/Lakshan-Madushanka/laravel-comments-admin-panel/blob/main/LICENSE.md) for more information.
