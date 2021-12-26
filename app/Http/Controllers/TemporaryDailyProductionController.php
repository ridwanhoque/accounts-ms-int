<?php

namespace App\Http\Controllers;

use App\DailyProduction;
use App\FmKutcha;
use App\Http\Requests\DailyProductionStoreRequest;
use App\Machine;
use App\SheetSizeColor;
use App\Product;
use App\Status;
use App\DailyProductionDetail;
use App\SheetProductionDetails;
use App\Color;
use App\SheetproductiondetailsColor;
use App\Repositories\TemporaryDailyProductionRepository;
use Message;
use Illuminate\Support\Facades\Auth;


class TemporaryDailyProductionController extends Controller
{
    protected $temporaryDailyProductionRepo;

    public function __construct(TemporaryDailyProductionRepository $temporaryDailyProductionRepository)
    {
        $this->temporaryDailyProductionRepo = $temporaryDailyProductionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $temporary_daily_productions = DailyProduction::where('company_id', auth()->user()->company_id)->where('is_approved', 0)->paginate(25);
        return view('admin.temporary_daily_productions.index', compact('temporary_daily_productions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sheet_size_color_materials = SheetSizeColor::whereHas('sheetproductiondetails_stocks', function ($q) {
            $q->where('available_quantity_kg', '>', 0)
                ->where('available_quantity_roll', '>', 0);
        })->get();
        $products = Product::with('raw_material')->products();
        $machines = Machine::where('company_id', auth()->user()->company_id)->pluck('name', 'id');
        $fm_kutchas = FmKutcha::with('raw_material')->fmKutchas();
        return view('admin.temporary_daily_productions.create', compact('sheet_size_color_materials', 'products', 'machines', 'fm_kutchas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DailyProductionStoreRequest $request)
    {
        $request->validated();

        $temporaryDailyProductions = $this->temporaryDailyProductionRepo->store($request);

        if($temporaryDailyProductions){
            return \redirect('temporary_daily_productions')->with('message', Message::created('daily_production'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dailyProduction = DailyProduction::find($id);
        $products = Product::products();
        $machines = Machine::machines();
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
        $currentUser = Auth::user();
        $sheetproductiondetails_colors = SheetproductiondetailsColor::where('company_id', $currentUser->company_id)->get();
        $sheet_size_color_materials = SheetSizeColor::whereHas('sheetproductiondetails_stocks', function ($q) {
            $q->where('available_quantity_kg', '>', 0)
                ->where('available_quantity_roll', '>', 0);
        })->get();

        $fm_kutchas = FmKutcha::with('raw_material')->fmKutchas();
        
        return view('admin.temporary_daily_productions.edit', compact([
            'dailyProduction', 'products', 'machines', 'statuses', 'sheet_production_details',
            'colors', 'sheet_size_color_materials', 'fm_kutchas'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DailyProductionStoreRequest $request, $id)
    {
        
        $request->validated();
        $dailyProduction = DailyProduction::find($id);

        $daily_production = $this->temporaryDailyProductionRepo->update($request, $dailyProduction);

        if ($daily_production) {
            return \redirect('daily_productions')->with('message', Message::updated('daily_production'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
