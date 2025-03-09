<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

    public function list(Request $request)
    {
        $query = Product::select("products.*");
        $query->when($request->keywords, fn($q) => $q->where("name", "like", "%$request->keywords%"));
        $query->when($request->min_price, fn($q) => $q->where("price", ">=", $request->min_price));
        $query->when($request->max_price, fn($q) => $q->where("price", "<=", $request->max_price));
        $query->when($request->order_by, fn($q) => $q->orderBy($request->order_by, $request->order_direction ?? "ASC"));
        $products = $query->get();
        return view("products.list", compact('products'));
    }

    public function edit(Request $request, Product $product = null)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $product = $product ?? new Product();
        return view("products.edit", compact('product'));
    }

    public function save(Request $request, Product $product = null)
    {
        $product = $product ?? new Product();
        $product->fill($request->all());
        $product->save();
        return redirect()->route('products_list');
    }

    public function delete(Request $request, Product $product)
    {
        $product->delete();
        return redirect()->route('products_list');
    }
}
