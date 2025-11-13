<?php

namespace Support\Database;

use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = strtoupper(Str::orderedUuid()->toString());
        });
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getIncrementing(): bool
    {
        return false;
    }
}
