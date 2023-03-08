<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $hidden = ['id', 'paciente_id', 'deleted_at', 'created_at', 'updated_at'];

    protected $fillable = ['cep', 'endereco', 'numero', 'bairro', 'complemento', 'cidade', 'estado'];
}
