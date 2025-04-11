<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function list()
    {
        // For admins, show all purchases
        if (auth()->user()->hasRole('Admin')) {
            $purchases = Purchase::with(['product', 'user'])->latest()->get();
        } 
        // For customers, only show their own purchases
        elseif (auth()->user()->hasPermissionTo('purchase_products')) {
            $purchases = auth()->user()->purchases()->with('product')->latest()->get();
        }
        // For users without permissions
        else {
            abort(403, 'You do not have permission to view purchase history.');
        }
        
        return view('purchases.list', compact('purchases'));
    }
    
    public function showPurchaseForm(Product $product)
    {
        // Check if user has permission to purchase products
        if (!auth()->user()->hasPermissionTo('purchase_products')) {
            abort(403, 'You do not have permission to purchase products.');
        }
        
        // Check if product is on hold
        if ($product->hold) {
            return redirect()->route('products_list')
                ->with('error', 'This product is currently unavailable for purchase.');
        }
        
        // Check if product is in stock
        if ($product->stock <= 0) {
            return redirect()->route('products_list')
                ->with('error', 'This product is out of stock.');
        }
        
        return view('purchases.form', compact('product'));
    }
    
    public function purchase(Request $request, Product $product)
    {
        // Check if user has permission to purchase products
        if (!auth()->user()->hasPermissionTo('purchase_products')) {
            abort(403, 'You do not have permission to purchase products.');
        }
        
        // Validate the form
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Check if product is on hold
        if ($product->hold) {
            return redirect()->route('products_list')
                ->with('error', 'This product is currently unavailable for purchase.');
        }
        
        // Check if there's enough stock
        if ($product->stock < $validated['quantity']) {
            return redirect()->route('purchase_form', $product->id)
                ->with('error', "Not enough stock available. Only {$product->stock} items left.")
                ->withInput();
        }
        
        // Calculate total price
        $totalPrice = $product->price * $validated['quantity'];
        
        // Check if user has enough credit
        if (auth()->user()->credit < $totalPrice) {
            return redirect()->route('purchase_form', $product->id)
                ->with('error', 'You do not have enough credit to complete this purchase.')
                ->withInput();
        }
        
        // Use database transaction to ensure all operations succeed or fail together
        DB::beginTransaction();
        
        try {
            // Create purchase record
            $purchase = new Purchase([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
                'total_price' => $totalPrice,
            ]);
            $purchase->save();
            
            // Update product stock
            $product->stock -= $validated['quantity'];
            $product->save();
            
            // Deduct credit from user
            $user = auth()->user();
            $user->credit -= $totalPrice;
            $user->save();
            
            DB::commit();
            
            return redirect()->route('purchases_list')
                ->with('success', "Successfully purchased {$validated['quantity']} {$product->name}(s).");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchase_form', $product->id)
                ->with('error', 'An error occurred while processing your purchase. Please try again.')
                ->withInput();
        }
    }
} 