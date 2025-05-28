<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Ito ang home page ng website.
     * Dito makikita ang impormasyon ng shop at mga bagong kategorya/produkto.
     */
    public function index(){
        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'category' => Category::all()->sortByDesc('id')->take(4), // Kukunin ang 4 na pinakabagong kategorya
            'title' => 'Home' // Ang titulo ng page
        ];

        return view('client.index', $data); // Ibabalik ang 'index' view
    }

    /**
     * Ipinapakita nito ang lahat ng produkto na available.
     * May pagination din para hindi masyadong marami ang nakikita sa isang page.
     */
    public function products(){
        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'product' => Product::orderBy('id', 'DESC')->paginate(16), // Kukunin ang mga produkto, 16 per page
            'category' => Category::all()->sortByDesc('id'), // Kukunin ang lahat ng kategorya
            'title' => 'Products' // Ang titulo ng page
        ];

        return view('client.products', $data); // Ibabalik ang 'products' view
    }

    /**
     * Naghahanap ito ng produkto batay sa keyword na inilagay ng user.
     * Ipapakita ang mga resultang tugma sa search term.
     */
    public function searchProduct(Request $request){
        $validator = Validator::make($request->all(), [ // Susuriin ang input ng user
            'product' => 'required|string|max:255' // Kailangan may input at hindi masyadong mahaba
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput(); // Kung may mali sa input, ibalik sa dating page na may error
        }else{
            $searchTerm = $request->product; // Ang hinahanap na term
            $data = [
                'title' => 'Search Results for ' . $searchTerm, // Titulo ng search results
                'shop' => Shop::first(), // Impormasyon ng shop
                'product' => Product::where('title', 'LIKE', '%'.$searchTerm.'%')->orderBy('id', 'DESC')->paginate(20), // Hanapin ang mga produkto na may tugmang title
                'search' => $searchTerm // Ang search term para sa view
            ];

            return view('client.productSearch', $data); // Ibabalik ang 'productSearch' view
        }
    }

    /**
     * Ipinapakita nito ang listahan ng lahat ng kategorya ng produkto.
     */
    public function category(){
        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'category' => Category::orderBy('id', 'DESC')->paginate(12), // Kukunin ang mga kategorya, 12 per page
            'title' => 'Categories' // Ang titulo ng page
        ];

        return view('client.category', $data); // Ibabalik ang 'category' view
    }

    /**
     * Ipinapakita nito ang lahat ng produkto sa ilalim ng isang partikular na kategorya.
     */
    public function categoryProducts($name_slug){
        $category = Category::where('slug', $name_slug)->firstOrFail(); // Hahanapin ang kategorya gamit ang slug, o mag-404 kung wala

        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'category' => $category, // Ang kategorya na napili
            'products' => $category->products()->orderBy('id', 'DESC')->paginate(16), // Kukunin ang mga produkto sa ilalim ng kategorya
            'title' => 'Category - '. str_replace('-', ' ', ucwords($category->name)) // Titulo ng page batay sa pangalan ng kategorya
        ];

        return view('client.categoryProducts', $data); // Ibabalik ang 'categoryProducts' view
    }

    /**
     * Ipinapakita nito ang detalyadong impormasyon ng isang produkto.
     * Mayroon din itong rekomendasyon ng ibang produkto.
     */
    public function productDetail($title_slug){
        $product = Product::where('slug', $title_slug)->firstOrFail(); // Hahanapin ang produkto gamit ang slug, o mag-404 kung wala

        // Kukunin ang mga rekomendasyon ng produkto
        if($product->category && $product->category->product->count() > 1){
            $recomendationProducts = $product->category->product->where('id', '!=', $product->id)->take(8); // Mga produkto sa parehong kategorya, maliban sa kasalukuyan
        }else{
            $recomendationProducts = Product::where('id', '!=', $product->id)->orderByDesc('id')->take(8); // Iba pang produkto kung walang kategorya o iisa lang
        }

        $data = [
            'shop' => Shop::first(), // Impormasyon ng shop
            'product' => $product, // Ang detalyadong produkto
            'recomendationProducts' => $recomendationProducts, // Mga rekomendasyon
            'title' => Str::title(str_replace('-', ' ', $product->title)) // Titulo ng page batay sa pangalan ng produkto
        ];

        return view('client.productDetail', $data); // Ibabalik ang 'productDetail' view
    }

    /**
     * Ito ang page kung saan magche-checkout ang user.
     * Makikita nila ang kanilang cart at kailangan nilang ilagay ang kanilang details.
     */
    public function checkout(){
        $cart = session()->get('cart'); // Kukunin ang cart mula sa session

        // Kung walang laman ang cart, ibalik sa cart page na may error
        if (!$cart || count($cart) == 0) {
            return redirect()->route('clientCart')->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        $user = auth()->user(); // Kukunin ang impormasyon ng naka-login na user (para sa pre-filling ng form)

        $data = [
            'shop' => Shop::first(), // Impormasyon ng shop
            'title' => 'Checkout', // Titulo ng page
            'user' => $user, // Impormasyon ng user
        ];

        return view('client.checkout', $data); // Ibabalik ang 'checkout' view
    }

    /**
     * Sine-save nito ang order ng user.
     * Sinusuri din ang stock ng produkto at kinakaltasan ito.
     * Pagkatapos, lilinisin ang cart.
     */
    public function checkoutSave(Request $request){
        $validator = Validator::make($request->all(), [ // Susuriin ang input ng user para sa order details
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput(); // Kung may mali, ibalik sa dating page na may error
        }

        $cart = session()->get('cart'); // Kukunin ang cart

        // Kung walang laman ang cart, ibalik sa cart page na may error
        if (!$cart || count($cart) == 0) {
            return redirect()->route('clientCart')->with('error', 'Your cart is empty. Please add items before placing an order.');
        }

        $total = 0;
        // Susuriin ang stock ng produkto bago mag-place ng order
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if (!$product || $product->stock < $details['quantity']) {
                return redirect()->back()->withErrors(['cart_error' => "Not enough stock for {$details['title']}. Available: {$product->stock}"])->withInput();
            }
            $total += $details['price'] * $details['quantity'];
        }

        $order_code = 'ORDER-' . strtoupper(Str::random(6)) . time(); // Magge-generate ng unique order code

        try {
            // Gagawin ang Order
            $order = Order::create([
                'user_id' => auth()->id(), // Idinadagdag ang ID ng naka-login na user sa order
                'shop_id' => Shop::first()->id,
                'order_code' => $order_code,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'note' => $request->note,
                'total' => $total,
                'status' => 0, // 0 means pending
            ]);

            // Maghahanda ng data para sa order details
            $orderDetailsData = [];
            foreach ($cart as $id => $details) {
                $orderDetailsData[] = [
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'title' => $details['title'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Ibabawas ang stock ng produkto
                $product = Product::find($id);
                if ($product) {
                    $product->stock -= $details['quantity'];
                    $product->save();
                }
            }

            // Isasalba ang order details
            OrderDetail::insert($orderDetailsData);

            // Lilinisin ang cart mula sa session
            session()->forget('cart');

            // Ibabalik sa success page
            return redirect()->route('clientSuccessOrder')->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            // Kung may error, i-log ito at ibalik sa checkout page
            \Log::error('Order placement failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->withErrors(['checkout_error' => 'There was an error processing your order. Please try again.']);
        }
    }

    /**
     * Ito ang page na ipinapakita pagkatapos matagumpay na makapag-place ng order ang user.
     */
    public function successOrder(){
        $data = [
            'shop' => Shop::first(), // Impormasyon ng shop
            'title' => 'Order Success' // Titulo ng page
        ];
        return view('client.success-order', $data); // Ibabalik ang 'success-order' view
    }

    /**
     * Ipinapakita nito ang "About Us" page ng website.
     */
    public function about(){
        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'title' => 'About' // Ang titulo ng page
        ];

        return view('client.about', $data); // Ibabalik ang 'about' view
    }
}