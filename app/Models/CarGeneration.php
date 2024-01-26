<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'name',
        'period',
        'image_path',
        'details_page_path',
    ];
}
