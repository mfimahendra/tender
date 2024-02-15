<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderDetail;
use App\Models\Criteria;
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

        $criteria_data = CriteriaData::where('tender_id', '=', $tender_id)
            ->join('criteria_masters', 'criteria_masters.criteria_code', '=', 'criteria_details.criteria_code')
            ->select('criteria_details.*', 'criteria_masters.criteria_name')
            ->get();
        
        $criteria_values = CriteriaValue::where('tender_id', '=', $tender_id)->get();

        $criteria_masters = Criteria::get();        

        return view ('tender.detail', [
            'tender' => $tender,
            'vendor_lists' => $vendor_lists,
            'criteria_data' => $criteria_data,
            'criteria_values' => $criteria_values,
            'criteria_masters' => $criteria_masters,
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

    public function saveCriteriaData(Request $request)
    {
        try {
            $tender_id = $request->tender_id;
            $criteria_code = $request->criteria_code;
            $vendor_id = $request->vendor_id;
            $criteria_value = $request->criteria_value;

            $criteria_data = CriteriaData::where('tender_id', '=', $tender_id)
                ->where('criteria_code', '=', $criteria_code)
                ->where('vendor_id', '=', $vendor_id)
                ->first();

            if ($criteria_data == null) {
                $criteria_data = new CriteriaData();
                $criteria_data->tender_id = $tender_id;
                $criteria_data->criteria_code = $criteria_code;
                $criteria_data->vendor_id = $vendor_id;
                $criteria_data->criteria_value = $criteria_value;
                $criteria_data->save();
            } else {
                $criteria_data->criteria_value = $criteria_value;
                $criteria_data->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan'
            ]);
        }
    }
}
