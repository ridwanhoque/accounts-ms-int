<?php

namespace App\Http\Controllers;

use App\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['companies'] = Company::orderBy('id','desc')->paginate(25);
       return view('admin.companies.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.companies.create');

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
            'name' => 'required|unique:companies,name',
            'phone' => 'required|unique:companies,phone|numeric',
            'email' => 'required|unique:companies,email',
            'website' => 'unique:companies,website',
            'company_logo' => 'required|image|max:1024',

        ]);

        $image = $request->file('company_logo');
        $slug  = str_slug($request->name,'_');

        if (isset($image)){

            $currentDate = Carbon::now()->toDateString();
            $imageName   = $slug.'_'.$currentDate.'_'.uniqid().'.'.$image->getClientOriginalExtension();

            if (!file_exists('uploads/company')){
                mkdir('uploads/company',0777,true);
            }

            $image->move('uploads/company',$imageName);

        }else{

            $imageName = 'default.jpg';

        }

        $company = new Company();
        $company->name = $request->name;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->address = $request->address;
        $company->company_logo = $imageName;
        $company->created_at = Carbon::now();
        $company->updated_at = Carbon::now();
        $company->save();

        return redirect()->route('companies.index')->with('massage','Company Added Successfull !');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $company = Company::find($company->id);
        return view('admin.companies.show',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $company = Company::find($company->id);
        return view('admin.companies.edit',compact('company'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $this->validate($request,[
            'name' => 'unique:companies,name,'.$company->id,
            'phone' => 'numeric|unique:companies,phone,'.$company->id,
            'email' => 'unique:companies,email,'.$company->id,
            'website' => 'unique:companies,website,'.$company->id,
        ]);

        $image = $request->file('company_logo');
        $slug  = str_slug($request->name,'_');
        $company = Company::find($company->id);

        if (isset($image)){

            $currentDate = Carbon::now()->toDateString();
            $imageName   = $slug.'_'.$currentDate.'_'.uniqid().'.'.$image->getClientOriginalExtension();

            if (!file_exists('uploads/company')){
                mkdir('uploads/company',0777,true);
            }

            if ($company->company_logo != 'default.jpg'){
                unlink('uploads/company/'.$company->company_logo);
            }

            $image->move('uploads/company',$imageName);

        }else{

            $imageName = $company->company_logo;

        }

        $company->name = $request->name;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->address = $request->address;
        $company->company_logo = $imageName;
        $company->updated_at = Carbon::now();
        $company->save();

        return redirect()->route('companies.index')->with('massage','Company Updated Successfull !');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company = Company::find($company->id);
        if ($company->company_logo != 'default.jpg'){
            unlink('uploads/company/'.$company->company_logo);
        }
        $company->delete();

        return redirect()->route('companies.index')->with('massage','Company Deleted Successfull !');

    }
}
