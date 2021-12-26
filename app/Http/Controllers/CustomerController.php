<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Repositories\CustomerRepository;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Message;

class CustomerController extends Controller
{
    protected $customerRepo;

    public function __construct(CustomerRepository $customerRepository){
        $this->customerRepo = $customerRepository;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->customerRepo->index();

        return view('admin.customers.index', compact(['customers']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CustomerStoreRequest $valid)
    {
        $valid->validated();

        $customer = $this->customerRepo->store($request);

        if($customer){
            return \redirect('customers')->with(['message' => Message::created('customer')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer, CustomerUpdateRequest $valid)
    {
        $valid->validated();

        $customer = $this->customerRepo->update($request, $customer);

        if($customer){
            return \redirect('customers')->with(['message' => Message::updated('customer')]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer = $this->customerRepo->destroy($customer);

        if($customer){
            return redirect('customers')->with(['message' => Message::deleted('customer')]);
        }
    }
}
