<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convite extends Model
{
    protected $fillable = ['email', 'token', 'usado_em', 'criado_por', 'expira_em'];
}
