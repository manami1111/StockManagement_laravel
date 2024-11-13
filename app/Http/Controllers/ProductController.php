<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //商品一覧取得
    public function index()
    {
        return response()->json(Product::all());
    }
    // Product::all()で、データベースに保存されているすべての商品を取り出す
    // JSON形式でデータを返す(reactで扱いやすくするため)


    // 商品作成
    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'expiration_date' => $request->expiration_date,
        ]);
        return response()->json($product, 201);
    }

    // 商品更新
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());
        return response()->json($product);
    }

    // 商品削除
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }
}
