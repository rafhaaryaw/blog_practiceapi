<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;
    protected $table = 'posts';


    protected $primarykey = 'id';

    protected $fillable = ['title', 'r_id_category', 'content'];


    protected $guarded = ['id'];
}
