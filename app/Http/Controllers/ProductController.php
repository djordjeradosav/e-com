<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(): Response
    {
        $products = Product::orderBy('name')->get();
        
        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }
}