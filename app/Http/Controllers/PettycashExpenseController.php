<?php

namespace App\Http\Controllers;

use App\PettycashExpense;
use Illuminate\Http\Request;
use App\PettycashChart;
use App\Http\Requests\PettycashExpenseStoreRequest;
use App\Http\Requests\PettycashExpenseUpdateRequest;
use App\Repositories\PettycashExpenseRepository;
use Auth;
use Message;

class PettycashExpenseController extends Controller
{

    protected $pettycashExpenseRepo;

    public function __construct(PettycashExpenseRepository $pettycashExpenseRepository){
        $this->pettycashExpenseRepo = $pettycashExpenseRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        $pettycash_expenses = PettycashExpense::where('company_id', $currentUser->company_id)->paginate(25);
        return view('admin.accounting.pettycash_expenses.index', compact('pettycash_expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $pettycash_charts = PettycashChart::where('company_id', $currentUser->company_id)->get();
        return view('admin.accounting.pettycash_expenses.create', compact('pettycash_charts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PettycashExpenseStoreRequest $valid)
    {
        $valid->validated();

        $pettycash_expenses = $this->pettycashExpenseRepo->store($request);

        if($pettycash_expenses == 'success'){
            return \redirect('accounting/pettycash_expenses')->with('message', Message::created('pettycash_expense'));
        }else{
            return \redirect('accounting/pettycash_expenses')->with('error_message', $pettycash_expenses);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PettycashExpense  $pettycashExpense
     * @return \Illuminate\Http\Response
     */
    public function show(PettycashExpense $pettycashExpense)
    {
        return view('admin.accounting.pettycash_expenses.invoice', compact('pettycashExpense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PettycashExpense  $pettycashExpense
     * @return \Illuminate\Http\Response
     */
    public function edit(PettycashExpense $pettycashExpense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PettycashExpense  $pettycashExpense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PettycashExpense $pettycashExpense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PettycashExpense  $pettycashExpense
     * @return \Illuminate\Http\Response
     */
    public function destroy(PettycashExpense $pettycashExpense)
    {
        $pettycash_expenses = $this->pettycashExpenseRepo->destroy($pettycashExpense);

        if($pettycash_expenses){
            return \redirect('accounting/pettycash_expenses')->with('message', Message::deleted('pettycash_expense'));
        }
    }
}
