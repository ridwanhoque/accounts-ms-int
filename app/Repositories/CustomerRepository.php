<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Customer;

class CustomerRepository implements CrudInterface{

    public function index(){
        return Customer::paginate(25);
    }

    public function create(){

    }

    public function store($request){
        return Customer::create($request->all());
    }

    public function show($customer){

    }

    public function edit($customer){

    }

    public function update($request, $customer){
        return $customer->update($request->all());
    }

    public function destroy($customer){
        return $customer->delete();
    }

}