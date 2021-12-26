<?php

namespace App\Http\Controllers;

use App\ChartOfAccount;
use App\ChartType;
use Illuminate\Http\Request;
use App\OwnerType;
use App\Repositories\Accounts\ChartOfAccountRepository;
use phpDocumentor\Reflection\Types\Null_;

class ChartOfAccountController extends Controller
{

    private $coa_repo;


    public function __construct(ChartOfAccountRepository $coa_repo)
    {   
        $this->coa_repo = $coa_repo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chartOfAccounts = ChartOfAccount::orderBy('id','desc')->with('chart_type', 'parent')->get();
        $chart_list = ChartOfAccount::orderByDesc('id')->get(['id', 'head_name', 'tire']);
            
        return view('admin.accounting.chart_of_accounts.index', compact('chartOfAccounts', 'chart_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chart_of_account_types = ChartType::pluck('name', 'id');
        $chart_of_accounts = ChartOfAccount::where('company_id', auth()->user()->company_id)->pluck('head_name', 'id');
        $owner_types = OwnerType::pluck('name', 'id');
        return view('admin.accounting.chart_of_accounts.create', compact([
            'chart_of_account_types', 'chart_of_accounts', 'owner_types'
            ]));
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
            'parent_id' => 'nullable',
            'chart_type_id' => 'required',
            'owner_type_id' => 'required',
            'head_name' => 'required|max:100|unique:chart_of_accounts,head_name',
        ]);


        $chartOfAccount = new ChartOfAccount();
        
        if($request->parent_id > 0){
            $chart_tire = ChartOfAccount::find($request->parent_id);
            $tire = $chart_tire->tire + 1;
         } else{
             $tire = 1;
         }
        $opening_balance = $request->opening_balance > 0 ? $request->opening_balance:0;
        $chartOfAccount->type = $request->type;
        $chartOfAccount->head_name = $request->head_name;
        $chartOfAccount->account_code = $request->account_code;
        $chartOfAccount->status = $request->status;
        $chartOfAccount->parent_id = $request->parent_id;
        $chartOfAccount->tire = $tire;
        $chartOfAccount->opening_balance = $opening_balance;
        $chartOfAccount->is_posting = $request->is_posting;
        $chartOfAccount->chart_type_id = $request->chart_type_id;
        $chartOfAccount->owner_type_id = $request->owner_type_id;
        $chartOfAccount->balance = $opening_balance;
        $chartOfAccount->save();

        if($request->parent_id > 0){
            $this->coa_repo->update_parent_chart($request->parent_id, $opening_balance);
        }

        return redirect()->back()->with('massage','Chart Of Account added successful.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $chart_of_account = ChartOfAccount::find($id);
        return view('admin.accounting.chart_of_accounts.show', compact('chart_of_account'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chart_of_account = ChartOfAccount::find($id);
        $chart_of_account_types = ChartType::pluck('name', 'id');
        $chart_of_accounts = ChartOfAccount::where('company_id', auth()->user()->company_id)->pluck('head_name', 'id');
        $owner_types = OwnerType::pluck('name', 'id');

        return view('admin.accounting.chart_of_accounts.edit', compact(['chart_of_account', 'chart_of_accounts', 'chart_of_account_types', 'owner_types']));
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
            'parent_id' => 'nullable',
            'chart_type_id' => 'required',
            'owner_type_id' => 'required',
            'head_name' => 'required|max:100',
        ]);

        $chartOfAccount = ChartOfAccount::find($id);

        //update reverse chart of account amount
        if($chartOfAccount->opening_balance <= 0){
            $this->coa_repo->adjust_double_entry($chartOfAccount);
        }

        $amount = $chartOfAccount->opening_balance > 0 ? 0:$request->opening_balance;
        if($request->parent_id > 0){
            $chart_tire = ChartOfAccount::find($request->parent_id);
            $tire = $chart_tire->tire + 1;
        }else{
            $tire = 1;
        }        
        $chart_opening = $chartOfAccount->opening_balance > 0 ? $chartOfAccount->opening_balance:$request->opening_balance;
        $chartOfAccount->type = $request->type;
        $chartOfAccount->head_name = $request->head_name;
        // $chartOfAccount->account_code = $request->account_code;
        $chartOfAccount->status = $request->status;
        $chartOfAccount->parent_id = $request->parent_id;
        $chartOfAccount->tire = $tire;
        $chartOfAccount->increment('balance', $amount);
        $chartOfAccount->opening_balance = $chart_opening;
        $chartOfAccount->is_posting = $request->is_posting;
        $chartOfAccount->chart_type_id = $request->chart_type_id;
        $chartOfAccount->owner_type_id = $request->owner_type_id;

        if($request->parent_id > 0){
            $this->coa_repo->update_parent_chart($request->parent_id, $amount);
        }

        $chartOfAccount->save();

        
        return redirect('/chart_of_accounts');
        // return redirect()->back()->with('massage','Chart Of Account Updated successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chartOfAccount = ChartOfAccount::find($id);
        if($chartOfAccount->is_default != 1){
            $chartOfAccount->delete();
            return redirect()->back()->with('massage','Chart Of Account Deleted successful.');    
        }else{
            return redirect()->back()->with('error','You can not delete this chart of account!');    
        }
    }

    // public function ajax_get_charts_bkp(Request $request){
    //     if($request->ajax())
    //     {
    //         $chart_of_account = ChartOfAccount::where('id', $request->parent_id);
            
    //         // $parent_count = $chart_of_account->count() > 0 ? $chart_of_account->count()+1:1;

    //         $tire = $chart_of_account->count() > 0 ? $chart_of_account->first()->tire:0;
    //         $data['tire'] = $request->parent_id != '' ? ($tire + 1) : 1;
    //         $parent_id_db = $chart_of_account->count() >0 ? $chart_of_account->first()->id:'';
    //         $chart_type_id = $chart_of_account->count() > 0 ? $chart_of_account->first()->chart_type_id:$request->chart_type_id;
    //         $max_chart_id = ChartOfAccount::max('id')+1;
    //         $data['account_code'] = $chart_type_id.str_pad($parent_id_db, 4, 0, STR_PAD_LEFT).$max_chart_id;
    //         $data['chart_type_id'] = $chart_of_account->first()->chart_type_id ?? '';

    //         return $data;
    //     }
    // }

    public function ajax_get_charts(Request $request){
        if($request->ajax())
        {
            $chart_of_account = ChartOfAccount::where('id', $request->parent_id);
            $self_charts = ChartOfAccount::where('parent_id', NULL);
            
            $parent_count = $chart_of_account->count() > 0 ? $chart_of_account->count()+1:$self_charts->count()+1;

            $tire = $chart_of_account->count() > 0 ? $chart_of_account->first()->tire:0;
            $data['tire'] = $request->parent_id != '' ? ($tire + 1) : 1;
            $parent_id_db = $chart_of_account->count() >0 ? $chart_of_account->first()->id:'';
            $chart_type_id = $chart_of_account->count() > 0 ? $chart_of_account->first()->chart_type_id:$request->chart_type_id;
            $max_chart_id = ChartOfAccount::max('id')+1;
            $data['account_code'] = $chart_type_id.str_pad($parent_id_db, 4, 0, STR_PAD_LEFT).$parent_count.$max_chart_id;
            $data['chart_type_id'] = $chart_of_account->first()->chart_type_id ?? '';

            return $data;
        }
    }


    
    public function ajax_get_chart_of_account_balance(Request $request){
        if($request->ajax())
        {
            $chart_of_account = ChartOfAccount::find($request->id);
            $data['balance'] = $chart_of_account->balance;

            return response()->json($data);
        }
    }

    


}
