<?php

namespace App\Http\Controllers;

use App\Sale;
use App\SaleDetails;
use Illuminate\Http\Request;
use App\Party;
use App\Status;
use App\Product;
use App\SheetProductionDetails;
use App\Http\Requests\SaleStoreRequest;
use App\Http\Requests\SaleUpdateRequest;
use App\Repositories\SaleRepository;
use App\ChartOfAccount;
use Auth;
use Message;

class SaleController extends Controller
{
    protected $saleRepo;

    public function __construct(SaleRepository $saleRepository){
        $this->saleRepo = $saleRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::with('party', 'chart_of_account')->orderByDesc('id')->paginate(25);
        return view('admin.sales.index', compact('sales'));
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
        $products = Product::with('raw_material')->where('company_id', $currentUser->company_id)
            ->whereHas('product_stock', function($q){
                $q->where('available_quantity', '>', 0);
            })
            ->orWhereHas('product_branch_stocks', function($qry){
                $qry->where('available_quantity', '>', 0);
            })
            ->get();
        $sheet_production_details = SheetProductionDetails::where('company_id', $currentUser->company_id)->get();

         //for accoutns
         $chart_parties = ChartOfAccount::where([
                'owner_type_id' => config('app.owner_party'),
                'chart_type_id' => config('app.chart_asset')
            ])->pluck('head_name', 'id');
         $sale_chart_id = sales_chart_id();

        return view('admin.sales.create', compact([
            'parties', 'statuses', 'products', 'sheet_production_details', 'chart_parties', 'sale_chart_id'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SaleStoreRequest $valid)
    {
        $valid->validated();

        $sale = $this->saleRepo->store($request);

        if($sale){
            return \redirect('sales')->with('message', Message::created('sale'));
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return view('admin.sales.invoice', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        $currentUser = Auth::user();
        $parties = Party::parties()->where('party_type', 1);
        $statuses = Status::all();
        $products = Product::with('raw_material')->where('company_id', $currentUser->company_id)
            ->whereHas('product_stock', function($q){
                $q->where('available_quantity', '>', 0);
            })
            ->get();
        $sheet_production_details = SheetProductionDetails::where('company_id', $currentUser->company_id)->get();
        
        return view('admin.sales.edit', compact([
            'sale', 'parties', 'statuses', 'products', 'sheet_production_details'
            ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale, SaleUpdateRequest $valid)
    {
        $valid->validated();

        $sales = $this->saleRepo->update($request, $sale);
        
        if($sales){
            return \redirect('sales')->with('message', Message::updated('sale'));
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sales = $this->saleRepo->destroy($sale);

        if($sales){
            return \redirect('sales')->with('message', Message::deleted('sale'));
        }
    }

    public function ajax_invoice_quantity(Request $request){
        return response()->json(['test'=>'1']);
        if($request->ajax())
        {
            $sale_id = $request->id;
            $product_id = $request->product_id;

            $sales = SaleDetails::where([
                'sale_id' => $sale_id,
                'product_id' => $product_id
            ]);
            
            $sale_quantity = $sales->count() > 0 ? $sales->first()->quantity:0;

            return response()->json(['sale_quantity' => $sale_quantity]);

        }
    }




}
