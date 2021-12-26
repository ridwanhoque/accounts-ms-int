<?php
namespace App\Interfaces;

Interface ReportInterface{

    public function report();
    public function filter($request);
}