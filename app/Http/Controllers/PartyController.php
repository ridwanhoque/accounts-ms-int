<?php

namespace App\Http\Controllers;

use App\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parties = Party::orderBy('id','desc')->where('party_type', 2)->get();
        return view('admin.accounting.party.index',compact('parties'));
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

           'name' => 'required|max:100'
        //    'email' => 'required|email|unique:parties,email',
        //    'phone' => 'required|regex:/(01)[0-9]{9}/|size:11|unique:parties,phone',
        //    'contact_person_mobile' => 'nullable|regex: /(01)[0-9]{9}/|size:11'

       ]);

       $party = Party::create($request->all());

       return redirect()->back()->with('massage','Party added successful.');
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

            'name' => 'required|max:100'
            // 'email' => 'required|email|unique:parties,email,'.$id,
            // 'phone' => 'required|regex:/(01)[0-9]{9}/|size:11|unique:parties,phone,'.$id,
            // 'contact_person_mobile' => 'nullable|regex: /(01)[0-9]{9}/|size:11'

        ]);

        $party = Party::find($id);
        $party->update($request->all());

        return redirect()->back()->with('massage','Party Updated successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $party = Party::find($id);
        $party->delete();
        return redirect()->back()->with('massage','Party Delete successful.');
    }
}
