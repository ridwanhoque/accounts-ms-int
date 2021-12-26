<?php

namespace App\Http\Controllers;

use App\AccountInformation;
use App\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['accountInfos'] = AccountInformation::all();
        $data['paymentMethods'] = PaymentMethod::with('account_information')->orderBy('id','desc')->get();

        return view('admin.accounting.payment-method.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'account_information_id' => 'required',
            'method_name' => 'required|max:100|unique:payment_methods,method_name',
        ]);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->account_information_id = $request->account_information_id;
        $paymentMethod->method_name = $request->method_name;
        $paymentMethod->status = $request->status;
        $paymentMethod->save();

        return redirect()->back()->with('massage','Payment Method added Successful.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->validate($request,[
            'account_information_id' => 'required',
            'method_name' => 'required|max:100|unique:payment_methods,method_name,'.$id,
        ]);

        $paymentMethod = PaymentMethod::find($id);
        $paymentMethod->account_information_id = $request->account_information_id;
        $paymentMethod->method_name = $request->method_name;
        $paymentMethod->status = $request->status;
        $paymentMethod->save();

        return redirect()->back()->with('massage','Payment Method Updated Successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
        return redirect()->back()->with('massage','Payment Method Deleted Successful.');
    }

    public function ajax_get_payment_method(Request $request){
        if($request->ajax())
        {
            $payment_methods = PaymentMethod::where('account_information_id', $request->id)->pluck('method_name', 'id');

            $data['payment_method_dropdown'] = '';

            foreach($payment_methods as $id => $method_name){
                $data['payment_method_dropdown'] .= '<option value="'.$id.'">'.$method_name.'</option>';
            }

            return response()->json($data);    
        }
    }

}
