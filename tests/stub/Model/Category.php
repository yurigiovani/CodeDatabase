<?php

namespace CodePress\CodeDataBase\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "codedatabase_categories";

    protected $fillable = [
        'name',
        'description',
    ];
}