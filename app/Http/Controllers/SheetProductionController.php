<?php

namespace App\Http\Controllers;

use App\Batch;
use App\ChartOfAccount;
use App\Color;
use App\ConfigMaterial;
use App\Http\Requests\SheetProductionStoreRequest;
use App\Http\Requests\SheetProductionUpdateRequest;
use App\RawMaterialStock;
use App\RawMaterialBatchStock;
use App\Repositories\SheetProductionRepository;
use App\SheetProduction;
use App\SheetSize;
use App\Status;
use App\SubRawMaterial;
use App\WastageStock;
use App\FmKutcha;
use App\KutchaWastageStock;
use App\Product;
use App\ProductStock;
use App\TemporarySheetProduction;
use Auth;
use Illuminate\Http\Request;
use Message;
use Carbon\Carbon;
use App\RawMaterial;
use App\TransactionDetails;
use App\Transaction;
use App\DailyProductionDetails;


class SheetProductionController extends Controller
{
    protected $sheetProductionRepo;
    protected $wastage_types;

    public function __construct(SheetProductionRepository $sheetProductionRepository)
    {
        $this->sheetProductionRepo = $sheetProductionRepository;
        $this->wastage_types = config('app.wastage_types');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sheet_productions = $this->sheetProductionRepo->index();
        return view('admin.sheet_productions.index', compact('sheet_productions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ConfigMaterial $config_material)
    {

       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SheetProductionStoreRequest $request)
    {

        $request->validated();

        $sheet_production = $this->sheetProductionRepo->store($request);

        if ($sheet_production == 'success') {
            return \redirect('sheet_productions')->with(['message' => Message::created('sheet_production')]);
        } else {
            return \redirect('sheet_productions')->with(['error_message' => $sheet_production]);
        }
    }

    public function temporary_store(Request $request, SheetProductionStoreRequest $valid)
    {

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SheetProduction  $sheetProduction
     * @return \Illuminate\Http\Response
     */
    public function show(SheetProduction $sheetProduction)
    {
        return view('admin.sheet_productions.show', compact('sheetProduction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SheetProduction  $sheetProduction
     * @return \Illuminate\Http\Response
     */
    public function edit(SheetProduction $sheetProduction)
    {
        $batches = Batch::all();
        $statuses = Status::all();
        $raw_materials = RawMaterial::all();
        $colors = Color::all();
        $sheet_sizes = SheetSize::all();
        return view('admin.sheet_productions.edit', compact([
            'sheetProduction', 'batches', 'statuses', 'raw_materials', 'colors', 'sheet_sizes',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SheetProduction  $sheetProduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SheetProduction $sheetProduction, SheetProductionUpdateRequest $valid)
    {
        $valid->validated();

        $sheet_production = $this->sheetProductionRepo->update($request, $sheetProduction);

        if ($sheet_production == "success") {
            return \redirect('sheet_productions')->with(['message' => Message::updated('sheet_production')]);
        } else {
            return \redirect('sheet_productions')->with('error_message', $sheet_production);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SheetProduction  $sheetProduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(SheetProduction $sheetProduction)
    {
        $sheet_production = $this->sheetProductionRepo->destroy($sheetProduction);

        if ($sheet_production) {
            return \redirect('sheet_productions')->with(['message' => Message::deleted('sheet_production')]);
        }
    }

    public function ajax_material_check(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $batch_id = $request->batch_id;
            $material_stock_exists = RawMaterialBatchStock::where([
                'sub_raw_material_id' => $id,
                'batch_id' => $batch_id
            ]);
            $material_stock = 0;
            if ($material_stock_exists->count() > 0) {
                $material_stock = $material_stock_exists->first()->available_quantity;
            }else{
                if($batch_id == ''){
                    $material_stock_exists = RawMaterialStock::where([
                        'sub_raw_material_id' => $id
                    ]);
                    $material_stock = 0;
                    if ($material_stock_exists->count() > 0) {
                        $material_stock = $material_stock_exists->first()->available_opening_quantity;
                    }                
                }
            }
            return $material_stock;
        }
    }

    public function ajax_fm_kutcha_check(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $fm_kutcha_stock_exists = KutchaWastageStock::where('fm_kutcha_id', $id);
            $fm_kutcha_stock = 0;
            if ($fm_kutcha_stock_exists->count() > 0) {
                $fm_kutcha_stock = $fm_kutcha_stock_exists->first()->available_kg;
            }
            return $fm_kutcha_stock;
        }
    }

    public function ajax_sheet_sizes(Request $request)
    {
        if ($request->ajax()) {
            $raw_material_id = SubRawMaterial::find($request->id)->raw_material_id;
            $sheet_sizes = SheetSize::orderBy('name')->where('raw_material_id', $raw_material_id)->get();

            $options = '';

            foreach ($sheet_sizes as $sheet_size) {
                $options .= '<option value="' . $sheet_size->id . '">' . $sheet_size->raw_material->name . ' - ' . $sheet_size->name . '</option>';
            }
            return $options;
        }
    }

    public function ajax_load_kutchas(Request $request)
    {
        if ($request->ajax()) {
            if ($request->fm_kutcha_id > 0) {
                $raw_material_id = FmKutcha::find($request->fm_kutcha_id)->raw_material_id;
            } else {
                $raw_material_id = SubRawMaterial::find($request->id)->raw_material_id;
            }

            $fm_kutchas = FmKutcha::where('raw_material_id', $raw_material_id)->get()->pluck('raw_material.name', 'id');


            $data['fm_kutcha_dropdown'] = '<option value="0">select kutcha</option>';

            foreach ($fm_kutchas as $kutcha_id => $rm_name) {
                $fm_kutcha = FmKutcha::find($kutcha_id);
                $data['fm_kutcha_dropdown'] .= '<option value="' . $kutcha_id . '">' . $rm_name . ' - ' . $fm_kutcha->name . '</option>';
            }



            $products = Product::where('raw_material_id', $raw_material_id)->get()->pluck('raw_material.name', 'id');

            $data['product_dropdown'] = '<option>select</option>';

            foreach ($products as $product_id => $raw_material_name) {
                $product = Product::find($product_id);
                $data['product_dropdown'] .= '<option value="' . $product_id . '">' . $raw_material_name . ' - ' . $product->name . '</option>';
            }


            return $data;
        }
    }

    public function ajax_load_kutcha_by_product_id(Request $request)
    {
        if ($request->ajax()) {
            $raw_material_id = Product::find($request->id)->raw_material_id;
            $fm_kutchas = FmKutcha::where('raw_material_id', $raw_material_id)->get()->pluck('raw_material.name', 'id');
            $data['fm_kutchas'] = '<option value="0">select</option>';

            foreach ($fm_kutchas as $fm_kutcha_id => $raw_material_name) {
                $fm_kutcha = FmKutcha::find($fm_kutcha_id);
                $data['fm_kutchas'] .= '<option value="' . $fm_kutcha_id . '">' . $fm_kutcha->raw_material_name . ' - ' . $fm_kutcha->name . '</option>';
            }

            return $data;
        }
    }

    public function test()
    {   
        $charts = ChartOfAccount::orderBy('chart_type_id')->get();
        return view('admin.sheet_productions.test', compact('charts'));
    }


    public function pending(){
    }

    public function delete_pending($id){
        $this->sheetProductionRepo->delete_pending($id);
    }

    public function review($id)
    {
        
    }
}
