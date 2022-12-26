<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EmployeeRequest;
use App\Http\Resources\Api\V1\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Retornar lista del recurso (empleados).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleados = Employee::all();
        
        return EmployeeResource::collection($empleados)->response()->setStatusCode(200);
    }

    /**
     * Registar el recurso en la base de datos (empleado).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        try {
            Employee::create($request->only(
                'first_name',
                'last_name'
            ));

            return response()->json([
                'message' => "¡El empleado $request->first_name $request->last_name se registro correctamente!"
            ])->setStatusCode(201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualizar un recurso específico en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::where('id', $id)->update($request->only(
                'first_name',
                'last_name'
            ));

            if (!$employee) {
                return response()->json([
                    'message' => "¡Empleado no encontrado!"
                ])->setStatusCode(404);
            }

            return response()->json([
                'message' => "¡La información del empleado se actualizó correctamente!"
            ])->setStatusCode(201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ])->setStatusCode(500);
        }
    }
}
