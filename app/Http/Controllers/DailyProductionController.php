<?php

namespace App\Http\Controllers;

use App\Color;
use App\DailyProduction;
use App\DailyProductionDetail;
use App\Http\Requests\DailyProductionStoreRequest;
use App\Http\Requests\DailyProductionUpdateRequest;
use App\Machine;
use App\Product;
use App\Repositories\DailyProductionRepository;
use App\SheetProductionDetails;
use App\SheetproductiondetailsColor;
use App\Status;
use App\SheetSizeColor;
use App\FmKutcha;
use App\SheetproductiondetailsStock;
use App\ConfigMaterial;
use Auth;
use Illuminate\Http\Request;
use Message;

class DailyProductionController extends Controller
{
    protected $dailyProductionRepo;

    public function __construct(DailyProductionRepository $dailyProductionRepository)
    {
        $this->dailyProductionRepo = $dailyProductionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $daily_productions = $this->dailyProductionRepo->index();
        return view('admin.daily_productions.index', compact([
            'daily_productions',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $sheet_production_details_exist = SheetProductionDetails::where('company_id', $currentUser->company_id);
        if ($sheet_production_details_exist->count() < 1) {
            return \redirect('sheet_productions')->with('error_message', 'Please add sheet production.');
        }
        $sheet_production_materials = ConfigMaterial::where('name', 'App\SheetProduction')->pluck('raw_material_id');

        $products = Product::products()->whereIn('raw_material_id', $sheet_production_materials);
        $machines = Machine::where('company_id', $currentUser->company_id)->pluck('name', 'id');
        $statuses = Status::all();
        //check sheet production status is complete
        //and this sheet yet not used to make any product
        $sheet_production_details_ids = DailyProductionDetail::pluck('id');

        $sheet_production_details = SheetProductionDetails::whereHas('sheet_production', function ($q) {
            $q->whereStatusId(1);
        })
            // ->whereNotIn('id', $sheet_production_details_ids)
            ->pluck('sheet_production_details_code', 'id');
        $colors = Color::pluck('name', 'id');
        $sheetproductiondetails_colors = SheetproductiondetailsColor::where('company_id', $currentUser->company_id)->get();

        $sheet_size_colors = SheetSizeColor::all();
        $fm_kutchas = FmKutcha::fmKutchas();

        $sheet_size_color_materials = SheetSizeColor::whereHas('sheetproductiondetails_stocks', function ($q) {
            $q->where('available_quantity_kg', '>', 0)
                ->where('available_quantity_roll', '>', 0);
        })->get();
        // dd($sheet_size_color_materials);
        return view('admin.daily_productions.create', \compact([
            'products', 'machines', 'statuses', 'sheet_production_details', 'colors',
            'sheet_size_colors', 'fm_kutchas', 'sheet_size_color_materials'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DailyProductionStoreRequest $valid)
    {

        $valid->validated();
        $daily_production = $this->dailyProductionRepo->store($request);
        if ($daily_production) {
            return \redirect('daily_productions')->with('message', Message::created('daily_production'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DailyProduction  $dailyProduction
     * @return \Illuminate\Http\Response
     */
    public function show(DailyProduction $dailyProduction)
    {
        return \view('admin.daily_productions.show', compact('dailyProduction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DailyProduction  $dailyProduction
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyProduction $dailyProduction)
    {

       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DailyProduction  $dailyProduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyProduction $dailyProduction, DailyProductionUpdateRequest $valid)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DailyProduction  $dailyProduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(DailyProduction $dailyProduction)
    {
        $dailyProduction = $this->dailyProductionRepo->destroy($dailyProduction);

        if ($dailyProduction) {
            return \redirect('daily_productions')->with('message', Message::deleted('daily_production'));
        }
    }

    public function get_product_data(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::findOrFail($request->id);
            $standard_weight = $product->standard_weight;
            $expected_quantity = $product->expected_quantity;
            $machine_name = $product->machine->name;

            $data = [
                'standard_weight' => $standard_weight,
                'expected_quantity' => $expected_quantity,
                'machine_name' => $machine_name,
            ];

            return response()->json($data);
        }
    }

    public function ajax_get_sheet_stock(Request $request)
    {
        if ($request->ajax()) {
            $todays_weight = $request->todays_weight;
            $used_roll = $request->used_roll;
            $sheetproductiondetails_stock = SheetproductiondetailsStock::where('sheet_size_color_id', $request->sheet_size_color_id)->first();
            $available_quantity_kg = $sheetproductiondetails_stock->available_quantity_kg;
            $available_quantity_roll = $sheetproductiondetails_stock->available_quantity_roll;
            $data['sheet_stock_kg'] = $available_quantity_kg - $todays_weight;
            $data['sheet_stock_roll'] = $available_quantity_roll - $used_roll;
            return $data;
        }
    }
}
