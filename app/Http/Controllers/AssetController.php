<?php

namespace App\Http\Controllers;

use App\Asset;
use App\ChartOfAccount;
use App\PaymentMethod;
use App\AccountInformation;
use App\Repositories\AssetRepository;
use App\Http\Requests\AssetStoreRequest;
use Illuminate\Http\Request;
use App\Party;
use Message;

class AssetController extends Controller
{

    protected $assetRepo;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepo = $assetRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = Asset::assets()->paginate(25);
        return view('admin.accounting.assets.index', compact(['assets']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parties = Party::parties()->where('party_type', 2);
        $chart_of_accounts = ChartOfAccount::where('chart_type_id', 3)->charts();
        $payment_methods = PaymentMethod::methods()->get();
        $accounts = AccountInformation::accountInfo();
        return view('admin.accounting.assets.create', compact('parties', 'chart_of_accounts', 'payment_methods', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AssetStoreRequest $valid)
    {
        $valid->validated();

        $assets = $this->assetRepo->store($request);

        if($assets){
            return redirect('accounting/assets')->with('message', Message::created('asset'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        return view('admin.accounting.assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit(Asset $asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
       $asset = $this->assetRepo->destroy($asset);

       if($asset){
        return redirect('accounting/assets')->withMessage(Message::deleted('asset'));
       }
    }
}
