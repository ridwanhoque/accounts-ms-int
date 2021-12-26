<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\Route;
use NumberFormatter;

class FormatHelper{

    public static function code($prefix, $suffix){
        return $prefix.'-'.$suffix;
    }

    public static function title($string){
        return ucfirst(str_replace('_', ' ', $string)); 
    }

    public static function checkActiveUrl($route){
        return (Route::currentRouteName()==$route);
    }

    public static function checkAccountingUrl($url){
        $accounting_urls = [
            'accounting', 'voucher'
        ];
        return in_array($url, $accounting_urls) ? 'is-expanded':'';
    }

    public static function checkAccountingSettingsUrl($url){
        $accounting_settings_urls = 
        [
            'chart-of-account', 
            'account-information', 
            'payment-method', 
            'vendors', 
            'party', 
            'pettycash_charts', 
            'asset_charts', 
            'opening_assets',
            'chart_of_accounts'
        ];

        return in_array($url, $accounting_settings_urls) ? 'is-expanded':'';
    }

    public static function toWords($amount){
        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);

        return ucfirst($formatter->format($amount));
    }

    public static function addComma($amount){
        return number_format($amount, 0, '.', ', ');
        // foreach(count($amount)){

        // }
      //  $decimal = explode('.', $amount, 2)[0];
        //return count($decimal[0]);
    }


    public static function getBangladeshCurrency( $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Taka = implode('', array_reverse($str));
        $poysa = ($decimal) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' poysa' : '';
        return ($Taka ? $Taka . 'taka ' : '') . $poysa ;
    }
}