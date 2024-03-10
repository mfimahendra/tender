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
        $tender_lists = Tender::where('status', '=', 'active')->get();

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
    public function edit($tender_id)
    {                        
        try {            
            $tender = Tender::where('tender_id', '=', $tender_id)->first();

            if ($tender == null) {
                abort(404);
            }

            $vendor_lists = Vendor::get();
            
            $vendor_selected = TenderDetail::where('tender_id', '=', $tender_id)
            ->join('vendors', 'tender_details.vendor_id', '=', 'vendors.vendor_id')
            ->select('tender_details.*', 'vendors.vendor_name')
            ->get();

            $vendor_unselected = Vendor::whereNotIn('vendor_id', $vendor_selected->pluck('vendor_id'))->get();        

            return view ('tender.edit', [
                'tender'=>$tender,
                'vendor_lists' => $vendor_lists,
                'vendor_selected' => $vendor_selected,
                'vendor_unselected' => $vendor_unselected
            ]);
        } catch (\Throwable $e) {
            abort(404);
        }
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
    public function destroy($tender_id)
    {
        try {
            $tender = Tender::where('tender_id', '=', $tender_id)->first();
            $tender_detail = TenderDetail::where('tender_id', '=', $tender_id)->get();

            if (!$tender) {
                return redirect()->route('tender.index')->with('error', 'tender not found.');
            } else{
                $tender->delete();
                
                foreach ($tender_detail as $detail) {
                    $detail->delete();
                }

                return redirect()->route('tender.index')->with('success', 'tender deleted successfully.');
            }

        } catch (\Throwable $th) {
            return redirect()->route('tender.index')->with('error', $th->getMessage());
        }
    }    
}
