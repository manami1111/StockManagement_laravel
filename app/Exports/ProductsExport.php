<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
{
    /**
     * 商品データを返す
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::all();  // 商品データを全て取得
    }
}
