<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ConfigMaterial;
use App\Batch;
use App\Product;
use App\Machine;
use App\Status;
use App\SubRawMaterial;
use App\SheetSize;
use App\Color;
use App\FmKutcha;
use App\Http\Requests\DirectProductionStoreRequest;
use App\RawMaterial;
use App\SheetSizeColor;
use App\Repositories\TemporaryDirectProductionRepository;
use App\Sheet;
use App\TemporaryDirectProduction;
use Message;
use App\TemporaryDirectProductionDetails;


class TemporaryDirectProductionController extends Controller
{
    protected $temporaryDirectProductionRepo;

    public function __construct(TemporaryDirectProductionRepository $temporaryDirectProductionRepository)
    {   
        $this->temporaryDirectProductionRepo = $temporaryDirectProductionRepository;    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        $temporary_direct_productions = TemporaryDirectProduction::where('company_id', $currentUser->company_id)
            ->paginate(25);
            
        return view('admin.temporary_direct_productions.index', compact(['temporary_direct_productions']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $direct_production_materials = ConfigMaterial::where('name', 'App\DirectProduction')->pluck('raw_material_id');
        $batches = Batch::where('company_id', $currentUser->company_id)->pluck('name', 'id');
        $products = Product::with('raw_material')->products()->whereIn('raw_material_id', $direct_production_materials);
        $machines = Machine::where('company_id', $currentUser->company_id)->pluck('name', 'id');
        $statuses = Status::all();

        $sub_raw_materials = null;
        $sub_raw_material_exist = SubRawMaterial::with('raw_material')->whereHas('raw_material_stocks', function ($q) {
            $q->where('available_quantity', '>', 0);
        });
        // ->whereIn('raw_material_id', $direct_production_materials);

        if ($sub_raw_material_exist->count() > 0) {
            $sub_raw_materials = $sub_raw_material_exist->get();
        }

        $sheet_sizes = SheetSize::sheetSizes();
        $colors = Color::colors();
        // $fm_kutchas = FmKutcha::whereHas('kutcha_wastage_stock', function($q){
        //     $q->where('available_kg', '>', 0);
        // })->get();

        $fm_kutchas = FmKutcha::with('raw_material')->get(['id', 'name', 'raw_material_id']);

        $sheet_size_color_materials = SheetSizeColor::whereHas('sheetproductiondetails_stocks', function($q){
            $q->where('available_quantity_kg', '>', 0)
              ->where('available_quantity_roll', '>', 0);
        })->get();

        
        return view('admin.temporary_direct_productions.create', compact([
            'batches', 'statuses', 'sub_raw_materials', 'sheet_sizes', 'colors', 'fm_kutchas', 'sheet_size_color_materials', 'products', 'machines'
            ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DirectProductionStoreRequest $request)
    {
        $request->validated();

        $directProduction = $this->temporaryDirectProductionRepo->store($request);

        if($directProduction){
            return \redirect('temporary_direct_productions')->with(['message' => Message::created('direct_production')]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $temp_direct_production = TemporaryDirectProduction::findOrFail($id);
        $temp_materials = $temp_direct_production->temporary_direct_production_details->where('production_type', 1);
        $temp_assign_kutchas = $temp_direct_production->temporary_direct_production_details->where('production_type', 2);
        $temp_productions = $temp_direct_production->temporary_direct_production_details->where('production_type', 3);

        // dd($temp_materials);
        
        return view('admin.temporary_direct_productions.show', compact([
            'temp_direct_production', 'temp_materials', 'temp_assign_kutchas', 'temp_productions'
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $temporary_direct_production = TemporaryDirectProduction::find($id);
        $currentUser = auth()->user();
        $direct_production_materials = ConfigMaterial::where('name', 'App\DirectProduction')->pluck('raw_material_id');
        $batches = Batch::where('company_id', $currentUser->company_id)->pluck('name', 'id');
        $products = Product::with('raw_material')->products()->whereIn('raw_material_id', $direct_production_materials);
        $machines = Machine::where('company_id', $currentUser->company_id)->pluck('name', 'id');
        $statuses = Status::all();

        $sub_raw_materials = null;
        $sub_raw_material_exist = SubRawMaterial::with('raw_material')->whereHas('raw_material_stocks', function ($q) {
            $q->where('available_quantity', '>', 0);
        });
        // ->whereIn('raw_material_id', $direct_production_materials);
        // $fm_kutchas = FmKutcha::whereHas('kutcha_wastage_stock', function($q){
        //     $q->where('available_kg', '>', 0);
        // })->get();


        $fm_kutchas = FmKutcha::with('raw_material')->get(['id', 'name', 'raw_material_id']);

        if ($sub_raw_material_exist->count() > 0) {
            $sub_raw_materials = $sub_raw_material_exist->get();
        }

        $assign_materials = $temporary_direct_production->temporary_direct_production_details()->where('production_type', 1)->get();
        $assign_kutchas = $temporary_direct_production->temporary_direct_production_details()->where('production_type', 2)->get();
        $productions = $temporary_direct_production->temporary_direct_production_details()->where('production_type', 3)->get();

        return \view('admin.temporary_direct_productions.edit', compact('batches', 'products', 'machines', 'sub_raw_materials', 'fm_kutchas',
                'temporary_direct_production', 'assign_materials', 'assign_kutchas', 'productions'));
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
        $temporary_direct_production = $this->temporaryDirectProductionRepo->destroy($id);

        if($temporary_direct_production){
            return redirect()->back()->with('message', Message::deleted('temporary_direct_production'));
        }
    }
}