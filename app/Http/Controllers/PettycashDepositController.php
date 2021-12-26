<?php

namespace App\Http\Controllers;

use App\PettycashDeposit;
use Illuminate\Http\Request;
use App\User;
use App\AccountInformation; 
use App\Repositories\PettycashDepositRepository;
use App\Http\Requests\PettycashDepositStoreRequest;
use App\Http\Requests\PettycashDepositUpdateRequest;
use Auth;
use Message;

class PettycashDepositController extends Controller
{

    protected $pettycashDepositRepo;

    function __construct(PettycashDepositRepository $pettycashDepositRepository){
        $this->pettycashDepositRepo = $pettycashDepositRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pettycash_deposits = PettycashDeposit::paginate(25);
        return view('admin.accounting.pettycash_deposits.index', compact('pettycash_deposits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $users = User::where('company_id', $currentUser->company_id)->get();
        $accounts = AccountInformation::where('company_id', $currentUser->company_id)->get();
        return view('admin.accounting.pettycash_deposits.create', compact([
            'users', 'accounts'
            ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PettycashDepositStoreRequest $valid)
    {
        $valid->validated();

        $pettycash_deposits = $this->pettycashDepositRepo->store($request);
        if($pettycash_deposits){
            return \redirect('accounting/pettycash_deposits')->with('message', Message::created('pettycash_deposit'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PettycashDeposit  $pettycashDeposit
     * @return \Illuminate\Http\Response
     */
    public function show(PettycashDeposit $pettycashDeposit)
    {
        return view('admin.accounting.pettycash_deposits.show', compact('pettycashDeposit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PettycashDeposit  $pettycashDeposit
     * @return \Illuminate\Http\Response
     */
    public function edit(PettycashDeposit $pettycashDeposit)
    {
        // $currentUser = Auth::user();
        // $users = User::where('company_id', $currentUser->company_id)->get();
        // $accounts = AccountInformation::where('company_id', $currentUser->company_id)->get();
        // return view('admin.accounting.pettycash_deposits.edit', compact([
        //     'pettycashDeposit', 'users', 'accounts'
        //     ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PettycashDeposit  $pettycashDeposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PettycashDeposit $pettycashDeposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PettycashDeposit  $pettycashDeposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(PettycashDeposit $pettycashDeposit)
    {
        $pettycash_deposits = $this->pettycashDepositRepo->destroy($pettycashDeposit);
        if($pettycash_deposits){
            return \redirect('accounting/pettycash_deposits')->with('message', Message::deleted('pettycash_deposit'));
        }
    }
}
