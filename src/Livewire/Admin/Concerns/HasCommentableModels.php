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
    public function getModels(string $modelPath): array
    {
        /** @var array<int, class-string> $models */
        $models = config('comments-admin-panel.commentable_models');

        if (count($models) > 0) {
            $this->validateModels($models);
            $models = $this->prepareModels($models);
        } else {
            $models = $this->loadModels($modelPath);
        }

        $this->setCommentCount($models);

        return $models;
    }


    /**
     * @param array $models
     * @throws \Throwable
     */
    private function validateModels(array $models): void
    {
        foreach ($models as $model) {
            Helpers::checkCommentableModelValidity(new $model);
        }
    }

    private function prepareModels(array $models): array
    {
        $preparedModels = [];

        foreach ($models as $model) {
            $key = str($model)
                ->afterLast('App' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR)
                ->studly()
                ->value();

            $preparedModels[$key]['count'] = 0;
            $preparedModels[$key]['instance'] = new $model();
        }

        return $preparedModels;
    }

    private function loadModels(string $path, array &$models = []): array
    {
        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $root = Str::after($path, app_path());
                $this->loadModels(app_path($root . DIRECTORY_SEPARATOR . "{$file->getFileName()}"), $models);
            }

            if (($ext = $file->getExtension()) === 'php') {
                $basePath = str_replace(app_path(), '', $file->getPathName());
                $namespace = str_replace('.' . $ext, '', 'App' . $basePath);

                if (is_subclass_of($namespace, Model::class) && is_subclass_of($namespace, CommentableContract::class)) {
                    $key = str($namespace)
                        ->afterLast('App' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR)
                        ->studly()
                        ->value();

                    $models[$key]['count'] = 0;
                    $models[$key]['instance'] = new $namespace();
                }
            }
        }

        return $models;
    }

    private function setCommentCount(array &$models): void
    {
        foreach ($models as $key => $model) {
            $models[$key]['count'] = Repository::commentsCountOf($model['instance']);
        }
    }
}
