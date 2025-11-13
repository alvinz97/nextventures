<?php

namespace Support\Database;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends BaseModel
{
    use HasUuid;
    use HasFactory;

    protected $keyType = 'string';
}
