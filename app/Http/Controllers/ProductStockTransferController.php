<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use App\Product;   
use App\ProductStockTransfer;
use App\Repositories\ProductStockTransferRepository;
use App\Http\Requests\ProductStockTransferStoreRequest;
use Message;

class ProductStockTransferController extends Controller
{
    protected $product_stock_transfer_repo;

    public function __construct(ProductStockTransferRepository $product_stock_transfer_repository)
    {   
        $this->product_stock_transfer_repo = $product_stock_transfer_repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::branches()->pluck('name', 'id');
        $product_stock_transfers = ProductStockTransfer::orderByDesc('id')->where('company_id', auth()->user()->company_id)->paginate(25);
        return view('admin.product_stock_transfers.index', compact('branches', 'product_stock_transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::branches()->pluck('name', 'id');
        $products = Product::with('raw_material')->whereHas('product_stock', function($q){
            $q->where('available_quantity', '>', 0);
        })->products();
        return view('admin.product_stock_transfers.create', compact([
            'branches', 'products'
            ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStockTransferStoreRequest $request)
    {
        $request->validated();

        $product_stock_transfer = $this->product_stock_transfer_repo->store($request);

        if($product_stock_transfer){
            return redirect('product_stock_transfers')->with('message', Message::created('product_stock_transfer'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product_stock_transfer = ProductStockTransfer::find($id);

        return view('admin.product_stock_transfers.show', compact('product_stock_transfer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
