<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Http\Requests\PurchaseReceiveStoreRequest;
use App\Http\Requests\PurchaseReceiveUpdateRequest;
use App\Party;
use App\Purchase;
use App\PurchaseDetails;
use App\PurchaseReceive;
use App\RawMaterial;
use App\Repositories\PurchaseReceiveRepository;
use App\Status;
use Auth;
use Illuminate\Http\Request;
use Message;

class PurchaseReceiveController extends Controller
{

    protected $purchaseReceiveRepo;

    public function __construct(PurchaseReceiveRepository $purchaseReceiveRepository)
    {
        $this->purchaseReceiveRepo = $purchaseReceiveRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchase_receives = PurchaseReceive::with('purchase.batch','purchase.party')->paginate(25);
        return view('admin.purchase_receives.index', compact('purchase_receives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $purchase_exists = Purchase::where('company_id', $currentUser->company_id);

        // if ($purchase_exists->count() < 1) {
        //     return \redirect('purchases')->with('error_message', 'Please create purchase order raw material!');
        // }

        // $purchases_test = Purchase::where('company_id', $currentUser->company_id)
        //     ->with(['purchase_details' => function($qry){
        //         $qry->groupBy('purchase_id')->selectRaw('sum(quantity) as pd_qty, purchase_id')->having('pd_qty', '>', 
        //             $qry->whereHas('purchase.purchase_receives', function($query){
        //                 $query->groupBy('purchase_id')
        //                 ->whereHas('purchase_receive_details', function($q){
        //                     $q->groupBy('purchase_receive_id')->selectRaw('sum(quantity) as qty, purchase_receive_id');
        //                 });
        //             })
        //         );
        //     }])
        //     ;
        //     dd($purchases_test->get());

        // $purchases = Purchase::where('company_id', $currentUser->company_id)
        //     ->with(['purchase_receives.purchase_receive_details' => function($qry){
        //         $qry->where
        //         ->selectRaw('sum(quantity) as qty, purchase_receive_id')->groupBy('purchase_receive_id');
        //     }])
            // ->with(
            //     ['purchase_details'  => function($q){
            //         $q->selectRaw('sum(quantity) as pd_qty, purchase_id');
            //     }]    
            // )
            // ->having('pd_qty','>=','qty')
            // ->select('purchase_receives.purchase_receive_details.qty > purchase_details.pd_qty')
            // ;
            $purchases = Purchase::where('company_id', $currentUser->company_id)->get();
        // dd($purchases->get()->toArray()->pluck('pd_qty'));
        $statuses = Status::all();
        $raw_materials = RawMaterial::all();
        return view('admin.purchase_receives.create', compact([
            'purchases', 'statuses', 'raw_materials']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PurchaseReceiveStoreRequest $valid)
    {
        $valid->validated();

        $purchase_receives = $this->purchaseReceiveRepo->store($request);

        if ($purchase_receives == 'success') {
            return redirect('purchase_receives')->with('message', Message::created('purchase_receive'));
        } else {
            return redirect('purchase_receives')->with('error_message', $purchase_receives);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseReceive  $purchaseReceive
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseReceive $purchaseReceive, $id)
    {
        $purchase_receive = PurchaseReceive::findOrFail($id);
        return view('admin.purchase_receives.invoice', compact('purchase_receive'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseReceive  $purchaseReceive
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseReceive $purchaseReceive, $id)
    {
        // $purchase_receive = PurchaseReceive::findOrFail($id);
        // $currentUser = Auth::user();
        // $purchases = Purchase::where('company_id', $currentUser->company_id)->get();
        // $parties = Party::parties();
        // return view('admin.purchase_receives.edit', compact([
        //     'purchase_receive', 'purchases', 'parties',
        // ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseReceive  $purchaseReceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseReceive $purchaseReceive, PurchaseReceiveUpdateRequest $valid)
    {
        $valid->validated();

        $purchase_receives = $this->purchaseReceiveRepo->update($request, $purchaseReceive);

        if ($purchase_receives == 'success') {
            return \redirect('purchase_receives')->with('message', Message::updated('purchase_receive'));
        } else {
            return \redirect('purchase_receives')->with('error_message', $purchase_receives);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseReceive  $purchaseReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseReceive $purchaseReceive, $id)
    {
        $purchase_receive_delete = PurchaseReceive::findOrFail($id);
        $purchase_receive = $this->purchaseReceiveRepo->destroy($purchase_receive_delete);

        if ($purchase_receive) {
            return \redirect('purchase_receives')->with('message', Message::deleted('purchase_receive'));
        }
    }

    public function details_ajax(Request $request)
    {

        if ($request->ajax()) 
        {
            $purchase_details = PurchaseDetails::where('purchase_id', $request->id)->get();

            $purchase = Purchase::findOrFail($request->id); 
            $party = $purchase->party_id;
            $party_name = Party::findOrFail($party)->name;

            $tr = "";
            foreach ($purchase_details as $key => $details) {
                $tr .= "<tr><td><input type='hidden' name='sub_raw_material_id[" . $key . "]' value='" . $details->sub_raw_material_id . "'>" . $details->sub_raw_material->raw_material->name.' - '.$details->sub_raw_material->name . "</td><td>" . $details->quantity.' '.config('app.kg'). "</td>";
                $tr .= '<td><input type="number" class="form-control" name="quantity[' . $key . ']" placeholder="Qty" value="' . $details->quantity . '"></td>';
                $tr .= '<td><input type="number" class="form-control" name="quantity_bag[' . $key . ']" placeholder="Qty (Bag)" value="' . $details->quantity_bag . '"></td></tr>';
            }

            $batch_name = Batch::find($purchase->batch_id)->name ?? '-';
            
            $data = [$party_name, $tr, $batch_name];
            // Session(['purchase_details_party' => $party_name, 'purchase_details_tr' => $tr]);
            return $data;

        }

    }
}
