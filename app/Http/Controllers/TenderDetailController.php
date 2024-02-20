<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderDetail;
use App\Models\Criteria;
use App\Models\CriteriaData;
use App\Models\CriteriaValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $tender_id = $request->tender_id;                        
            $criteria_data = $request->criteria_data;

            $master_criteria = Criteria::get();

            // foreach set criteria_code from criteria_data as key then add criteria_type from master criteria to criteria_data array
            foreach ($criteria_data as $key => $value) {
                $criteria_data[$key]['criteria_type'] = $master_criteria->where('criteria_code', $value['criteria_code'])->first()->criteria_type;
            }

            CriteriaData::where('tender_id', '=', $tender_id)->delete();

            foreach ($criteria_data as $key => $value) {                
                $criteria_first = CriteriaData::insert([
                    'tender_id' => $tender_id,
                    'criteria_code' => $value['criteria_code'],
                    'criteria_weight' => $value['criteria_weight'],                    
                    'criteria_type' => $value['criteria_type'],
                    'remark' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }            

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Data gagal disimpan' . $e->getMessage()
            ]);
        }
    }
}
