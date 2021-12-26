<?php

namespace App\Http\Controllers;

use App\Http\Requests\LedgerStoreRequest;
use App\Ledger;
use Illuminate\Http\Request;
use App\Repositories\LedgerRepository;
use Message;

class LedgerController extends Controller
{

    protected $ledgerRepo;

    public function __construct(LedgerRepository $ledgerRepository)
    {   
        $this->ledgerRepo = $ledgerRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ledgers = Ledger::ledgers()->paginate(25);
        return view('admin.accounting.ledgers.index', compact('ledgers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->ledgerRepo->create();
        return view('admin.accounting.ledgers.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, LedgerStoreRequest $valid)
    {
        
        $ledgers = $this->ledgerRepo->store($request);

        if($ledgers){
            return redirect()->back()->with('message', Message::created('ledger'));
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
