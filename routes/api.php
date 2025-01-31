<?php

use App\Http\Controllers\PassportController;
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

Route::prefix('produto')->group(function () {
    Route::get('all', [ProdutoController::class, 'index']);
    Route::get('getId/{id}', [ProdutoController::class, 'show']);
    Route::post('create', [ProdutoController::class, 'store']);
    Route::put('update/{id}', [ProdutoController::class, 'update']);
    Route::delete('delete/{id}', [ProdutoController::class, 'destroy']);
    Route::post('search/nameCategory', [ProdutoController::class, 'searchByNameCategory']);
    Route::get('search/{category}', [ProdutoController::class, 'searchByCategory']);
    Route::post('search/image', [ProdutoController::class, 'searchByUrlImage']);
});

// Plus
Route::post('login', [PassportController::class, 'login']);
Route::post('user/register', [PassportController::class, 'register']);
Route::post('/logout', [PassportController::class, 'logout']);

Route::group(['middleware' => ['cors', 'json.response']], function () {
    //
});
