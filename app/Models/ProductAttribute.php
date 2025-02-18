<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes'; // Ensure this matches your database table name
    protected $primaryKey = 'product_attr_id';
    public $timestamps = false; // Set to true if your table has `created_at` and `updated_at`

    protected $fillable = [
        'product_id', 
        'attribute_name', 
        'attribute_value',
    ];
}
