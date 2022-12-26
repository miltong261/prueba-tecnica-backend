<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    //RUTA PARA LISTAR, GUARDAR Y ACTUALIZAR TIPO DE VEHÍCULOS (se toma en cuenta a futuro)
    Route::apiResource('type_vehicles', App\Http\Controllers\Api\V1\TypeVehicleController::class)->only('index', 'store', 'update');

    //RUTA PARA LISTAR, GUARDAR Y ACTUALIZAR EMPLEADOS
    Route::apiResource('employees', App\Http\Controllers\Api\V1\EmployeeController::class)->only('index', 'store', 'update');

    //RUTA PARA LISTAR Y ACTUALIZAR VEHÍCULOS
    Route::apiResource('vehicles', App\Http\Controllers\Api\V1\VehicleController::class)->only('index', 'update');

    //RUTA PARA REGISTRAR ENTRADA, REGISTRAR SALIDA Y COMENZAR MES
    Route::post('transactions/start_time', [App\Http\Controllers\Api\V1\TransactionController::class, 'start_time']);
    Route::put('transactions/end_time', [App\Http\Controllers\Api\V1\TransactionController::class, 'end_time']);
    Route::put('transactions/start_month', [App\Http\Controllers\Api\V1\TransactionController::class, 'start_month']);

    //RUTA PARA REALIZAR EL REPORTE DE PAGO (vehículos no residentes)
    Route::get('transactions/amount_import/{matricula}', [App\Http\Controllers\Api\V1\TransactionController::class, 'amount_import']);
    //RUTA PARA REALIZAR EL REPORTE DE HORAS DE ENTRADA Y SALIDA (vehículos oficiales)
    Route::get('transactions/oficial_vehicle_import/{matricula}', [App\Http\Controllers\Api\V1\TransactionController::class, 'oficial_vehicle_import']);
    //RUTA PARA REALIZAR EL REPORTE DEL TOTAL DE MINUTOS Y MONTO A PAGAR (vehículos residentes)
    Route::get('transactions/report/{name_pdf}', [App\Http\Controllers\Api\V1\TransactionController::class, 'report']);
});
