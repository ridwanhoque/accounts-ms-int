<?php

namespace App\Http\Controllers;

use App\DirectProduction;
use Illuminate\Http\Request;
use App\Http\Requests\DirectProductionStoreRequest;
use App\Repositories\DirectProductionRepository;
use Message;

class DirectProductionController extends Controller
{
    protected $directProductionRepo;

    public function __construct(DirectProductionRepository $dpr)
    {
        $this->directProductionRepo = $dpr;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $direct_productions = DirectProduction::where('company_id', auth()->user()->company_id)->paginate(25);
        return view('admin.direct_productions.index', compact('direct_productions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DirectProductionStoreRequest $request)
    {
        $request->validated();

        $temporary_direct_production = $this->directProductionRepo->store($request);

        if ($temporary_direct_production) {
            return redirect('direct_productions')->with('message', Message::created('direct_production'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DirectProduction  $directProduction
     * @return \Illuminate\Http\Response
     */
    public function show(DirectProduction $directProduction)
    {
        return view('admin.direct_productions.show', compact('directProduction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DirectProduction  $directProduction
     * @return \Illuminate\Http\Response
     */
    public function edit(DirectProduction $directProduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DirectProduction  $directProduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DirectProduction $directProduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DirectProduction  $directProduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(DirectProduction $directProduction)
    {
        $direct_productions = $this->directProductionRepo->destroy($directProduction);

        if ($direct_productions) {
            return redirect('direct_productions')->with('message', Message::deleted('direct_production'));
        }
    }
}
