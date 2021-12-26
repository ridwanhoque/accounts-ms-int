<?php
namespace App\Repositories;

use App\HaddiPowderStock;
use App\Interfaces\CrudInterface;
use App\KutchaWastage;
use App\KutchaWastageStock;
use App\RawMaterialStock;
use App\Sheet;
use App\SheetProduction;
use App\SheetProductionDetails;
use App\SheetproductiondetailsStock;
use App\SheetSizeColor;
use App\SheetStock;
use App\SubRawMaterial;
use App\RawMaterialBatchStock;
use App\TemporarySheetProduction;
use App\TemporarySheetProductionDetails;
use Auth;
use DB;
use Illuminate\Http\Request;

class SheetProductionRepository implements CrudInterface
{

    protected $currentUser;
    protected $wastage_types;

    public function __construct()
    {
        $this->currentUser = Auth::user();
        $this->wastage_types = config('app.wastage_types');
    }

    public function index()
    {
        return SheetProduction::with(['sum_material', 'sum_sheet'])->orderByDesc('id')->paginate(25);
    }

    public function create()
    {

    }

    public function sheet_production_save($request)
    {
        $sheet_production_data = [
            'sheet_production_date' => $request->sheet_production_date,
            'status_id' => $request->status_id,
            'total_kg' => $request->total_kg,
            'total_roll' => $request->total_roll,
            'haddi' => $request->haddi,
            'powder' => $request->powder,
        ];
        $create = SheetProduction::create($sheet_production_data);

        return $create;
    }

    public function sheet_production_details_save($request, $key, $sheet_production_id)
    {
        $sheet_production_details_data =
            [
            'sheet_size_id' => $request->sheet_size_ids[$key],
            'color_id' => $request->color_ids[$key],
            'sheet_production_id' => $sheet_production_id,
            'qty_roll' => $request->qty_rolls[$key],
            'qty_kg' => $request->qty_kgs_details[$key],
        ];

        $create = SheetProductionDetails::create($sheet_production_details_data);

        return $create;
    }

    public function sheet_fm_kutcha_save($request, $key, $sheet_production_id)
    {
        $sheet_data = [
            'qty_kg' => $request->fm_kutcha_in_kgs[$key],
            'sheet_production_id' => $sheet_production_id,
        ];
        if ($request->fm_kutcha_in_kgs[0] > 0) {
            $sheet_data['fm_kutcha_id'] = $request->fm_kutcha_in_ids[$key];
            $sheet = Sheet::create($sheet_data);
        }

        return $sheet_data;
    }

    public function sheet_save($request, $key, $sheet_production_id)
    {
        $sheet_data = [
            'qty_kg' => $request->qty_kgs[$key],
            'sheet_production_id' => $sheet_production_id,
            'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
            'batch_id' => $request->batch_id[$key]
        ];
        $create = Sheet::create($sheet_data);

        return $create;
    }

    public function kutcha_wastage_save($request, $key, $sheet_production_id)
    {
        $kutcha_wastage_data = [
            'sheet_production_id' => $sheet_production_id,
            'fm_kutcha_id' => $request->fm_kutcha_ids[$key],
            'qty_kg' => $request->kutcha_qty_kgs[$key],
        ];

        $kutcha_wastage = KutchaWastage::create($kutcha_wastage_data);

        return $kutcha_wastage;
    }

    public function raw_material_stock_save($request, $key, $sheet_production_id)
    {
        //store into sheet stocks table
        $raw_material_stock_exist = RawMaterialStock::where([
            'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
        ]);
        if ($raw_material_stock_exist->count() > 0) {
            $raw_material_stock = $raw_material_stock_exist->first();
            $raw_material_stock->used_quantity += $request->qty_kgs[$key];
            $raw_material_stock->available_quantity -= $request->qty_kgs[$key];
            if($request->batch_id[$key] == ''){
                $raw_material_stock->used_opening_quantity += $request->qty_kgs[$key];
                $raw_material_stock->available_opening_quantity -= $request->qty_kgs[$key];        
            }
            $raw_material_stock->save();
        }

        return $raw_material_stock;
    }

    public function deduct_quantity_from_stock($rm_id, $qty_kgs){
        $raw_material_batch_stock = RawMaterialStock::where('sub_raw_material_id', $rm_id)
            ->first();
            $raw_material_batch_stock->used_quantity += $qty_kgs;
            $raw_material_batch_stock->available_quantity -= $qty_kgs;
            $raw_material_batch_stock->save();

            return $raw_material_batch_stock;
    }

    public function raw_material_batch_stock_save($request, $key, $sheet_production_id)
    {

        $qty_kgs = $request->qty_kgs[$key];
        // //store into sheet stocks table
        $raw_material_batch_stock_exist = RawMaterialBatchStock::where([
            'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
            'batch_id' => $request->batch_id[$key]
        ]);
        if ($raw_material_batch_stock_exist->count() > 0) {
            $raw_material_batch_stock = $raw_material_batch_stock_exist->first();
            $raw_material_batch_stock->used_quantity += $qty_kgs;
            $raw_material_batch_stock->available_quantity -= $qty_kgs;
            $raw_material_batch_stock->save();
        }
        // else{
        //     $raw_material_batch_stock = RawMaterialBatchStock::create([
        //         'sub_raw_material_id' => $request->sub_raw_material_id[$key],
        //         'batch_id' => $request->batch_id[$key],
        //         'used_quantity' => $qty_kgs,
        //         'available_quantity' => $qty_kgs
        //     ]);
        // }

        return 1;
    }

    public function sheet_stock_save($request, $key, $sheet_production_id)
    {
        //store into sheet stocks table
        $sheet_stock_exist = SheetStock::where([
            'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
        ]);
        if ($sheet_stock_exist->count() > 0) {
            $sheet_stock = $sheet_stock_exist->first();
            $sheet_stock->total_kg += $request->qty_kgs[$key];
            $sheet_stock->available_kg += $request->qty_kgs[$key];
            $sheet_stock->save();
        } else {
            $sheet_stock = SheetStock::create([
                'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
                'total_kg' => $request->qty_kgs[$key],
                'available_kg' => $request->qty_kgs[$key]
            ]);
        }

        return $sheet_stock;
    }

    public function kutcha_wastage_stock_save($request, $key, $sheet_production_id)
    {
        //store into kutcha_wastage stocks table
        $kutcha_wastage_stock_exist = KutchaWastageStock::where([
            'fm_kutcha_id' => $request->fm_kutcha_ids[$key],
        ]);
        if ($kutcha_wastage_stock_exist->count() > 0) {
            $kutcha_wastage_stock = $kutcha_wastage_stock_exist->first();
            $kutcha_wastage_stock->total_kg += $request->kutcha_qty_kgs[$key];
            $kutcha_wastage_stock->available_kg += $request->kutcha_qty_kgs[$key];
            $kutcha_wastage_stock->save();
        } else {
            $kutcha_wastage_stock = KutchaWastageStock::create([
                'fm_kutcha_id' => $request->fm_kutcha_ids[$key],
                'total_kg' => $request->kutcha_qty_kgs[$key],
                'available_kg' => $request->kutcha_qty_kgs[$key],
            ]);
        }

        return $kutcha_wastage_stock;
    }

    public function kutcha_wastage_stock_deduct($request, $key, $sheet_production_id)
    {
        //store into kutcha_wastage stocks table
        $kutcha_wastage_stock_exist = KutchaWastageStock::where([
            'fm_kutcha_id' => $request->fm_kutcha_in_ids[$key],
        ]);
        if ($kutcha_wastage_stock_exist->count() > 0) {
            $kutcha_wastage_stock = $kutcha_wastage_stock_exist->first();
            $kutcha_wastage_stock->used_kg += $request->fm_kutcha_in_kgs[$key];
            $kutcha_wastage_stock->available_kg -= $request->fm_kutcha_in_kgs[$key];
            $kutcha_wastage_stock->save();
        }

        return $kutcha_wastage_stock;
    }

    public function sheet_size_color_save($request, $key, $raw_material_id)
    {
        $sheet_size_color = SheetSizeColor::create([
            'sheet_size_id' => $request->sheet_size_ids[$key],
            'color_id' => $request->color_ids[$key],
            'raw_material_id' => $raw_material_id,
        ]);

        return $sheet_size_color;
    }

    public function sheetproductiondetails_stock_save($request, $key, $sheet_production_id)
    {
        $raw_material_id = SubRawMaterial::find($request->sub_raw_material_ids[0])->raw_material_id;

        $sheet_size_color_exist = SheetSizeColor::where([
            'sheet_size_id' => $request->sheet_size_ids[$key],
            'color_id' => $request->color_ids[$key],
            'raw_material_id' => $raw_material_id,
        ]);
        

        if ($sheet_size_color_exist->count() > 0) {
            $sheet_size_color_id = $sheet_size_color_exist->first()->id;
            $spdetails_stock = SheetproductiondetailsStock::where([
                'sheet_size_color_id' => $sheet_size_color_id,
            ]);
            $spdetails_stock = SheetproductiondetailsStock::where('sheet_size_color_id', $sheet_size_color_exist->first()->id)->first();
            $spdetails_stock->total_quantity_roll += $request->qty_rolls[$key];
            $spdetails_stock->available_quantity_roll += $request->qty_rolls[$key];
            $spdetails_stock->total_quantity_kg += $request->qty_kgs_details[$key];
            $spdetails_stock->available_quantity_kg += $request->qty_kgs_details[$key];
            $spdetails_stock->save();
        } else {
            $sheet_size_color = $this->sheet_size_color_save($request, $key, $raw_material_id);
            $spdetails_stock = SheetproductiondetailsStock::create([
                'sheet_size_color_id' => $sheet_size_color->id,
                'total_quantity_roll' => $request->qty_rolls[$key],
                'available_quantity_roll' => $request->qty_rolls[$key],
                'total_quantity_kg' => $request->qty_kgs_details[$key],
                'available_quantity_kg' => $request->qty_kgs_details[$key],
            ]);

        }

        return $spdetails_stock;

    }

    public function haddi_powder_stock_save($request){
        $sub_raw_material_id = $request->sub_raw_material_ids[0];
        $data = [
            'haddi' => $request->haddi,
            'powder' => $request->powder,
            'sub_raw_material_id' => $sub_raw_material_id
        ];

        $haddi_powder_exists = HaddiPowderStock::where('sub_raw_material_id', $sub_raw_material_id);
        if($haddi_powder_exists->count() > 0){
            $haddi_powder_stock = $haddi_powder_exists->first();
            $haddi_powder_stock->haddi += $request->haddi;
            $haddi_powder_stock->powder += $request->powder;
            $haddi_powder_stock->save();
        }else{
            $haddi_powder_stock = HaddiPowderStock::create($data);
        }

        return $haddi_powder_stock;
    }

    public function haddi_powder_stock_deduct($sheet_production){
        $sub_raw_material_id = $sheet_production->sheets->first()->sub_raw_material_id;
        $haddi_powder_stock = HaddiPowderStock::where('sub_raw_material_id', $sub_raw_material_id)
                            ->first();
        $haddi_powder_stock->haddi -= $sheet_production->haddi;
        $haddi_powder_stock->powder -= $sheet_production->powder;
        $haddi_powder_stock->save();

        return $haddi_powder_stock;
    }

    public function store($request)
    {

        //check whether input and output equal
        //assign_material+assign_kutcha+sheet
        //kutcha+haddi+powder

        $sheet_production = $this->sheet_production_save($request);

        $haddi_powder_stock = $this->haddi_powder_stock_save($request);

        if(!empty($request->qty_kgs)){
            foreach ($request->qty_kgs as $s_key => $qty_kg) {
                if ($request->qty_kgs[0] > 0) {
                    //store into sheet table
                    $this->sheet_save($request, $s_key, $sheet_production->id);
    
                    //store into sheet stock table
                    $this->sheet_stock_save($request, $s_key, $sheet_production->id);
    
                    //store into raw material stock table
                    $this->raw_material_stock_save($request, $s_key, $sheet_production->id);
    
                    //store into raw material batch stock table
                    $this->raw_material_batch_stock_save($request, $s_key, $sheet_production->id);
    
                }
            }    
        }

        if(!empty($request->fm_kutcha_in_ids)){
            foreach ($request->fm_kutcha_in_ids as $fkin_key => $fm_kutcha_in_id) {
                //it can be null
                if ($request->fm_kutcha_in_kgs[0] > 0) {
                    //store into sheet table
                    $this->sheet_fm_kutcha_save($request, $fkin_key, $sheet_production->id);
    
                    //deduct qty from kutcha wastage stock
                    $this->kutcha_wastage_stock_deduct($request, $fkin_key, $sheet_production->id);
                }
            }    
        }

        if(!empty($request->sheet_size_ids)){
            foreach ($request->sheet_size_ids as $spd_key => $sheet_size_id) {
                if ($request->qty_rolls[0] > 0 || $request->qty_kgs[0] > 0) {
                    //store into sheet production details
                    $sheet_production_details = $this->sheet_production_details_save($request, $spd_key, $sheet_production->id);
    
                    //store into sheet production details stock
                    $this->sheetproductiondetails_stock_save($request, $spd_key, $sheet_production->id);
                }
    
            }    
        }

        if(!empty($request->fm_kutcha_ids)){
            foreach ($request->fm_kutcha_ids as $fk_key => $fm_kutcha_id) {
                if ($request->kutcha_qty_kgs[0] > 0 && $fm_kutcha_id > 0) {
                    //add with kutcha wastage
                    $this->kutcha_wastage_save($request, $fk_key, $sheet_production->id);
    
                    //add with kutcha wastage stock
                    $this->kutcha_wastage_stock_save($request, $fk_key, $sheet_production->id);
                }
            }    
        }

        //sheet_stock
        //raw_material_stock
        //kutcha_wastage_stock

        $this->delete_temporary_sheet_production($request->temporary_sheet_production_id);

        return "success";

    }

    public function delete_temporary_sheet_production($id){
        $temporary_sheet_production = TemporarySheetProduction::find($id);
        $temporary_sheet_production->temporary_sheet_production_details->each->delete();
        $temporary_sheet_production->delete();

        return true;

    }

    public function edit($sheet_production)
    {

    }

    public function show($sheet_production)
    {

    }

    public function update($request, $sheet_production)
    {

    }

    public function destroy($sheet_production)
    {
        $this->haddi_powder_stock_deduct($sheet_production);

        foreach ($sheet_production->sheet_production_details as $spdetails) {
            
            $raw_material_id_db = $sheet_production->whereHas('sheets', function($q) use($sheet_production){
                $q->where('sub_raw_material_id', '>=', 1)
                    ->where('sheet_production_id', $sheet_production->id);
            })->first()->sheets->first()->sub_raw_material->raw_material_id;


              //sheetproductiondetails stock deduct
              $sheet_size_color_id = SheetSizeColor::where([
                'sheet_size_id' => $spdetails->sheet_size_id,
                'color_id' => $spdetails->color_id,
                'raw_material_id' => $raw_material_id_db 
            ])->first()->id;

            $sheetproductiondetails_stock = SheetproductiondetailsStock::where('sheet_size_color_id', $sheet_size_color_id)->first();
            $sheetproductiondetails_stock->total_quantity_kg -= $spdetails->qty_kg;
            $sheetproductiondetails_stock->available_quantity_kg -= $spdetails->qty_kg;
            $sheetproductiondetails_stock->total_quantity_roll -= $spdetails->qty_roll;
            $sheetproductiondetails_stock->available_quantity_roll -= $spdetails->qty_roll;
            $sheetproductiondetails_stock->save();

            if($sheetproductiondetails_stock->total_quantity_kg <= 0 && $sheetproductiondetails_stock->total_quantity_roll <= 0){
                //sheet production details delete
                $sheetproductiondetails_stock->delete();
                
                SheetSizeColor::find($sheet_size_color_id)->delete();
            }

            //sheet production details delete
            $spdetails->delete();
        }

        foreach ($sheet_production->sheets as $sheet) {

            if ($sheet->sub_raw_material_id > 0) {
                //sheet stock delete
                $sheet_stock = SheetStock::where('sub_raw_material_id', $sheet->sub_raw_material_id)->first();
                $sheet_stock->total_kg -= $sheet->qty_kg;
                $sheet_stock->available_kg -= $sheet->qty_kg;
                $sheet_stock->save();

                //raw material add
                $raw_material_stock = RawMaterialStock::where('sub_raw_material_id', $sheet->sub_raw_material_id)->first();
                $raw_material_stock->used_quantity -= $sheet->qty_kg;
                $raw_material_stock->available_quantity += $sheet->qty_kg;
                if($sheet->batch_id == NULL){
                    $raw_material_stock->used_opening_quantity -= $sheet->qty_kg;
                    $raw_material_stock->available_opening_quantity += $sheet->qty_kg;
                }
                $raw_material_stock->save();

                //raw material add
                $raw_material_batch_check = RawMaterialBatchStock::where('sub_raw_material_id', $sheet->sub_raw_material_id)
                    ->where('batch_id', $sheet->batch_id);
                   if($raw_material_batch_check->count() > 0){
                        $raw_material_batch_stock->first();
                        $raw_material_batch_stock->used_quantity -= $sheet->qty_kg;
                        $raw_material_batch_stock->available_quantity += $sheet->qty_kg;
                        $raw_material_batch_stock->save();
                   }
              
            }

            if ($sheet->fm_kutcha_id > 0) {
                //sheet stock deduct added qty
                // $sheet_stock_by_fm = Sheet::where('fm_kutcha_id', $sheet->fm_kutcha_id)->first();
                // $sheet_stock_by_fm->total_kg -= $sheet->qty_kg;
                // $sheet_stock_by_fm->available_kg -= $sheet->qty_kg;
                // $sheet_stock_by_fm->save();


                //kutcha wastage stock add if any kutch is added with raw material then deduct it
                $kutcha_wastage_stock = KutchaWastageStock::where('fm_kutcha_id', $sheet->fm_kutcha_id)->first();
                $kutcha_wastage_stock->used_kg -= $sheet->qty_kg;
                $kutcha_wastage_stock->available_kg += $sheet->qty_kg;
                $kutcha_wastage_stock->save();

            }

            //sheet delete
            $sheet->delete();

        }

        foreach($sheet_production->kutcha_wastages as $kutcha_wastage){
            
                    $kutcha_wastage_stock_add = KutchaWastageStock::where('fm_kutcha_id', $kutcha_wastage->fm_kutcha_id)->first();
                    $kutcha_wastage_stock_add->total_kg -= $kutcha_wastage->qty_kg;
                    $kutcha_wastage_stock_add->available_kg -= $kutcha_wastage->qty_kg;
                    $kutcha_wastage_stock_add->save(); 

                    $kutcha_wastage->delete();                  
                    
        }

        return $sheet_production->delete();
    }

    public function temporary_sheet_production_save($request){
        $temporary_sheet_production = TemporarySheetProduction::create([
            'sheet_production_date' => $request->sheet_production_date,
            'total_kg' => $request->total_kg,
            'total_roll' => $request->total_roll,
            'haddi' => $request->haddi,
            'powder' => $request->powder
        ]);

        return $temporary_sheet_production;
    }

    public function temp_material_save($request, $key, $id){
        $data['temporary_sheet_production_id'] = $id;
        $data['sheet_type'] = 1;
        $data['batch_id'] = $request->batch_id[$key];
        $data['sub_raw_material_id'] = $request->sub_raw_material_ids[$key];
        $data['qty_kgs'] = $request->qty_kgs[$key];

        $temp_material = TemporarySheetProductionDetails::create($data);

        return $temp_material;
    }

    public function temp_sheet_save($request, $key, $id){
        $data['temporary_sheet_production_id'] = $id;
        $data['sheet_type'] = 3;
        $data['sheet_size_id'] = $request->sheet_size_ids[$key];
        $data['color_id'] = $request->color_ids[$key];
        $data['sheet_rolls'] = $request->qty_rolls[$key];
        $data['sheet_kgs'] = $request->qty_kgs_details[$key];

        $temp_sheet = TemporarySheetProductionDetails::create($data);

        return $temp_sheet;
    }

    public function temp_assign_kutcha_save($request, $key, $id){
        $data['temporary_sheet_production_id'] = $id;
        $data['sheet_type'] = 2;
        $data['fm_kutcha_id'] = $request->fm_kutcha_in_ids[$key];
        $data['qty_kgs'] = $request->fm_kutcha_in_kgs[$key];

        $temp_assign_kutcha = TemporarySheetProductionDetails::create($data);

        return $temp_assign_kutcha;
    }

    public function temp_kutcha_wastage_save($request, $key, $id){
        $data['temporary_sheet_production_id'] = $id;
        $data['sheet_type'] = 4;
        $data['fm_kutcha_id'] = $request->fm_kutcha_ids[$key];
        $data['qty_kgs'] = $request->kutcha_qty_kgs[$key];

        $temp_kutcha_wastage = TemporarySheetProductionDetails::create($data);

        return $temp_kutcha_wastage;
        
    }

    public function temporary_sheet_production_details_save($request, $key, $id){
        // $data = [
        //     'temporary_sheet_production_id' => $id
        // ];
        // if(array_key_exists($key, $request->sub_raw_material_ids) && $request->qty_kgs[$key] > 0){
        //     $this->temp_material_save($request, $key, $id);
        // }
        // if(array_key_exists($key, $request->fm_kutcha_in_kgs) && $request->fm_kutcha_in_kgs[$key] > 0){
        //     $data['sheet_type'] = 2;
        //     $data['fm_kutcha_id'] = $request->fm_kutcha_in_ids[$key];
        //     $data['qty_kgs'] = $request->fm_kutcha_in_kgs[$key];
        // }
        // if(array_key_exists($key, $request->sheet_size_ids) && $request->qty_rolls[$key] > 0){
        //     $data['sheet_type'] = 3;
        //     $data['sheet_size_id'] = $request->sheet_size_ids[$key];
        //     $data['color_id'] = $request->color_ids[$key];
        //     $data['sheet_rolls'] = $request->qty_rolls[$key];
        //     $data['sheet_kgs'] = $request->qty_kgs_details[$key];
        // }
        // if(array_key_exists($key, $request->kutcha_qty_kgs) && $request->kutcha_qty_kgs[$key] > 0){
        //     $data['sheet_type'] = 4;
        //     $data['fm_kutcha_id'] = $request->fm_kutcha_ids[$key];
        //     $data['qty_kgs'] = $request->kutcha_qty_kgs[$key];
        // }

        // $tspdetails = TemporarySheetProductionDetails::create($data);

        // return $tspdetails;
    }

    public function temporary_store(Request $request){
        $temporary_sheet_production = $this->temporary_sheet_production_save($request);

        //assigned raw material
        foreach ($request->qty_kgs as $s_key => $qty_kg) {
            if ($request->qty_kgs[$s_key] > 0) {
                $this->temp_material_save($request, $s_key, $temporary_sheet_production->id);
            }
        }

        //assigned kutcha
        foreach ($request->fm_kutcha_in_ids as $fkin_key => $fm_kutcha_in_id) {
            //it can be null
            if ($request->fm_kutcha_in_kgs[$fkin_key] > 0) {
                $this->temp_assign_kutcha_save($request, $fkin_key, $temporary_sheet_production->id);
            }
        }

        //produced sheets
        foreach ($request->sheet_size_ids as $spd_key => $sheet_size_id) {
            if ($request->qty_rolls[$spd_key] > 0 || $request->qty_kgs_details[$spd_key] > 0) {
                $this->temp_sheet_save($request, $spd_key, $temporary_sheet_production->id);
            }

        }

        //wastage out
        foreach ($request->fm_kutcha_ids as $fk_key => $fm_kutcha_id) {
            if($request->kutcha_qty_kgs[$fk_key] > 0){
                $this->temp_kutcha_wastage_save($request, $fk_key, $temporary_sheet_production->id);
            }
        }
    }


    public function delete_pending($id){
        $temporary_sheet_production = TemporarySheetProduction::find($id);
        $temporary_sheet_production->temporary_sheet_production_details->delete();
        $temporary_sheet_production->delete();

        return 'true';
    }


}
