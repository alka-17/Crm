<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomValue extends Model
{
    use SoftDeletes;
    protected $fillable = ['contact_id','custom_field_id','value'];

    public function field()
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }
}
