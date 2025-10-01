<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tareas extends Model
{
    protected $fillable = ['titulo', 'descripcion', 'fecha_inicio','hora_inicio', 'fecha_fin', 'hora_fin', 'prioridad', 'estado'];
}
