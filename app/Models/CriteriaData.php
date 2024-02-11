<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaData extends Model
{
    protected $table = 'criteria_details';

    protected $fillable = [
        'tender_id',
        'criteria_code',
        'criteria_weight',
        'criteria_type',
        'remark',
        'created_at',
        'updated_at'
    ];


    use HasFactory;
}
