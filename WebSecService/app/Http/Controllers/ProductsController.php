<?php

namespace App\Http\Controllers;

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
        
        // Only show non-held products to customers
        if (auth()->check() && !auth()->user()->hasRole(['Admin', 'Employee'])) {
            $query->where('hold', false);
        }
        
        // Simple direct filtering by favourite
        if ($request->has('favourites_only')) {
            $query->where('favourite', true);
        }
        
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
        
        // Check permission to edit products
        if (!auth()->user()->hasPermissionTo('edit_products')) {
            abort(403, 'You do not have permission to edit products.');
        }
        
        $product = $product ?? new Product();
        return view("products.edit", compact('product'));
    }

    public function save(Request $request, Product $product = null)
    {
        // Check permission to add/edit products
        if (!auth()->user()->hasPermissionTo('edit_products')) {
            abort(403, 'You do not have permission to edit products.');
        }
        
        $product = $product ?? new Product();
        
        // Fill product data first (but don't save yet)
        $product->fill($request->except('photo'));
        
        // Handle file upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            
            // Make sure the image directory exists
            $imagesPath = public_path('images');
            if (!is_dir($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }
            
            // Create a unique filename
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
            
            try {
                // Move the uploaded file
                if ($file->move($imagesPath, $filename)) {
                    // Remove old image if it exists
                    if ($product->photo && is_file(public_path('images/' . $product->photo))) {
                        @unlink(public_path('images/' . $product->photo));
                    }
                    
                    $product->photo = $filename;
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }
        
        // Save the product
        $product->save();
        
        return redirect()->route('products_list')->with('success', 'Product saved successfully.');
    }

    public function delete(Request $request, Product $product)
    {
        // Check permission to delete products
        if (!auth()->user()->hasPermissionTo('delete_products')) {
            abort(403, 'You do not have permission to delete products.');
        }
        
        $product->delete();
        return redirect()->route('products_list');
    }
    
    public function hold(Request $request, Product $product)
    {
        // Check permission to hold products
        if (!auth()->user()->hasPermissionTo('hold_products')) {
            abort(403, 'You do not have permission to hold products.');
        }
        
        $product->hold = true;
        $product->save();
        
        return redirect()->route('products_list')
            ->with('success', "Product '{$product->name}' is now on hold.");
    }

    public function unhold(Request $request, Product $product)
    {
        // Check permission to unhold products
        if (!auth()->user()->hasPermissionTo('hold_products')) {
            abort(403, 'You do not have permission to unhold products.');
        }
        
        $product->hold = false;
        $product->save();
        
        return redirect()->route('products_list')
            ->with('success', "Product '{$product->name}' is now available.");
    }

    public function updateStock(Request $request, Product $product)
    {
        // Check permission to manage stock
        if (!auth()->user()->hasPermissionTo('manage_stock')) {
            abort(403, 'You do not have permission to manage stock.');
        }
        
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);
        
        $product->stock = $request->stock;
        $product->save();
        
        return redirect()->route('products_list')
            ->with('success', "Stock for '{$product->name}' updated successfully.");
    }

    public function toggleFavourite(Request $request, Product $product)
    {
        // Toggle the favourite status
        $product->favourite = !$product->favourite;
        $product->save();
        
        return redirect()->back()->with('success', 
            $product->favourite 
                ? "Added {$product->name} to favourites." 
                : "Removed {$product->name} from favourites."
        );
    }
}
