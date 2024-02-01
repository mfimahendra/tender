<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderDetail;
use App\Models\CriteriaData;
use App\Models\CriteriaValue;
use Illuminate\Http\Request;

class TenderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tender_id)
    {
        $tender = Tender::get()->where('tender_id', $tender_id)->first();
        $vendor_lists = TenderDetail::where('tender_id', '=', $tender_id)
            ->join('vendors', 'tender_details.vendor_id', '=', 'vendors.vendor_id')
            ->select('tender_details.*', 'vendors.vendor_name')
            ->get();

        $criteria_id = $tender->criteria_id;                

        $criteria_data = CriteriaData::where('criteria_id', '=', $criteria_id)
            ->join('criteria_masters', 'criteria_masters.criteria_code', '=', 'criterias.criteria_code')
            ->select('criterias.*', 'criteria_masters.criteria_name')
        ->get();
        $criteria_values = CriteriaValue::where('tender_id', '=', $tender_id)->get();

        return view ('tender.detail', [
            'tender' => $tender,
            'vendor_lists' => $vendor_lists,
            'criteria_data' => $criteria_data,
            'criteria_values' => $criteria_values,
            'criteria_id' => $criteria_id
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TenderDetail  $tenderDetail
     * @return \Illuminate\Http\Response
     */
    public function show(TenderDetail $tenderDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TenderDetail  $tenderDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(TenderDetail $tenderDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TenderDetail  $tenderDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TenderDetail $tenderDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TenderDetail  $tenderDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(TenderDetail $tenderDetail)
    {
        //
    }
}
