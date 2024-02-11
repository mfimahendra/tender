<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderDetail;
use App\Models\Vendor;
use App\Models\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tender_lists = Tender::where('status', '=', 'active')
        ->get();

        return view ('tender.index', [
            'tender_lists' => $tender_lists,
        ]);        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $id_generator = IdGenerator::where('remark', '=', 'tender')->first();
        $default_new_id = $id_generator->prefix . sprintf("%'.0" . $id_generator->length . "d", $id_generator->index);

        $vendor_lists = Vendor::get();

        return view ('tender.create', [
            'default_new_id' => $default_new_id,
            'vendor_lists' => $vendor_lists
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        try {
            DB::beginTransaction();

            // validator
            if ($request->input('tender_name') == null) {
                $response = array(
                    'status' => false,
                    'message' => 'Nama Tender tidak boleh kosong.'
                );

                return Response::json($response);
            }

            if ($request->input('tender_date') == null) {
                $response = array(
                    'status' => false,
                    'message' => 'Tanggal Tender tidak boleh kosong.'
                );

                return Response::json($response);
            }

            if($request->input('vendor_lists') == null) {
                $response = array(
                    'status' => false,
                    'message' => 'Vendor tidak boleh kosong.'
                );

                return Response::json($response);
            }

            $id_generator = IdGenerator::where('remark', '=', 'tender')->first();
            $tender_id = $id_generator->prefix . sprintf("%'.0" . $id_generator->length . "d", $id_generator->index);
            $tender_name = $request->input('tender_name');
            
            // tender_date 
            $tender_date = $request->input('tender_date');            
            $tender_date = date('Y-m-d', strtotime($tender_date));

            $insert_tender = Tender::insert([
                'tender_id' => $tender_id,
                'tender_name' => $tender_name,
                'tender_date' => $tender_date,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $vendor_lists = $request->input('vendor_lists');         

            foreach ($vendor_lists as $vendor_id) {
                $insert_tender_details = TenderDetail::insert([
                    'tender_id' => $tender_id,
                    'vendor_id' => $vendor_id,
                    'score' => 0,
                    'date' => $tender_date,
                    'status' => 'active',
                    'remark' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            IdGenerator::where('remark', '=', 'tender')->update(['index' => $id_generator->index + 1]);
            
            DB::commit();

            $response = array(
                'status' => true,
                'message' => 'Tender berhasil dibuat.',
            );

            return Response::json($response);

        } catch (\Throwable $e) {
            DB::rollback();
            
            $response = array(
                'status' => false,
                'message' => 'Tender gagal dibuat.',
            );

            return Response::json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function show(Tender $tender)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function edit(Tender $tender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tender $tender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tender  $tender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tender $tender)
    {
        //
    }
}
