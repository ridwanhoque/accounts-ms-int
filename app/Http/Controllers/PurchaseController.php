<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Http\Request;
use App\Repositories\PurchaseRepository;
use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\PurchaseUpdateRequest;
use App\Status;
use App\Party;
use App\RawMaterial;
use App\Company;
use App\Batch;
use App\ChartOfAccount;
use App\PurchaseReceive;
use Auth;
use Carbon\Carbon;

use Message;

class PurchaseController extends Controller
{

    protected $purchaseRepo;

    public function __construct(PurchaseRepository $purchaseRepository){
        $this->purchaseRepo = $purchaseRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = $this->purchaseRepo->index();

        return view('admin.purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $account_linked = Company::findOrFail($currentUser->company_id)->account_linked;
        $batches = Batch::orderBYDesc('id')->where('company_id', auth()->user()->company_id)->pluck('name', 'id');
        $parties = Party::parties()->where('party_type', 2);
        $statuses = Status::all();
        $raw_materials = RawMaterial::where('company_id', $currentUser->company_id)->get();
        
        //for accoutns
        $chart_parties = ChartOfAccount::where([
                'owner_type_id' => config('app.owner_party'),
                'chart_type_id' => config('app.chart_liability')
            ])->pluck('head_name', 'id');
        $purchase_chart_id = inventories_chart_id();

        return view('admin.purchases.create', compact([
            'batches', 'parties', 'statuses', 'raw_materials', 'account_linked', 'chart_parties', 'purchase_chart_id'
            ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PurchaseStoreRequest $valid)
    {
        
        $valid->validated();

        $purchase = $this->purchaseRepo->store($request);

        if($purchase){
            return \redirect('purchases')->with(['message' => Message::created('purchase')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        return view('admin.purchases.invoice', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        if($purchase->status_id == 1){
            return \redirect('purchases')->with(['error_message' => 'You can not edit!']);
        }
        $currentUser = Auth::user();
        $parties = Party::parties()->where('party_type', 2);
        $statuses = Status::all();
        $raw_materials = RawMaterial::where('company_id', $currentUser->company_id)->get();
        return view('admin.purchases.edit', compact([
            'purchase', 'parties', 'statuses', 'raw_materials'
            ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase, PurchaseUpdateRequest $valid)
    {
        $valid->validated();

        $purchase = $this->purchaseRepo->update($request, $purchase);

        if($purchase){
            return redirect('purchases')->with(['message' => Message::updated('purchase')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase = $this->purchaseRepo->destroy($purchase);

        if($purchase['error_message']){
            return \redirect('purchases')->with(['error_message' => $purchase['error_message']]);
        }else{
            return redirect('purchases')->with(['message' => Message::deleted('purchase')]);
        }
    }
}
