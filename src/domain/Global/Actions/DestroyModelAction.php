<?php

namespace Domain\Global\Actions;

use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Model;

class DestroyModelAction
{
    use Helper;

    public function execute(Model $model, array $relatedDeletions = []): void
    {
        foreach ($relatedDeletions as $relation => $deletionCallback) {
            if (method_exists($model, $relation)) {
                $deletionCallback($model->$relation());
            }
        }

        $model->delete();
    }
}
