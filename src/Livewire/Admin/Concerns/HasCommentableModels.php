<?php

namespace LakM\Comments\AdminPanel\Livewire\Admin\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use LakM\Comments\AdminPanel\Repository;
use LakM\Comments\Concerns\Commentable;

trait HasCommentableModels
{
    public function getModels(string $modelPath): array
    {
        $models = $this->loadModels( $modelPath);
        $this->setCommentCount($models);

        return $models;
    }

    private function loadModels(string $path, array &$models = []): array
    {
        foreach (new \DirectoryIterator($path) as $file) {
            if($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $this->loadModels(app_path("\\Models\\{$file->getFileName()}"), $models);
            }

            if (($ext = $file->getExtension()) === 'php') {
                $basePath = str_replace(app_path(), '', $file->getPathName());
                $namespace = str_replace('.'. $ext, '', 'App' . $basePath);

                if (is_subclass_of($namespace, Model::class) && Arr::has(class_uses($namespace), Commentable::class)) {
                    $key = str($namespace)->afterLast('App\\Models\\')->studly()->value();
                    $models[$key]['count'] = 0;
                    $models[$key]['instance'] = new $namespace;
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
