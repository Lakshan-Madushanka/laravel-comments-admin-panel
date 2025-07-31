<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin\Concerns;

trait CanNavigate
{
    public function navigate(string $key): void
    {
        $this->redirect($this->getModelRoute($key));
    }

    public function getModelRoute(string $key): string
    {
        return route('admin.models.comments.index', ['name' => str($key)->replace('\\', '')->kebab()->plural()]);
    }
}
