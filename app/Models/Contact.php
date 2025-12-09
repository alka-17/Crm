<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','email','phone','gender','profile_image','additional_file','is_active','merged_into','extra_emails','extra_phones'];
    protected $casts = [
        'is_active' => 'boolean',
        'extra_emails' => 'array',
        'extra_phones' => 'array',
    ];

    public function customValues(): HasMany
    {
        return $this->hasMany(CustomValue::class);
    }

    public function mergeLogs(): HasMany
    {
        // dd('hgdsf');
        return $this->hasMany(MergeLog::class, 'master_contact_id','id');
    }
}