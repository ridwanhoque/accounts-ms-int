<?php
namespace App\Interfaces;

interface CrudInterface{

    public function index();
    public function create();
    public function store($request);
    public function show($model);
    public function edit($model);
    public function update($request, $model);
    public function destroy($model);    

}