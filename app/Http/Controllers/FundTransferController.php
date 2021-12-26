<?php

namespace App\Http\Controllers;

use App\FundTransfer;
use Illuminate\Http\Request;
use App\Repositories\FundTransferRepository;
use App\Http\Requests\FundTransferStoreRequest;
use App\Http\Requests\FundTransferUpdateRequest;
use App\AccountInformation;
use App\Transection;
use Auth;
use Message;
use Uploader;

class FundTransferController extends Controller
{

    protected $fundTransferRepo;

    public function __construct(FundTransferRepository $fundTransferRepository){
        $this->fundTransferRepo = $fundTransferRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        $fund_transfers = FundTransfer::where('company_id', $currentUser->company_id)->paginate(25);
        return view('admin.accounting.fund_transfers.index', compact('fund_transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account_information = AccountInformation::AccountInfo();
        return view('admin.accounting.fund_transfers.create', compact([
            'account_information'
            ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FundTransferStoreRequest $valid)
    {
                
        // if($request->has('fund_transfer_image')){
        //     $fund_transfer_image = Uploader::upload_image('images/fund_transfers', $request->file('fund_transfer_image'));
        // }
        $fund_transfers = $this->fundTransferRepo->store($request);
        if($fund_transfers){
            return \redirect('accounting/fund_transfers')->with('message', Message::created('fund_transfer'));
        }

        // if($valid->store()){
        //     return \redirect('accounting/fund_transfers')->with('message', Message::created('fund_transfer'));
        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FundTransfer  $fundTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(FundTransfer $fundTransfer)
    {
        return view('admin.accounting.fund_transfers.show', compact('fundTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FundTransfer  $fundTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(FundTransfer $fundTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FundTransfer  $fundTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FundTransfer $fundTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FundTransfer  $fundTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(FundTransfer $fundTransfer)
    {
        $fund_transfers = $this->fundTransferRepo->destroy($fundTransfer);
        if($fund_transfers){
            return \redirect('accounting/fund_transfers')->with('message', Message::deleted('fund_transfer'));
        }
    }

    public function get_account_balance(Request $request){
        //if($request->ajax()){
            $transection_exists = Transection::where('account_information_id', $request->id);
            if($transection_exists->count() > 0){
                $account_balance = $transection_exists->sum('amount'); 
            }else{
                $account_balance = 0;
            }

            return $account_balance;
        //}
    }
}
