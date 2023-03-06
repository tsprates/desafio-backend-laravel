<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function endereco()
    {
        return $this->hasOne(Endereco::class);
    }
}
