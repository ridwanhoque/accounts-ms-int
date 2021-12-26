<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Color;
use App\RawMaterial;
use App\Machine;
use App\ProductStock;

class ProductController extends Controller
{

    protected $productRepo;

    public function __construct(ProductRepository $productRepo){
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productRepo->index();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raw_materials = RawMaterial::RawMaterials();
        $machines = Machine::machines();
        $colors = Color::colors()->whereNotIn('id', [2]);
        return view('admin.products.create', compact([
            'raw_materials', 'machines', 'colors']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProductStoreRequest $valid)
    {

        $validated = $valid->validated();
        $product = $this->productRepo->store($request);

        if($product){
            return \redirect('production_settings/products')->with(['message' => 'product created successfully!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $raw_materials = RawMaterial::RawMaterials();
        $machines = Machine::machines();
        $colors = Color::colors();
        return view('admin.products.edit', compact([
            'product', 'raw_materials', 'machines', 'colors'
            ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, ProductUpdateRequest $valid)
    {
        $validated = $valid->validated();

        $product = $this->productRepo->update($request, $product);

        if($product){
            return \redirect('production_settings/products')->with(['message' => 'product updated success!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product = $this->productRepo->destroy($product);

        if($product){
            return \redirect('production_settings/products')->with(['message' => 'product delete success']);
        }
    }

    public function ajax_products(){
        return response()->ajax(Product::products());
    }
}
