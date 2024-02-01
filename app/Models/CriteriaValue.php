<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaValue extends Model
{
    protected $table = 'criteria_values';

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'criteria_code',
        'value',
        'uom',
        'remark',
        'created_at',
        'updated_at'
    ];
    use HasFactory;
}
