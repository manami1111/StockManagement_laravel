<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::middleware('auth:sanctum')->group(function () {

// Route::HTTPメソッド('URL',[コントローラー::class,'コントローラーのメソッド'])->name('ルート名');
// HTTPメソッド→GET,POST,PUTまたはPATCH,DELETE

// 商品の一覧を習得する(get:ページを表示)
Route::get('products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// 新しい商品を作成する(post:データを保存)
Route::post('products', [ProductController::class, 'store']);
// 商品を更新する(putまたはpatch:データの更新)({id}は商品ID)
Route::put('products/{id}', [ProductController::class, 'update']);
// 商品を削除する(delete:データの削除)
Route::delete('products/{id}', [ProductController::class, 'destroy']);

// データをエクスポートする
Route::get('/products/export', [ProductController::class, 'export']);
Route::get('/products/export-csv', [ProductController::class, 'exportCsv']);

// // ユーザ登録用
// Route::post('/register', [AuthController::class, 'register']);

// // ログイン用
// Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/inventory', function () {
    // 在庫一覧を返す処理
    return response()->json(['message' => 'This is protected data']);
});
