<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tunjTranspost extends Model
{
    protected $table = 'tunj_transposts';
    protected $fillable = ['name', 'description', 'amount', 'type', 'status'];
}
