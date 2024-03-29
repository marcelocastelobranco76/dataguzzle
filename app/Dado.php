<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dado extends Model
{
    protected $fillable = ['vigencia','valor_mensal'];
    protected $guarded = ['id', 'created_at', 'update_at'];
    protected $table = 'dados';
}
