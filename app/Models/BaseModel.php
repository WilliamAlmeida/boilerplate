<?php

namespace App\Models;

use App\Casts\DatetimeWithTimezoneGetOnly;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $casts = [
        'created_at' => DatetimeWithTimezoneGetOnly::class,
        'updated_at' => DatetimeWithTimezoneGetOnly::class,
        'deleted_at' => DatetimeWithTimezoneGetOnly::class,
    ];

    public function mergeCasts($casts)
    {
        $this->casts = array_merge(
            array_merge($this->casts, $casts), 
            $this->casts
        );

        return $this;
    }
}
