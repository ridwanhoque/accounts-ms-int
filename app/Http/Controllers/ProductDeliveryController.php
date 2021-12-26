<?php

namespace App\Http\Controllers;

use App\Branch;
use App\DailyProductionDetail;
use App\Http\Requests\ProductDeliveryStoreRequest;
use App\Http\Requests\ProductDeliveryUpdateRequest;
use App\Party;
use App\Product;
use App\ProductDelivery;
use App\Repositories\ProductDeliveryRepository;
use App\Sale;
use App\SaleDetails;
use App\Status;
use App\ProductDeliveryDetails;
use App\ProductStock;
use Auth;
use Illuminate\Http\Request;
use Message;

class ProductDeliveryController extends Controller
{
    protected $productDeliveryRepo;

    public function __construct(ProductDeliveryRepository $productDeliveryRepository)
    {
        $this->productDeliveryRepo = $productDeliveryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_deliveries = ProductDelivery::orderByDesc('id')->get();
        return view('admin.product_deliveries.index', compact('product_deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $parties = Party::parties()->where('party_type', 1);
        $statuses = Status::all();
        $products = Product::where('company_id', $currentUser->company_id)
            // ->whereHas('product_stock', function ($q) {
            //     $q->where('available_quantity', '>', 0);
            // })
            ->whereHas('sale_details')
            ->get();
        $daily_production_details = DailyProductionDetail::where('company_id', $currentUser->company_id)->get();
        $sales = Sale::sales();

        $branches = Branch::branches()->pluck('name', 'id');
        return view('admin.product_deliveries.create', compact([
            'parties', 'statuses', 'products', 'daily_production_details', 'sales', 'branches'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProductDeliveryStoreRequest $valid)
    {
        $valid->validated();

        $product_deliveries = $this->productDeliveryRepo->store($request);

        if ($product_deliveries == 'success') {
            return \redirect('product_deliveries')->with('message', Message::created('product_delivery'));
        } else {
            return \redirect('product_deliveries')->with('error_message', $product_deliveries);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductDelivery  $productDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDelivery $productDelivery)
    {
        return view('admin.product_deliveries.show', compact('productDelivery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductDelivery  $productDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductDelivery $productDelivery)
    {
        $currentUser = Auth::user();
        $parties = Party::parties();
        $statuses = Status::all();
        $products = Product::where('company_id', $currentUser->company_id)
            ->whereHas('product_stock', function ($q) {
                $q->where('available_quantity', '>', 0);
            })
            ->get();
        $daily_production_details = DailyProductionDetail::where('company_id', $currentUser->company_id)->get();
        $sales = Sale::sales();
        return view('admin.product_deliveries.edit', compact([
            'productDelivery', 'parties', 'statuses', 'products', 'daily_production_details', 'sales',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductDelivery  $productDelivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductDelivery $productDelivery, ProductDeliveryUpdateRequest $valid)
    {
        $valid->validated();

        $product_deliveries = $this->productDeliveryRepo->update($request, $productDelivery);

        if ($product_deliveries == 'success') {
            return \redirect('product_deliveries')->with('message', Message::updated('product_delivery'));
        } else {
            return \redirect('product_deliveries')->with('error_message', $product_deliveries);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductDelivery  $productDelivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDelivery $productDelivery)
    {

        $product_deliveries = $this->productDeliveryRepo->destroy($productDelivery);

        if ($product_deliveries) {
            return redirect('product_deliveries')->with('message', Message::deleted('product_delivery'));
        }
    }

    public function ajax_invoice_by_product(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sale::whereHas('sale_details', function ($q) use ($request) {
                $q->where('product_id', $request->id);
            })->pluck('sale_reference', 'id');

            $sale_dropdown = '<option value="0">select</option>';
            foreach ($sales as $key => $sale) {
                $sale_dropdown .= '<option value="' . $key . '">' . $sale . '</option>';
            }
            return $sale_dropdown;
        }
    }

    public function ajax_sale_invoice_qty(Request $request)
    {
        if ($request->ajax()) {
            $sale_invoice = SaleDetails::where([
                'sale_id' => $request->id,
                'product_id' => $request->product_id
            ])->first();
            $data['sale_invoice_qty'] = $sale_invoice->quantity;
            $data['sale_invoice_pack'] = $sale_invoice->pack;
            $data['sale_invoice_weight'] = $sale_invoice->weight;

            $data['product_stock_pack'] = ProductStock::where([
                'product_id' => $request->product_id
            ])->first()->available_pack;
            
            $data['product_stock_weight'] = ProductStock::where([
                'product_id' => $request->product_id
            ])->first()->available_weight;
            

            $data['already_delivered'] = ProductDeliveryDetails::where([
                'sale_id' => $request->id,
                'product_id' => $request->product_id
            ])->sum('quantity');

            $data['delivered_pack'] = ProductDeliveryDetails::where([
                'sale_id' => $request->id,
                'product_id' => $request->product_id
            ])->sum('pack');

            $data['delivered_weight'] = ProductDeliveryDetails::where([
                'sale_id' => $request->id,
                'product_id' => $request->product_id
            ])->sum('weight');


            return $data;
        }
    }
}
