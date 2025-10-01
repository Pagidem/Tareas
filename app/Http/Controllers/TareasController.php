<?php

namespace App\Http\Controllers;

use App\Models\Tareas;
use Illuminate\Http\Request;

class TareasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Tareas::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required | string | max:255',
            'descripcion' => 'nullable | string',
            'fecha_inicio' => 'nullable | string | max:60',
            'hora_inicio' => 'nullable | string | max:60',
            'fecha_fin' => 'nullable | string | max:60',
            'hora_fin' => 'nullable | string | max:60',
            'prioridad' => 'nullable | string | max:255',
            'estado' => 'nullable | string | max:255',
        ]);
        $registro = Tareas::create($validated);
        return response()->json($registro, 201);
    }

    public function concluirTarea($id)
    {
        $tarea = Tareas::find($id);
        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada'], 404);
        }
        $tarea->estado = 'Concluida';
        $tarea->save();
        return response()->json($tarea);
    }

    public function cambiarestado($id,$estado)
    {
        $tarea = Tareas::find($id);
        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada'], 404);
        }
        $tarea->estado = $estado;
        $tarea->save();
        return response()->json($tarea);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tareas $tareas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'titulo' => 'required | string | max:255',
            'descripcion' => 'nullable | string',
            'fecha_inicio' => 'nullable | string | max:60',
            'hora_inicio' => 'nullable | string | max:60',
            'fecha_fin' => 'nullable | string | max:60',
            'hora_fin' => 'nullable | string | max:60',
            'prioridad' => 'nullable | string | max:255',
            'estado' => 'nullable | string | max:255',
        ]);
        $tarea = Tareas::findOrFail($id);
        $tarea->update($validated);
        return response()->json($tarea);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tareas $tareas)
    {
        //
    }
}
