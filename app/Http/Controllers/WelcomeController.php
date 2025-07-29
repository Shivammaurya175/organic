<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class WelcomeController extends Controller
{
    //
    public function index(){
        $category=Category::all();
        $product=Product::all();
        return view('index',compact('category','product'));
    }
      public function showCart1()
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

    return view('cart.index', compact('items'));
}
    public function register(){
        return view('authentication-register');
    }
     public function register1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'referral_code' => ['nullable', 'string', 'exists:users,user_id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
          $namePart = substr(preg_replace('/\s+/', '', $request->name), 0, 4); // Remove spaces
    $randomPart = rand(1000, 9999);
    $userId = strtoupper($namePart . $randomPart);

    // Lookup parent user if referral code provided
    $referralCode = $request->input('referral_code');
    $parentId = null;

    if ($referralCode) {
        $parent = User::where('user_id', $referralCode)->first();
        if ($parent) {
            $parentId = $parent->user_id;
        }
    }
        $de=encrypt($request->password);
        User::create([
            'user_id' => $userId,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'decript_password'=>$request->input('password'),
            'referral_id' => $parentId, // Save parent ID
        ]);

        return redirect()->route('user.login')->with('success', 'Account created successfully. Please log in.');
    }
    public function login(){
        return view('authentication-login');
    }
  
     // Handle login request
    public function login1(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
        $request->session()->regenerate(); 

        // Redirect based on user role
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin_dashboard');
        } elseif ($user->role === 'user') {
            return redirect()->route('user_dashboard'); // or any other route for normal users
        }
        else {
            return redirect()->route('login');
        }
    }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    
    }
    // Logout method (optional)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('web.index'));
    }
}

