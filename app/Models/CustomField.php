<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    protected $fillable = ['name','type','options'];
    protected $casts = ['options' => 'array']; // auto decode JSON

    public function values(): HasMany
    {
        return $this->hasMany(CustomValue::class);
    }
}
