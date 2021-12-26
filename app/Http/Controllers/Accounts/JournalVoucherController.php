<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\JournalVoucherStoreRequest;
use App\Repositories\Accounts\JournalVoucherRepository;
use Message;

class JournalVoucherController extends Controller
{
    protected $JournalVoucherRepo;

    public function __construct(JournalVoucherRepository $JournalVoucherRepository)
    {
        $this->JournalVoucherRepo = $JournalVoucherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $journal_vouchers = $this->JournalVoucherRepo->index();
        return view('admin.accounts.journal_vouchers.index', compact('journal_vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->JournalVoucherRepo->create();
        return view('admin.accounts.journal_vouchers.create', compact('data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JournalVoucherStoreRequest $request)
    {
        $request->validated();

        $journal_vouchers = $this->JournalVoucherRepo->store($request);

        if($journal_vouchers){
            return redirect()->back()->withMessage(Message::created('journal_voucher'));
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
        $journal_voucher = $this->JournalVoucherRepo->show($id);
        return view('admin.accounts.journal_vouchers.invoice', compact('journal_voucher'));
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
