<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;

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