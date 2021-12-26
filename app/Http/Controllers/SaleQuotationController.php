<?php

namespace App\Http\Controllers;

use App\SaleQuotation;
use App\SaleQuotationDetail;
use Illuminate\Http\Request;
use Auth;
use App\Party;
use App\Product;
use App\Company;
use App\Repositories\SaleQuotationRepository;
use App\Http\Requests\SaleQuotationStoreRequest;
use App\Http\Requests\SaleQuotationUpdateRequest;
use Message;

class SaleQuotationController extends Controller
{
    protected $saleQuotationRepo;

    public function __construct(SaleQuotationRepository $saleQuotationRepository){
        $this->saleQuotationRepo = $saleQuotationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sale_quotations = SaleQuotation::saleQuotations()->paginate(25);
        return view('admin.sale_quotations.index', compact('sale_quotations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $parties = Party::parties();
        $products = Product::products();
        $account_linked = Company::find($currentUser->company_id)->account_linked;
        return view('admin.sale_quotations.create', compact([
            'parties', 'products', 'account_linked'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SaleQuotationStoreRequest $valid)
    {
        $valid->validated();

        $sale_quotations = $this->saleQuotationRepo->store($request);

        if($sale_quotations){
            return redirect('sale_quotations')->with('message', Message::created('sale_quotation'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleQuotation  $saleQuotation
     * @return \Illuminate\Http\Response
     */
    public function show(SaleQuotation $saleQuotation)
    {
        return view('admin.sale_quotations.invoice', compact('saleQuotation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleQuotation  $saleQuotation
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleQuotation $saleQuotation)
    {
        $currentUser = Auth::user();
        $parties = Party::parties();
        $products = Product::products();
        $account_linked = Company::find($currentUser->company_id)->account_linked;

        return view('admin.sale_quotations.edit', compact([
            'saleQuotation', 'parties', 'products', 'account_linked'
            ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleQuotation  $saleQuotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleQuotation $saleQuotation, SaleQuotationUpdateRequest $valid)
    {   
        $valid->validated();

        $sale_quotations = $this->saleQuotationRepo->update($request, $saleQuotation);

        if($sale_quotations){
            return \redirect('sale_quotations')->with('message', Message::updated('sale_quotation'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleQuotation  $saleQuotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleQuotation $saleQuotation)
    {
        $sale_quotations = $this->saleQuotationRepo->destroy($saleQuotation);

        if($sale_quotations){
            return \redirect('sale_quotations')->with('message', Message::deleted('sale_quotation'));
        }
    }
}
