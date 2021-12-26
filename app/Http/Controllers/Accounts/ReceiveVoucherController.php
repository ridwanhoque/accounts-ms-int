<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Accounts\ReceiveVoucherRepository;
use App\Http\Requests\Accounts\ReceiveVoucherStoreRequest;
use Message;

class ReceiveVoucherController extends Controller
{
    protected $receiveVoucherRepo;

    public function __construct(ReceiveVoucherRepository $receiveVoucherRepository)
    {
        $this->receiveVoucherRepo = $receiveVoucherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receive_vouchers = $this->receiveVoucherRepo->index();
        return view('admin.accounts.receive_vouchers.index', compact('receive_vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->receiveVoucherRepo->create();
        return view('admin.accounts.receive_vouchers.create', compact('data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReceiveVoucherStoreRequest $request)
    {
        $request->validated();

        $receive_vouchers = $this->receiveVoucherRepo->store($request);

        if($receive_vouchers){
            return redirect()->back()->withMessage(Message::created('receive_voucher'));
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
        $receive_voucher = $this->receiveVoucherRepo->show($id);
        return view('admin.accounts.receive_vouchers.invoice', compact('receive_voucher'));
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
