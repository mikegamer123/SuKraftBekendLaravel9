<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use  \TCG\Voyager\Traits\Translatable;

class Post extends Model
{
    Use Translatable;
    use HasFactory;
    protected $guarded = [];

}
