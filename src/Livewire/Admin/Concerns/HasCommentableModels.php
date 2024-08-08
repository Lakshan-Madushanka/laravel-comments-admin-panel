<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LakM\Comments\Contracts\CommentableContract;
use LakM\Comments\Helpers;
use LakM\CommentsAdminPanel\Repository;

trait HasCommentableModels
{
    /** @throws \Throwable */
    public function getModels(string $modelPath, string $modelNamespace = '', string $modelRootNamespace = ''): array
    {
        /** @var array<int, class-string> $models */
        $models = config('comments-admin-panel.commentable_models');

        $modelNamespace = $this->getModelNamespace($modelNamespace);
        $modelRootNamespace = $this->getModelRootNamespace($modelNamespace, $modelRootNamespace);

        if (count($models) > 0) {
            $this->validateModels($models);
            $models = $this->prepareModels($models, $modelNamespace);
        } else {
            $models = $this->loadModels(
                path: $modelPath,
                modelNamespace: $modelNamespace,
                modelRootNamespace: $modelRootNamespace
            );
        }

        $this->setCommentCount($models);

        return $models;
    }

    public function getModelNamespace(string $modelNamespace): string
    {
        if (empty($modelNamespace)) {
            $modelNamespace = 'App\Models\\';
        }

        if (!Str::endsWith($modelNamespace, '\\')) {
            $modelNamespace .= '\\';
        }

        return $modelNamespace;
    }

    public function getModelRootNamespace(string $modelNamespace, string $modelRootNamespace): string
    {
        if (empty($modelRootNamespace)) {
            $modelRootNamespace = Str::before($modelNamespace, '\\');
        }

        if (Str::endsWith($modelRootNamespace, '\\')) {
            $modelRootNamespace = Str::replaceLast('\\', '', $modelRootNamespace);
        }

        return $modelRootNamespace;
    }

    /**
     * @param  array  $models
     * @throws \Throwable
     */
    public function validateModels(array $models): void
    {
        foreach ($models as $model) {
            Helpers::checkCommentableModelValidity(new $model());
        }
    }

    public function prepareModels(array $models, string $modelNamespace): array
    {
        $preparedModels = [];

        foreach ($models as $model) {
            $key = str($model)
                ->afterLast($modelNamespace)
                ->studly()
                ->value();

            $preparedModels[$key]['count'] = 0;
            $preparedModels[$key]['instance'] = new $model();
        }

        return $preparedModels;
    }

    public function loadModels(
        string $path,
        string $modelNamespace,
        string $modelRootNamespace,
        array &$models = []
    ): array {
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);

        /** @var \SplFileInfo $file */
        foreach ($iterator as $pathName => $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $basePath = str_replace(app_path(), '', $pathName);
            $namespace = str_replace('/', '\\', str_replace('.php', '', $modelRootNamespace . $basePath));

            if (is_subclass_of($namespace, Model::class) && is_subclass_of($namespace, CommentableContract::class)) {
                $key = str($namespace)
                    ->afterLast($modelNamespace)
                    ->studly()
                    ->value();

                $models[$key]['count'] = 0;
                $models[$key]['instance'] = new $namespace();
            }
        }
        return $models;
    }

    public function setCommentCount(array &$models): void
    {
        foreach ($models as $key => $model) {
            $models[$key]['count'] = Repository::commentsCountOf($model['instance']);
        }
    }
}
