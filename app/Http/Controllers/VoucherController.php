<?php

namespace App\Http\Controllers;

use App\AccountInformation;
use App\ChartOfAccount;
use App\Company;
use App\Party;
use App\PaymentMethod;
use App\Transection;
use App\Voucher;
use App\VoucherAccountChart;
use App\VoucherChartPayment;
use App\VoucherPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->type == 'debit') {
            $vouchers = Voucher::with('voucher_account_charts', 'voucher_chart_payment', 'voucher_payment', 'party')->where('voucher_type', 'debit')
                ->where('payment_type', 'voucher')->orderBy('id', 'desc')->get();
        } else {
            $vouchers = Voucher::with('voucher_account_charts', 'voucher_chart_payment', 'voucher_payment', 'party')->where('voucher_type', 'credit')
                ->where('payment_type', 'voucher')->orderBy('id', 'desc')->get();
        }

        return view('admin.accounting.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['parties'] = \request()->type == 'credit' ? 
            Party::orderBy('id', 'desc')->where('party_type', 1)->get()
            :Party::orderBy('id', 'desc')->where('party_type', 2)->get()
            ;
        $type = \request()->type == 'credit' ? 'income' : 'expenses';
        $data['chart_of_accounts'] = ChartOfAccount::where('type', $type)->orderBy('id', 'desc')->get();
        $data['account_informations'] = AccountInformation::orderBy('id', 'desc')->get();
        $data['payment_methods'] = PaymentMethod::orderBy('id', 'desc')->get();

        return view('admin.accounting.vouchers.create', $data);
    }

    public function get_payment_method()
    {
        $id = Input::get('id');

        $payment_methods = PaymentMethod::where('account_information_id', $id)->get();
        $data['payment_methods'] = '';
        foreach ($payment_methods as $value) {
            $data['payment_methods'] .= '<option value="' . $value->id . '">' . $value->method_name . '</option>';
        }

        $data['account_balance'] = AccountInformation::find($id)->account_balance;
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'party_id' => 'required',
            'payment_date' => 'required',
            'chart_of_account_id.*' => 'required',
            'payable_amount_unit.*' => 'required|numeric',
            'account_information_id' => 'required',
            'payment_method_id' => 'required',
            'payable_total_amount' => 'required|numeric',
            'due_total_amount' => 'required|numeric',
            'cheque_number' => 'unique:vouchers,cheque_number',
        ], [
            'party_id.required' => 'Party field is required',
            'account_information_id.required' => 'Account Information field is required',
            'payment_method_id.required' => 'Payment Method field is required',
            'chart_of_account_id.*.required' => 'Chart Of Account field is required',
            'payable_amount_unit.*.required' => 'Payable Amount field is required',
            'payable_total_amount.required' => 'Total Payable Amount field is required',
            'paid_total_amount.required' => 'Total Paid Amount field is required',
            'due_total_amount.required' => 'Total Due Amount field is required',
        ]);

        //save data into voucher table
        $voucher = $this->save_voucher($request);

        //save data into voucher payment table
        $voucher_payment = $this->save_voucher_payment($request, $voucher->id);

        //save data into voucher account chart
        foreach ($request->chart_of_account_id as $key => $chart_id) {
            //save data into voucher account chart table
            $voucher_account_chart = $this->save_voucher_account_chart($request, $key, $voucher->id);

            //save data into voucher chart payment table
            $this->save_voucher_chart_payment($request, $key, $voucher->id, $voucher_payment->id, $voucher_account_chart->id);

            if ($request->paid_amount_unit[$key] > 0) {
                //save data into transaction table
                $this->save_transaction($request, $key, $voucher_account_chart);
            }

        }

        if ($request->paid_total_amount > 0) {
            $payment_type = $request->filled('payment_type') ? $request->payment_type: ''; 
            //add or deduct with account information balance
            $this->save_account_information($request->account_information_id, $request->paid_total_amount, $request->voucher_type, $payment_type);
        }

        return redirect()->back()->with('massage', 'Voucher Created Successful');

    }

    public function save_voucher($request)
    {

        $voucher = new Voucher;
        $voucher->party_id = $request->party_id;
        $voucher->voucher_date = $request->payment_date;
        $voucher->voucher_type = $request->voucher_type;
        $voucher->payment_type = $request->payment_type;
        $voucher->voucher_reference = $request->ref_id;
        $voucher->payable_amount = $request->payable_total_amount;
        $voucher->paid_amount = $request->paid_total_amount != null ? $request->paid_total_amount : 0;
        $voucher->due_amount = $request->due_total_amount;
        $voucher->cheque_number = $request->cheque_number;
        $voucher->approved_by = Auth::user()->id;
        $voucher->approved_at = Carbon::now();
        $voucher->save();

        return $voucher;

    }

    public function save_voucher_payment($request, $voucher_id)
    {
        $voucher_payment = new VoucherPayment;
        $voucher_payment->voucher_id = $voucher_id;
        $voucher_payment->account_information_id = $request->account_information_id;
        $voucher_payment->payment_method_id = $request->payment_method_id;
        $voucher_payment->type = 'voucher';
        $voucher_payment->save();

        return $voucher_payment;

    }

    public function save_voucher_account_chart($request, $key, $voucher_id)
    {
        $voucher_account_chart = new VoucherAccountChart;
        $voucher_account_chart->voucher_id = $voucher_id;
        $voucher_account_chart->chart_of_account_id = $request->chart_of_account_id[$key];
        $voucher_account_chart->description = $request->description[$key];
        $voucher_account_chart->payable_amount = $request->payable_amount_unit[$key];
        $voucher_account_chart->paid_amount = $request->paid_amount_unit[$key] != null ? $request->paid_amount_unit[$key] : 0;
        $voucher_account_chart->save();

        return $voucher_account_chart;

    }

    public function save_voucher_chart_payment($request, $key, $voucher_id, $voucher_payment_id, $voucher_account_chart_id)
    {
        $voucher_chart_payment = new VoucherChartPayment;
        $voucher_chart_payment->voucher_id = $voucher_id;
        $voucher_chart_payment->account_information_id = $request->account_information_id;
        $voucher_chart_payment->payment_method_id = $request->payment_method_id;
        $voucher_chart_payment->voucher_payment_id = $voucher_payment_id;
        $voucher_chart_payment->voucher_account_chart_id = $voucher_account_chart_id;
        $voucher_chart_payment->save();

        return $voucher_chart_payment;
    }

    public function save_transaction($request, $key, $voucher_account_chart)
    {

        $transaction_amount = ($request->voucher_type == 'debit') ? $request->paid_amount_unit[$key] * (-1) : $request->paid_amount_unit[$key];
        
        $transaction = new Transection;
        $transaction->account_information_id = $request->account_information_id;
        $transaction->amount = $transaction_amount;
        $transaction->transactionable()->associate($voucher_account_chart);
        $transaction->save();

        return $transaction;
    }

    public function save_account_information($account_information_id, $amount, $voucher_type, $payment_type)
    {
        $amount = $voucher_type == ('debit' && $payment_type!='advance') ? $amount*(-1):$amount;
        $account_information = AccountInformation::find($account_information_id);
        $account_information->account_balance += $amount;
        $account_information->save();

        return $account_information;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        $data['voucher'] = Voucher::with('party', 'user', 'voucher_payment')->findOrFail($id);
        $data['company'] = Company::where('id', Auth::user()->company_id)->first();

        return view('admin.accounting.vouchers.print-voucher', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data['parties'] = Party::orderBy('id', 'desc')->get();
        $data['chart_of_accounts'] = ChartOfAccount::orderBy('id', 'desc')->get();
        $data['account_informations'] = AccountInformation::orderBy('id', 'desc')->get();
        $data['payment_methods'] = PaymentMethod::orderBy('id', 'desc')->get();
        $data['voucher'] = Voucher::findOrFail($id);

        return view('admin.accounting.vouchers.edit', $data);
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
        $voucher = Voucher::findOrFail($id);
        VoucherAccountChart::where('voucher_id', $id)->delete();
        VoucherChartPayment::where('voucher_id', $id)->delete();
        VoucherPayment::where('voucher_id', $id)->delete();
        Transection::where('transactionable_id', $id)->delete();
        $voucher->delete();

        return redirect()->back()->with('massage', 'Voucher Delete is successful.');

    }

    public static function convert_number_to_words($number)
    {

        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion',
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

}
