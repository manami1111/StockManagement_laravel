<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

// Route::HTTPメソッド('URL',[コントローラー::class,'コントローラーのメソッド'])->name('ルート名');
// HTTPメソッド→GET,POST,PUTまたはPATCH,DELETE
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// 商品の一覧を習得する(get:ページを表示)
Route::get('products', [ProductController::class, 'index']);
// 新しい商品を作成する(post:データを保存)
Route::post('products', [ProductController::class, 'store']);
// 商品を更新する(putまたはpatch:データの更新)({id}は商品ID)
Route::put('products/{id}', [ProductController::class, 'update']);
// 商品を削除する(delete:データの削除)
Route::delete('products/{id}', [ProductController::class, 'destroy']);