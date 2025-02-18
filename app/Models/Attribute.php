<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $table = 'attributes';
    protected $primaryKey = 'attribute_row_id'; 
    protected $fillable = [
        'attributes_name',
        'attributes_value',
        'created_by',
    ];
}
