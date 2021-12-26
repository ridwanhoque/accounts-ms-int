<?php
namespace App\Http\Helpers;

class MessageHelper{

    public static function created($message){
        return \ucfirst(str_replace('_', ' ', $message)).' created successfully!';
    }

    public static function updated($message){
        return \ucfirst(str_replace('_', ' ', $message)).' updated successfully!';
    }

    public static function deleted($message){
        return \ucfirst(str_replace('_', ' ', $message)).' deleted successfully!';
    }
}