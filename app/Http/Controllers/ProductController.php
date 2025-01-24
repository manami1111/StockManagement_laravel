<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    //商品一覧取得
    public function index()
    {
        return response()->json(Product::all());
    }
    // return response() ->json($data);
    // response()はレスポンスを作成するメソッド
    // json()を使うとデータをJSON形式にして返す
    // (フロントエンドとAPI間でデータのやり取りをする際扱いやすくするため)
    // Product::all()で、データベースに保存されているすべての商品を取り出す



    // 商品作成
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date|after:today',
        ]);

        $product = Product::create($validatedData);

        return response()->json($product, 201);
    }
    // RequestはLravelのクラス名(Illuminate\Http\Request),ユーザーが送信したデータを表す
    // Laravelでは、Requestというクラスを使って、フォームデータ、URLパラメータ、ヘッダー情報などのリクエスト情報を取得できる
    // $requestはフロントエンドから送られてきたデータを受け取るためのオブジェクト
    // Request クラスのインスタンス（オブジェクト）を $request という変数に注入している
    // Product::createはProductモデルを使ってデータベースに新しい商品を作成する
    // $request->nameなど $requestオブジェクトからnameというデータを取得する
    // それを連想配列で 'name' => $request->name として渡すことで、データベースの「name」フィールドに保存される
    // 新しい商品がデータベースに作成されて$product変数に格納される
    // response()->json(...):JSON形式のレスポンスを返す方法。レスポンスは、クライアント（通常はブラウザやAPIを利用するアプリケーション）に返される
    // 201:HTTPステータスコードで、"Created"（作成された）を意味し、新しいリソースが正常に作成されたときに使われる

    // 商品更新
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $product->update($request->all());
        return response()->json($product);
    }
    // Request $request:HTTPリクエストから送信されたデータ（商品名、価格、数量など）を受け取るための引数
    // $requestオブジェクトは、ユーザーが送信したフォームデータを含んでいる
    // $id:URLパラメータ(WebサイトやAPIのURLの一部として、追加の情報を渡すために使われるデータ)として渡された商品ID。
    // このIDを使って、データベースから更新対象の商品を特定する
    // find($id)は、引数として渡された$id（商品ID）に対応する商品レコードをデータベースから検索。
    // もしそのIDに一致する商品が見つかれば、その商品データが返されます。
    // Productモデルを使ってデータベースから一致する商品を探して$productに格納
    // この商品オブジェクトに対して、update()メソッドを使って、データを更新
    // $request->all(): $request->all()は、HTTPリクエストで送信されたすべてのデータを配列として取得
    // つまり、フォームから送信された商品名、価格、数量、有効期限など、全てのデータを一度に取得して更新する

    // 商品削除
    public function destroy($id)
    {
        // Product::destroy($id);
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(null, 204);
    }
    // HTTPのDELETE（他にもGETやPOST等）などのメソッドは、サーバに対する要請の種類を表すもの
    // Controllerに記述するdestroy等のメソッドは、オブジェクト指向における振る舞い（メンバ関数）の事
    // delete()=複数削除 destroy()=単体削除
    // Product::destroy($id) :Productモデルを使って指定したIDの商品をデータベースから削除する
    // 削除できた→destroyメソッドは削除したレコード数を返す(1件削除なら1)
    // 商品が見つからない→削除されたレコード数がないから0を返す
    // null は 削除された商品の情報は返さないということ
    // 204 は 削除が成功したが、返すべきデータはないということ
    // null と 204 を合わせて使うことで、「削除が成功した」「返すべきデータはない」ということを明確に伝えることができる


    // 商品のエクスポート(Excel)
    public function export()
    {
        try {
            Log::info("Export started");
            // エクスポートが開始されたことをログに記録
            // ログを見返してエクスポート処理が正常に始まったかを確認できる

            // Excelファイルをダウンロード
            return
                Excel::download(new ProductsExport, 'products.xlsx');
            // Excel::download() は、Laravel Excelパッケージが提供するメソッド
            // ProductsExport クラスのデータを products.xlsx という名前でダウンロードする
        } catch (\Exception $e) { // エクスポート中にエラーが発生した場合
            Log::error("Export failed: " . $e->getMessage());
            // エクスポート処理が失敗した場合、そのエラーをエラーログに記録

            return
                response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
            // response()->json():Laravelのレスポンスヘルパーで、JSON形式のレスポンスを返すためのメソッド
            // JSON形式とは、データを {}（波かっこ）で囲んだオブジェクトや、[]（角かっこ）で囲んだ配列の形式で表現するもの

        }
    }


    // // 商品のエクスポート(CSV)

    public function exportCsv()
    {
        try {
            // エラーが起こったらキャッチに移る
            return response()->streamDownload(function () {
                // response()->streamDownload : Laravelのヘルパーメソッドで、ストリーム形式でダウンロード
                // ストリーム形式 : 大量のデータを一度にメモリに読み込むのではなく、少しずつ生成・送信する方法
                // function () {...} : 無名関数。一時的に使う関数
                $products = (new \App\Exports\ProductsExport())->collection();
                // 「コレクション」とは、データを操作しやすい形にまとめた便利なデータの集まり
                // コレクションは主に Illuminate\Support\Collection クラスを使って管理され、配列やデータベースの結果を扱いやすくするためのツール
                // $productsに製品データをコレクションとして保存


                // CSVデータを1行ずつ作成
                $csvLines = $products->map(function ($product) {
                    // 製品データをmapで１つずつ取り出す
                    return implode(",", $product->toArray());
                    // $product->toArray() : 製品データを単純な配列形式（['value1', 'value2', ...]）に変換
                    // implode(",", ...) : 配列をカンマで区切った文字列に変換
                });

                echo $csvLines->implode("\n");
                // implode("\n") :コレクション内の各要素を改行（\n）で区切って結合
            }, 'products.csv', [
                // ファイルの種類と名前を設定
                'Content-Type' => 'text/csv', //ファイルの種類
                'Content-Disposition' => 'attachment; filename="products.csv"', //ダウンロードした時のファイル名
            ]);
        } catch (\Exception $e) {
            Log::error('CSVエクスポート中にエラー発生: ' . $e->getMessage());
            // サーバーのログファイルにエラー内容を記録

            // ユーザーにエラーメッセージを返す
            return response()->json([
                'error' => 'CSVエクスポート中にエラーが発生しました。',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // 商品の詳細情報を取得する
    public function show($id)
    {
        // 商品IDを元に商品情報を取得
        $product = Product::find($id);
        
        // 商品が見つからなかった場合
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // 商品情報を返す
        return response()->json($product);
    }
}
