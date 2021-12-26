<?php
namespace App\Repositories;

use App\DailyProduction;
use App\DailyProductionDetail;
use App\DailyProductionDetailStock;
use App\FormingWastageStock;
use App\Interfaces\CrudInterface;
use App\ProductStock;
use App\SheetproductiondetailsStock;
use App\SheetSizeColor;
use App\SheetStock;
use App\WastageStock;
use App\KutchaWastageStock;
use Auth;

class TemporaryDailyProductionRepository implements CrudInterface
{

    protected $wastage_types;

    public function __construct()
    {
        $this->wastage_types = config('app.wastage_types');
    }

    public function index()
    {
        return DailyProduction::where('company_id', auth()->user()->company_id)->where('is_approved', 1)->with('total_sheet_kutchas')->orderByDesc('id')->paginate(25);
    }

    public function create()
    {

    }

    public function store($request)
    {
        // dd($request->all());

        //store into daily production table
        $daily_production = $this->save_daily_production($request);

        foreach ($request->product_ids as $key => $product_id) {

            $details = [
                'daily_production_id' => $daily_production->id,
                'product_id' => $product_id,
            ];
            //store into daily production details table
            $daily_production_detail = $this->save_daily_production_details($request, $key, $details);

        }

        return true;

    }

    public function sheetproductiondetails_stock_save($data){
        $sheet_production_details = SheetproductiondetailsStock::where('sheet_size_color_id', $data['sheet_size_color_id'])->first();
        $sheet_production_details->used_quantity_kg += $data['total_kg'];
        $sheet_production_details->available_quantity_kg -= $data['total_kg'];
        $sheet_production_details->used_quantity_roll += $data['total_roll'];
        $sheet_production_details->available_quantity_roll -= $data['total_roll'];
        $sheet_production_details->save();
    }

    public function kutcha_wastage_stock_save($request, $key)
    {
        $kutcha_wastage_stock_exists = KutchaWastageStock::where('fm_kutcha_id', $request->fm_kutcha_ids[$key]);
        if ($kutcha_wastage_stock_exists->count() > 0) {
            $kutcha_wastage_stock = $kutcha_wastage_stock_exists->first();
            $kutcha_wastage_stock->total_kg += $request->wastage_out[$key];
            $kutcha_wastage_stock->available_kg += $request->wastage_out[$key];
            $kutcha_wastage_stock->save();
        } else {
            $kutcha_wastage_stock = KutchaWastageStock::create([
                'fm_kutcha_id' => $request->fm_kutcha_ids[$key], 
                'total_kg' => $request->wastage_out[$key],
                'available_kg' => $request->wastage_out[$key],
            ]);
        }

        return $kutcha_wastage_stock;
    }

    public function edit($daily_production)
    {

    }

    public function show($daily_production)
    {

    }

    public function update($request, $daily_production)
    {
        //update daily production info
        $this->save_daily_production($request, $daily_production->id);

        foreach ($request->product_ids as $key => $product_id) {
            $details = [
                'daily_production_id' => $daily_production->id,
                'product_id' => $product_id,
            ];
            $daily_production_details_id = $request->daily_production_details_ids[$key];
            //update daily production details info
            $daily_production_detail = $this->save_daily_production_details($request, $key, $details, $daily_production_details_id);

            // if ($request->status_id == 1) {
            //     $total_kg = $request->todays_weight[$key];

                $ids = [
                    'daily_production_details_id' => $daily_production_detail->id,
                    'product_id' => $product_id,
                    'finish_quantity' => $request->finish_quantity[$key],
                ];

            //     //store lot wise stock
            //     $this->save_dailyproductiondetails_stock($ids);

            //     //if status is complete deduct sheet from sheet stock
            //     $this->deduct_from_sheet_stock($total_kg);

            //     //add to product stock
            //     $this->save_product_stock($request, $key, $product_id);

            //     //add to wastage stock
            //     // $this->save_wastage_stock($request, $key);

            //     //add to forming wastage stock
            //     // $this->save_forming_wastage($request, $key, $ids);
            // }

            if ($request->is_approved == 1) {
                $total_kg = $request->todays_weight[$key];
                $total_roll = $request->used_rolls[$key];

                $ids = [
                    'daily_production_details_id' => $daily_production_detail->id,
                    'product_id' => $product_id,
                    'finish_quantity' => $request->finish_quantity[$key],
                    'sheet_size_color_id' => $request->sheet_size_color_ids[$key],
                    'total_kg' => $total_kg,
                    'total_roll' => $total_roll,
                ];

                //deduct from sheet production details stock
                $this->sheetproductiondetails_stock_save($ids);

                //store lot wise stock
                $this->save_dailyproductiondetails_stock($ids);

                //add to product stock
                $this->save_product_stock($request, $key, $product_id);

                //add to wastage stock
                $this->kutcha_wastage_stock_save($request, $key);

            }

        }

        return true;
    }

    public function destroy($daily_production)
    {
        if($daily_production->is_approved != 1){
            $daily_production->daily_production_details->each->delete();
            $daily_production->delete();

            //sheetproductiondetails_stock_save save
            //save_dailyproductiondetails_stock
            //save_product_stock
            //kutcha_wastage_stock_save
        }

        return true;
    }

    public function save_daily_production($request, $id = null)
    {

        if ($id != null) {
            $daily_production = DailyProduction::findOrFail($id);
        } else {
            $daily_production = new DailyProduction;
            $daily_production->daily_production_date = $request->daily_production_date;
        }
        $daily_production->is_approved = $request->is_approved;
        $daily_production->status_id = $request->status_id;
        $daily_production->save();

        return $daily_production;
    }

    public function save_daily_production_details($request, $key, $details, $id = null)
    {
        if ($id != null) {
            $daily_production_detail = DailyProductionDetail::findOrFail($id);
        } else {
            $daily_production_detail = new DailyProductionDetail;
        }

        $daily_production_detail->daily_production_id = $details['daily_production_id'];
        $daily_production_detail->product_id = $details['product_id'];
        $daily_production_detail->machine_id = $request->machine_ids[$key];
        $daily_production_detail->sheet_size_color_id = $request->sheet_size_color_ids[$key];
        $daily_production_detail->wastage_out = $request->wastage_out[$key] != null ? $request->wastage_out[$key] : 0;
        $daily_production_detail->standard_weight = $request->standard_weight[$key];
        $daily_production_detail->todays_weight = $request->todays_weight[$key];
        $daily_production_detail->used_roll = $request->used_rolls[$key];
        $daily_production_detail->expected_quantity = $request->expected_quantity[$key];
        $daily_production_detail->finish_quantity = $request->finish_quantity[$key];
        $daily_production_detail->pack = $request->pack[$key];
        $daily_production_detail->cavity = $request->cavity[$key];
        $daily_production_detail->net_weight = $request->net_weight[$key];
        $daily_production_detail->run_time = $request->run_time[$key];
        $daily_production_detail->hours_per_minute = $request->hours_per_minute[$key];
        $daily_production_detail->sheet_wastage = $request->sheet_wastage[$key] != null ?: 0;
        $daily_production_detail->forming_wastage = $request->forming_wastage[$key] != null ?: 0;
        $daily_production_detail->reason = $request->reason[$key];
        $daily_production_detail->remarks = $request->remarks[$key];
        $daily_production_detail->fm_kutcha_id = $request->fm_kutcha_ids[$key];
        $daily_production_detail->save();

        return $daily_production_detail;
    }

    public function deduct_from_sheet_stock($total_kg)
    {
        $currentUser = Auth::user();
        $sheet_stock = SheetStock::where('company_id', $currentUser->company_id)->firstOrFail();
        $sheet_stock->total_kg -= $total_kg;
        $sheet_stock->save();

        return $sheet_stock;
    }

    public function save_product_stock($request, $key, $product_id)
    {
     
        $product_stock_exist = ProductStock::where('product_id', $product_id);
        if ($product_stock_exist->count() > 0) {
            $product_stock = $product_stock_exist->first();
        } else {
            $product_stock = new ProductStock;
            $product_stock->product_id = $product_id;
        }
        $product_stock->produced_quantity += $request->finish_quantity[$key];
        $product_stock->available_quantity += $request->finish_quantity[$key];
        $product_stock->produced_pack += $request->pack[$key];
        $product_stock->available_pack += $request->pack[$key];
        $product_stock->produced_weight += $request->net_weight[$key];
        $product_stock->available_weight += $request->net_weight[$key];
        $product_stock->save();

        return $product_stock;
    }

    public function save_wastage_stock($request, $key)
    {
        $wastage_stocks = WastageStock::where('type', $this->wastage_types[1])->firstOrFail();
        $wastage_stocks->total_quantity += $request->wastage_out[$key];
        $wastage_stocks->available_quantity += $request->wastage_out[$key];
        $wastage_stocks->save();

        return $wastage_stocks;
    }

    public function save_forming_wastage($request, $key, $ids)
    {
        $sheet_production_details_id = SheetSizeColor::find($request->sheet_size_color_ids[$key])->sheet_production_details_id;

        $forming_wastage_exists = FormingWastageStock::where('product_id', $ids['product_id']);
        if ($forming_wastage_exists->count() > 0) {
            $forming_wastage_stock = $forming_wastage_exists->first();
        } else {
            $forming_wastage_stock = new FormingWastageStock;
            $forming_wastage_stock->product_id = $ids['product_id'];
            $forming_wastage_stock->sheet_production_details_id = $sheet_production_details_id;
        }
        $forming_wastage_stock->total_quantity += $request->wastage_out[$key];
        $forming_wastage_stock->available_quantity += $request->wastage_out[$key];
        $forming_wastage_stock->save();

        return $forming_wastage_stock;
    }

    public function save_dailyproductiondetails_stock($details_stock)
    {
        $dailyproductiondetails_stock = new DailyProductionDetailStock;
        $dailyproductiondetails_stock->daily_production_details_id = $details_stock['daily_production_details_id'];
        $dailyproductiondetails_stock->product_id = $details_stock['product_id'];
        $dailyproductiondetails_stock->total_quantity += $details_stock['finish_quantity'];
        $dailyproductiondetails_stock->available_quantity += $details_stock['finish_quantity'];
        $dailyproductiondetails_stock->save();
    }

    public function deduct_from_sheetproductiondetails_stock($data)
    {
        $sheetproductiondetails_stock = SheetproductiondetailsStock::where('sheet_size_color_id', $data['sheet_size_color_id'])
            ->get();

        $total_kg = $data['total_kg'];
        $total_roll = $data['total_roll'];

        foreach ($sheetproductiondetails_stock as $spd_stock) {
            $available_kg_db = $spd_stock->available_quantity_kg;
            $available_roll_db = $spd_stock->available_quantity_roll;

            if ($total_kg > $available_kg_db && $total_kg > 0) {
                $spd_stock->used_quantity_kg += $available_kg_db;
                $spd_stock->available_quantity_kg = 0;
                $total_kg -= $available_kg_db;
            } else {
                $spd_stock->used_quantity_kg += $total_kg;
                $spd_stock->available_quantity_kg -= $total_kg;
                $total_kg -= $available_kg_db;
            }

            if ($total_roll > $available_roll_db && $total_roll > 0) {
                $spd_stock->used_quantity_roll += $available_roll_db;
                $spd_stock->available_quantity_roll = 0;
                $total_roll -= $available_roll_db;
            } else {
                $spd_stock->used_quantity_roll += $total_roll;
                $spd_stock->available_quantity_roll -= $total_roll;
                $total_roll -= $available_roll_db;
            }

            $spd_stock->save();
        }

        return $sheetproductiondetails_stock;
    }

}
