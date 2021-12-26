<?php

namespace App\Http\Controllers;

use App\AccountInformation;
use App\ChartOfAccount;
use App\JournalVoucher;
use App\Party;
use App\VoucherAccountChart;
use Illuminate\Http\Request;

class JournalVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $journal_vouchers = JournalVoucher::journalVouchers()->get();
        return view('admin.accounting.journal_vouchers.index', compact('journal_vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parties = Party::parties();
        $chart_of_accounts = ChartOfAccount::charts();
        $account_informations = AccountInformation::accountInfo();
        return view('admin.accounting.journal_vouchers.create', compact('parties', 'chart_of_accounts', 'account_informations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(JournalVoucher $journalVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(JournalVoucher $journalVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JournalVoucher $journalVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(JournalVoucher $journalVoucher)
    {
        //
    }
}
