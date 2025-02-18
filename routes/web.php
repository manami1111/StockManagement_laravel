<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']); // ログインAPI

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/inventory', function () {
        // 在庫一覧を返す処理
        return response()->json(['message' => 'This is protected data']);
    });
});


Route::get('/{any}', function () {
    return view('react.index');
})->where('any', '.*');

Route::get('/react', function () {
    return view('react.index');
});
