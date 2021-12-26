<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\PaymentVoucherStoreRequest;
use App\Repositories\Accounts\PaymentVoucherRepository;
use Illuminate\Http\Request;
use Message;

class PaymentVoucherController extends Controller
{

    protected $paymentVoucherRepo;

    public function __construct(PaymentVoucherRepository $paymentVoucherRepository)
    {
        $this->paymentVoucherRepo = $paymentVoucherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_vouchers = $this->paymentVoucherRepo->index();
        return view('admin.accounts.payment_vouchers.index', compact('payment_vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->paymentVoucherRepo->create();
        return view('admin.accounts.payment_vouchers.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentVoucherStoreRequest $request)
    {
        $request->validated();

        $payment_vouchers = $this->paymentVoucherRepo->store($request);

        if ($payment_vouchers) {
            return redirect()->back()->withMessage(Message::created('payment_voucher'));
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
        $payment_voucher = $this->paymentVoucherRepo->show($id);
        return view('admin.accounts.payment_vouchers.invoice', compact('payment_voucher'));
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
