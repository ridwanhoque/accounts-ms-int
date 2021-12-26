<?php

namespace App\Http\Controllers;

use App\AccountInformation;
use App\PaymentMethod;
use Illuminate\Http\Request;

class AccountInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountInfos = AccountInformation::orderBy('id','desc')->get();
        return view('admin.accounting.account-info.index',compact('accountInfos'));
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
            'account_name' => 'required|unique:account_information,account_name|max:100',
            'account_no' => 'required|unique:account_information,account_no|max:100',
            'branch_name' => 'required',
        ]);

        $accountInformation = new AccountInformation();
        $accountInformation->account_name = $request->account_name;
        $accountInformation->account_no = $request->account_no;
        $accountInformation->branch_name = $request->branch_name;
        $accountInformation->status = $request->status;
        $accountInformation->save();

        return redirect()->back()->with('massage','Account Information added successful');

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
            'account_name' => 'required|unique:account_information,account_name,'.$id.'|max:100',
            'account_no' => 'required|unique:account_information,account_no,'.$id.'|max:100',
            'branch_name' => 'required',
        ]);

        $accountInformation = AccountInformation::find($id);
        $accountInformation->account_name = $request->account_name;
        $accountInformation->account_no = $request->account_no;
        $accountInformation->branch_name = $request->branch_name;
        $accountInformation->status = $request->status;
        $accountInformation->save();

        return redirect()->back()->with('massage','Account Information Updated successful');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (PaymentMethod::where('account_information_id',$id)->first()){
            return redirect()->back()->with('error','This Information is already use in Payment Method, You can not delete it !');
        }
        $accountInformation = AccountInformation::find($id);
        $accountInformation->delete();

        return redirect()->back()->with('massage','Account Information Deleted successful.');
    }
}
