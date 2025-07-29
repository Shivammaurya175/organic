<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
public function addToCart(Request $request)
{
    $productId = $request->input('product_id');
    $quantity = (int) $request->input('quantity', 1);

    $product = Product::findOrFail($productId);

    if (Auth::check()) {
        // Logged-in user: Save to DB
        $cartItem = CartItem::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        $cartItem->quantity += $quantity;
        $cartItem->save();

    } else {
        // Guest: Save to session
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'volume_point' => $product->volume_point,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);
    }

    return redirect()->back()->with('success', 'Item added to cart!');
}

    public function moveSessionCartToDatabase()
    {
        $cart = session('cart', []);
        foreach ($cart as $item) {
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $item['product_id']],
                ['quantity' => DB::raw('quantity + ' . $item['quantity'])]
            );
        }
        session()->forget('cart');
    }

    public function showCart()
{
    if (Auth::check()) {
        $items = \App\Models\CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();
    } else {
        $sessionCart = session('cart', []);
        $productIds = collect($sessionCart)->pluck('product_id')->toArray();
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = collect($sessionCart)->map(function ($item) use ($products) {
            $product = $products[$item['product_id']] ?? null;
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
            ];
        });
    }

    return view('addtocart', compact('items'));
}

public function updateQuantity(Request $request)
{
    $productId = $request->input('product_id');
    $quantity = max(1, (int) $request->input('quantity'));

    if (Auth::check()) {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->update(['quantity' => $quantity]);
    } else {
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }
        session(['cart' => $cart]);
    }

    return back();
}
public function updateQuantity1(Request $request, $id)
{
    if (Auth::check()) {
        // Logged-in user: Update CartItem from database
        $item = \App\Models\CartItem::findOrFail($id);

        if ($item->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->action === 'increase') {
            $item->quantity += 1;
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
        }

        $item->save();
    } else {
        // Guest user: Update quantity in session cart using product_id
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($request->action === 'increase') {
                $cart[$id]['quantity'] += 1;
            } elseif ($request->action === 'decrease' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] -= 1;
            }

            session(['cart' => $cart]);
        }
    }

    return redirect()->route('checkout')->with('success', 'Cart updated.');
}



public function removeItem(Request $request)
{
    $productId = $request->input('product_id');

    if (Auth::check()) {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();
    } else {
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }
    }

    return redirect()->back()->with('success', 'Item removed from cart.');
}

public function addToWishlist(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('user.login')->with('error', 'Please login to add to wishlist.');
    }

    $productId = $request->input('product_id');
    $userId = Auth::id();

    $exists = \App\Models\Wishlist::where('user_id', $userId)
                ->where('product_id', $productId)
                ->exists();

    if (!$exists) {
        \App\Models\Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    return redirect()->back()->with('success', 'Added to wishlist.');
}

public function removeFromWishlist(Request $request)
{
    Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->input('product_id'))
            ->delete();

    return back()->with('success', 'Removed from wishlist');
}


public function toggleWishlist(Request $request)
{
    $userId = Auth::id();

    $productId = $request->input('product_id');

    if (!$userId) {
        return redirect()->route('login')->with('error', 'Please login to manage wishlist.');
    }
    $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

    if ($wishlist) {
        $wishlist->delete(); 
    } else {
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    return back()->with('success', 'Wishlist updated');
}


}
