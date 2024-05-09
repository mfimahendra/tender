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
            ->select('criteria_details.*', 'criteria_masters.criteria_name', 'criteria_masters.uom')
            ->get();

        //tanda Salwa
        // $data_value_criteria = CriteriaData::where('criteria_details.tender_id', '=', $tender_id) 
        //     ->leftjoin('criteria_values', 'criteria_details.criteria_code', '=', 'criteria_values.criteria_code', 'criteria_details.tender_id', '=', 'criteria_details.criteria_code')
        //     ->leftjoin('criteria_masters', 'criteria_details.criteria_code', '=', 'criteria_masters.criteria_code')
        //     ->select('criteria_values.vendor_id', 'criteria_values.value', 'criteria_masters.criteria_name', 'criteria_details.tender_id', 'criteria_masters.uom')
        //     ->get(); 

        // $data_value_criteria = CriteriaData::where('criteria_details.tender_id', '=', $tender_id)
        //     ->select('criteria_details.tender_id', 'criteria_values.vendor_id', 'criteria_details.criteria_code', 'criteria_masters.criteria_name', 'criteria_values.value', 'criteria_masters.uom')
        //     ->leftJoin('criteria_values', function ($join) {
        //         $join->on('criteria_details.tender_id', '=', 'criteria_values.tender_id')
        //             ->on('criteria_details.criteria_code', '=', 'criteria_values.criteria_code');
        //     })
        //     ->join('criteria_masters', 'criteria_details.criteria_code', '=', 'criteria_masters.criteria_code')
        //     ->get();

        $data_value_criteria = DB::table('criteria_details as cd')
            ->select('cd.tender_id', 'td.vendor_id', 'cd.criteria_code', 'cm.criteria_name', DB::raw('COALESCE(cv.value, null) AS value'), 'cm.uom')
            ->join('criteria_masters as cm', 'cd.criteria_code', '=', 'cm.criteria_code')
            ->join('tender_details as td', 'cd.tender_id', '=', 'td.tender_id')
            ->leftJoin('criteria_values as cv', function ($join) {
                $join->on('cd.tender_id', '=', 'cv.tender_id')
                    ->on('td.vendor_id', '=', 'cv.vendor_id')
                    ->on('cd.criteria_code', '=', 'cv.criteria_code');
            })
            ->where('cd.tender_id', $tender_id)
            ->get();




        $criteria_values = CriteriaValue::where('tender_id', '=', $tender_id)->get();

        $criteria_masters = Criteria::get();

        return view('tender.detail', [
            'tender' => $tender,
            'vendor_lists' => $vendor_lists,
            'criteria_data' => $criteria_data,
            'criteria_values' => $criteria_values,
            'criteria_masters' => $criteria_masters,
            'data_value_criteria' => $data_value_criteria
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
    public function destroy($id)
    {
        try {
            $tender_detail = TenderDetail::where('id', '=', $id)->first();

            if (!$tender_detail) {
                return redirect()->route('tender_detail', ['id' => $tender_detail->tender_id])->with('error', 'Vendor not found.');
            } else {
                $tender_detail->delete();

                return redirect()->route('tender.detail', ['id' => $tender_detail->tender_id])->with('success', 'Vendor deleted successfully.');
            }
        } catch (\Throwable $th) {
            return redirect()->route('tender.detail', ['id' => $tender_detail->tender_id])->with('error', $th->getMessage());
        }
    }


    public function saveCriteriaValue(Request $request)
    {
        DB::beginTransaction();
        try {
            $tender_id = $request->tender_id;
            $vendor_id = $request->vendor_id;
            $criteria_value = $request->criteria_value;

            $master_criteria = Criteria::get();

            // foreach set criteria_code from criteria_data as key then add criteria_type from master criteria to criteria_data array
            foreach ($criteria_value as $key => $value) {
                $criteria_value[$key]['type'] = $master_criteria->where('criteria_code', $value['criteria_code'])->first()->criteria_type;
                $criteria_value[$key]['uom'] = $master_criteria->where('criteria_code', $value['criteria_code'])->first()->uom;
            }

            // CriteriaValue::where('tender_id', '=', $tender_id, 'vendor_id', '=', $vendor_id)->delete();
            CriteriaValue::where([
                ['tender_id', '=', $tender_id],
                ['vendor_id', '=', $vendor_id]
            ])->delete();

            foreach ($criteria_value as $key => $value) {
                $criteria_first = CriteriaValue::insert([
                    'tender_id' => $tender_id,
                    'vendor_id' => $vendor_id,
                    'criteria_code' => $value['criteria_code'],
                    'value' => $value['value'],
                    'type' => $value['type'],
                    'uom' => $value['uom'],
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

    public function saveCriteriaData(Request $request)
    {
        DB::beginTransaction();
        try {
            $tender_id = $request->tender_id;
            $criteria_data = $request->criteria_data;

            $master_criteria = Criteria::get();

            // criteria_code -> key
            // criteria_weight
            // criteria_type buat nyari criteria_type dari master criteria

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

    public function fetchAllCriteriaValuesByTenderId($tender_id)
    {
        try {
            // $criteria_values = CriteriaValue::where('tender_id', '=', $tender_id)->get();

            $criteria_values = CriteriaValue::where('tender_id', $tender_id)
                ->Join('vendors', 'criteria_values.vendor_id', '=', 'vendors.vendor_id')
                ->select('criteria_values.*', 'vendors.vendor_name')
                ->get();


            $response = [
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => $criteria_values
            ];

            return response()->json($response);
        } catch (\Throwable $e) {

            $response = [
                'status' => false,
                'message' => 'Data gagal diambil' . $e->getMessage(),
            ];

            return response()->json($response);
        }
    }

    public function scoring($tender_id)
    {
        $tender = Tender::where('tender_id', '=', $tender_id)->first();

        return view(
            'tender.scoring',
            ['tender' => $tender]
        );
    }
}
