<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\SheetProduction;
use App\TemporarySheetProduction;
use App\TemporarySheetProductionDetails;
use Auth;
use Illuminate\Http\Request;

class TemporarySheetProductionRepository implements CrudInterface
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

    public function store($request)
    {
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
     
        return 'success';

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

    public function destroy($id)
    {
        $temporary_sheet_production = TemporarySheetProduction::find($id);
        $temporary_sheet_production->temporary_sheet_production_details->each->delete();
        $temporary_sheet_production->delete();

        return 'true';
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

}
