<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TemporarySheetProduction;
use App\SubRawMaterial;
use App\Batch;
use App\Color;
use App\FmKutcha;
use App\SheetSize;
use App\ConfigMaterial;
use App\Http\Requests\SheetProductionStoreRequest;
use Auth;
use App\RawMaterialStock;
use App\Status;
use App\Repositories\TemporarySheetProductionRepository;
use Message;

class TemporarySheetProductionController extends Controller
{
    protected $tempSheetProductionRepo;

    public function __construct(TemporarySheetProductionRepository $temporarySheetProductionRepository)
    {
        $this->tempSheetProductionRepo = $temporarySheetProductionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $temporary_sheet_productions = TemporarySheetProduction::where('company_id', auth()->user()->company_id)->paginate(25);

        return view('admin.temporary_sheet_productions.index', compact('temporary_sheet_productions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $count = ConfigMaterial::count();

        $currentUser = Auth::user();
        $rm_stock = RawMaterialStock::where('company_id', $currentUser->company_id);
        if ($rm_stock->count() < 1) {
            // return \redirect('purchases')->with('error_message', 'Please purchase product!');
        }
        $batches = Batch::where('company_id', $currentUser->company_id)->pluck('name', 'id');
        $statuses = Status::all();
        $sub_raw_materials = null;
        $sheet_production_materials = ConfigMaterial::where('name', 'App\SheetProduction')->pluck('raw_material_id');
        $sub_raw_material_exist = SubRawMaterial::with('raw_material');
        // ->whereIn('raw_material_id', $sheet_production_materials);
        $sub_raw_material_exist->whereHas('raw_material_stocks', function ($q) {
            $q->where('available_quantity', '>', 0);
        });

        if ($sub_raw_material_exist->count() > 0) {
            $sub_raw_materials = $sub_raw_material_exist->with('raw_material')->get();
        }

        $colors = Color::colors();
        $sheet_sizes = SheetSize::orderByDesc('name')->sheetSizes();

       
        $fm_kutchas = FmKutcha::fmKutchas();

        return view('admin.temporary_sheet_productions.create', compact([
            'batches', 'statuses', 'sub_raw_materials', 'colors', 'sheet_sizes',
            'fm_kutchas'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SheetProductionStoreRequest $valid)
    {
        $valid->validated();

        $sheet_production = $this->tempSheetProductionRepo->store($request);

        if ($sheet_production == 'success') {
            return \redirect('temporary_sheet_productions')->with(['message' => Message::created('sheet_production')]);
        } else {
            return \redirect('temporary_sheet_productions')->with(['error_message' => $sheet_production]);
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
        $temp_sheet_production = TemporarySheetProduction::find($id);
        $temp_materials = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 1);
        $temp_assign_kutchas = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 2);
        $temp_sheets = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 3);
        $temp_kutcha_wastages = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 4);

        return view('admin.temporary_sheet_productions.show', compact([
            'temp_sheet_production', 'temp_materials', 'temp_assign_kutchas', 'temp_sheets', 'temp_kutcha_wastages'
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
        $sub_raw_materials = SubRawMaterial::with('raw_material')->subRawMaterials();
        $batches = Batch::where('company_id', auth()->user()->company_id)->pluck('name', 'id');
        $colors = Color::colors();
        $fm_kutchas = FmKutcha::fmKutchas();
        $sheet_sizes = SheetSize::sheetSizes();
        // dd($sheet_sizes);

        $temp_sheet_production = TemporarySheetProduction::find($id);
        $temp_materials = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 1);
        $temp_assign_kutchas = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 2);
        $temp_sheets = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 3);
        $temp_kutcha_wastages = $temp_sheet_production->temporary_sheet_production_details->where('sheet_type', 4);
        // dd($temp_sheets);


        return view('admin.temporary_sheet_productions.edit', compact([
            'temp_sheet_production', 'sub_raw_materials', 'batches', 'colors', 'fm_kutchas', 'sheet_sizes',
            'temp_materials', 'temp_assign_kutchas', 'temp_sheets', 'temp_kutcha_wastages', 'id'
        ]));
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
        $temp_sheet_production = $this->tempSheetProductionRepo->destroy($id);

        if($temp_sheet_production){
            return redirect()->back()->with('message', Message::deleted('sheet_production_draft'));
        }

    }
}
