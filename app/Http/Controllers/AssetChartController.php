<?php

namespace App\Http\Controllers;

use App\AssetChart;
use App\Http\Requests\AssetChartStoreRequest;
use App\Http\Requests\AssetChartUpdateRequest;
use Illuminate\Http\Request;
use App\Repositories\AssetChartRepository;
use Message;

class AssetChartController extends Controller
{
    protected $assetChartRepo;

    public function __construct(AssetChartRepository $assetChartRepository)
    {
        $this->assetChartRepo = $assetChartRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asset_charts = AssetChart::assetCharts()->paginate(25);
        return view('admin.accounting.asset_charts.index', compact('asset_charts'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.accounting.asset_charts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AssetChartStoreRequest $valid)
    {
        $valid->validated();
        $assetChart = $this->assetChartRepo->store($request->all());    

        if($assetChart){
            return redirect('asset_charts')->with('message', Message::created('asset_chart'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AssetChart  $assetChart
     * @return \Illuminate\Http\Response
     */
    public function show(AssetChart $assetChart)
    {
        return view('admin.accounting.asset_charts.show', compact('assetChart'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AssetChart  $assetChart
     * @return \Illuminate\Http\Response
     */
    public function edit(AssetChart $assetChart)
    {
        return view('admin.accounting.asset_charts.edit', compact('assetChart'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AssetChart  $assetChart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetChart $assetChart, AssetChartUpdateRequest $valid)
    {
        $valid->validated();

        $assetChart = $this->assetChartRepo->update($request->all(), $assetChart);

        if($assetChart){
            return redirect('asset_charts')->with('message', Message::updated('asset_chart'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssetChart  $assetChart
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetChart $assetChart)
    {
        $assetChart = $this->assetChartRepo->destroy($assetChart);

        if($assetChart){
            return redirect('asset_charts')->with('message', Message::deleted('asset_chart'));
        }
    }

    public function updateChart($id, AssetChartUpdateRequest $valid){
        $assetChart = AssetChart::find($id);

        $valid->validated();

    }
}
