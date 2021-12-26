<?php

namespace App\Http\Controllers;

use App\DuePayment;
use Illuminate\Http\Request;
use App\Party;
use App\ChartOfAccount;
use App\AccountInformation;
use App\PaymentMethod;
use App\Voucher;
use App\VoucherPayment;
use App\VoucherAccountChart;
use App\Company;
use App\Repositories\DuePaymentRepository;
use App\Http\Requests\DuePaymentStoreRequest;
use App\Http\Requests\DuePaymentUpdateRequest;
use Auth;
use Message;

class DuePaymentController extends Controller
{

    protected $duePaymentRepo;

    public function __construct(DuePaymentRepository $duePaymentRepository){
        $this->duePaymentRepo = $duePaymentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $voucher = Voucher::findOrFail($request->voucherid);
        $parties = Party::parties();
        $chart_of_accounts = ChartOfAccount::charts();
        $account_informations = AccountInformation::accountInfo();
        $payment_methods = PaymentMethod::methods();

        return \view('admin.accounting.due_payments.create', compact([
            'voucher','parties', 'chart_of_accounts', 'account_informations', 'payment_methods'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DuePaymentStoreRequest $valid)
    {   
        $valid->validated();

        $due_payments = $this->duePaymentRepo->store($request);

        if($due_payments){
            return \redirect('voucher?type=debit')->with('message', Message::created('voucher_payment'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DuePayment  $duePayment
     * @return \Illuminate\Http\Response
     */
    public function show(DuePayment $duePayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DuePayment  $duePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(DuePayment $duePayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DuePayment  $duePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DuePayment $duePayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DuePayment  $duePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $voucher_payment_delete = $this->duePaymentRepo->destroy($request);
        if($voucher_payment_delete){
            return \redirect('voucher?type=debit')->with('message', Message::deleted('voucher_payment'));
        }
    }

    public function voucher_payments($id){
        $voucher_payments = VoucherPayment::where('voucher_id', $id)->paginate(25);

        return view('admin.accounting.due_payments.voucher_payments', compact([
            'voucher_payments'
        ]));
    }

    public function payment_invoice($id){
        $currentUser = Auth::user();
        $company = Company::findOrFail($currentUser->company_id);
        $payment_invoice = VoucherPayment::findOrFail($id);
        

        return view('admin.accounting.due_payments.payment_invoice', compact([
            'payment_invoice', 'company'
        ]));
    }
}
