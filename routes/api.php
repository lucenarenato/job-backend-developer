<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('info', function () {
    $app_info = [
        'server' => getenv('APP_NAME'),
        'version' => getenv('APP_VERSION')
    ];

    return response()->json($app_info);
});

Route::get('all', [ProdutoController::class, 'index']);
Route::get('getId/{id}', [ProdutoController::class, 'show']);
Route::post('create', [ProdutoController::class, 'store']);

