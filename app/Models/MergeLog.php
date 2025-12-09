<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MergeLog extends Model
{
    use SoftDeletes;
    protected $fillable = ['master_contact_id','secondary_contact_id','merged_data','notes','performed_by'];
    protected $casts = ['merged_data' => 'array'];
}
