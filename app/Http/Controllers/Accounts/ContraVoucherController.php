<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\ContraVoucherStoreRequest;
use App\Repositories\Accounts\ContraVoucherRepository;
use Message;

class ContraVoucherController extends Controller
{

    protected $contraVoucherRepo;

    public function __construct(ContraVoucherRepository $contraVoucherRepository)
    {
        $this->contraVoucherRepo = $contraVoucherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contra_vouchers = $this->contraVoucherRepo->index();
        return view('admin.accounts.contra_vouchers.index', compact('contra_vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->contraVoucherRepo->create();
        return view('admin.accounts.contra_vouchers.create', compact('data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContraVoucherStoreRequest $request)
    {
        $request->validated();

        $contra_vouchers = $this->contraVoucherRepo->store($request);

        if($contra_vouchers){
            return redirect()->back()->withMessage(Message::created('contra_voucher'));
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
        $contra_voucher = $this->contraVoucherRepo->show($id);
        return view('admin.accounts.contra_vouchers.invoice', compact('contra_voucher'));
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
