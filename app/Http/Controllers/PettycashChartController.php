<?php

namespace App\Http\Controllers;

use App\PettycashChart;
use Illuminate\Http\Request;
use App\Status;
use App\Repositories\PettycashChartRepository;
use App\Http\Requests\PettycashChartStoreRequest;
use App\Http\Requests\PettycashChartUpdateRequest;
use Auth;
use Message;

class PettycashChartController extends Controller
{
    protected $pettycashChartRepo; 
    protected $statuses;

    public function __construct(PettycashChartRepository $pettycashChartRepository){
        $this->pettycashChartRepo = $pettycashChartRepository;
        $this->statuses = config('app.statuses');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = $this->statuses;
        $currentUser = Auth::user();
        $pettycash_charts = PettycashChart::where('company_id', $currentUser->company_id)->paginate(25);
        return view('admin.accounting.pettycash_charts.index', compact([
            'pettycash_charts', 'statuses'
            ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = $this->statuses;
        return view('admin.accounting.pettycash_charts.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PettycashChartStoreRequest $valid)
    {
        $valid->validated();
        
        $pettycash_charts = $this->pettycashChartRepo->store($request);

        if($pettycash_charts){
            return \redirect('pettycash_charts')->with('message', Message::created('pettycash_chart'));
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PettycashChart  $pettycashChart
     * @return \Illuminate\Http\Response
     */
    public function show(PettycashChart $pettycashChart)
    {
        $statuses = $this->statuses;
        return view('admin.accounting.pettycash_charts.show', compact([
            'pettycashChart', 'statuses'
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PettycashChart  $pettycashChart
     * @return \Illuminate\Http\Response
     */
    public function edit(PettycashChart $pettycashChart)
    {
        $statuses = $this->statuses;
        return view('admin.accounting.pettycash_charts.edit', compact([
            'pettycashChart', 'statuses'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PettycashChart  $pettycashChart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PettycashChart $pettycashChart, PettycashchartUpdateRequest $valid)
    {
        $valid->validated();

        $pettycash_charts = $this->pettycashChartRepo->update($request, $pettycashChart);

        if($pettycash_charts){
            return \redirect('pettycash_charts')->with('message', Message::updated('pettycash_chart'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PettycashChart  $pettycashChart
     * @return \Illuminate\Http\Response
     */
    public function destroy(PettycashChart $pettycashChart)
    {
        $pettycash_charts = $this->pettycashChartRepo->destroy($pettycashChart);

        if($pettycash_charts){
            return \redirect('accounting/pettycash_charts')->with('message', Message::deleted('pettycash_chart'));
        }
    }
}
