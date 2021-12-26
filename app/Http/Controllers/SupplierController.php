<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use App\Repositories\SupplierRepository;
use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use Message;

class SupplierController extends Controller
{
    protected $supplierRepo;

    public function __construct(SupplierRepository $supplierRepository){
        $this->supplierRepo = $supplierRepository;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = $this->supplierRepo->index();

        return view('admin.suppliers.index', compact(['suppliers']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SupplierStoreRequest $valid)
    {
        $valid->validated();

        $supplier = $this->supplierRepo->store($request);

        if($supplier){
            return \redirect('suppliers')->with(['message' => Message::created('supplier')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier, SupplierUpdateRequest $valid)
    {
        $valid->validated();

        $supplier = $this->supplierRepo->update($request, $supplier);

        if($supplier){
            return \redirect('suppliers')->with(['message' => Message::updated('supplier')]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier = $this->supplierRepo->destroy($supplier);

        if($supplier){
            return redirect('suppliers')->with(['message' => Message::deleted('supplier')]);
        }
    }
}
