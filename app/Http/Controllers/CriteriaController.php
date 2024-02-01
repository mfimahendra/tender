<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criteria = Criteria::get();

        return view ('criteria.index', [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('criteria.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            if($request->input('criteria_name') == null){
                $response = array(
                    'status' => false,
                    'message' => 'Nama Kriteria tidak boleh kosong.'
                );

                return Response::json($response);
            }

            if($request->input('criteria_type') == null){
                $response = array(
                    'status' => false,
                    'message' => 'Tipe Kriteria tidak boleh kosong.'
                );

                return Response::json($response);
            }

            if($request->input('uom') == null){
                $response = array(
                    'status' => false,
                    'message' => 'UOM tidak boleh kosong.'
                );

                return Response::json($response);
            }

            $id_generator = IdGenerator::where('remark', '=', 'criteria')->first();
            $criteria_code = $id_generator->prefix . sprintf("%'.0" . $id_generator->length . "d", $id_generator->index);

            $criteria_name = $request->input('criteria_name');
            $criteria_type = $request->input('criteria_type');
            $uom = $request->input('uom');
            $remark = $request->input('remark');
            
            $insert_criteria = Criteria::insert([
                'criteria_code' => $criteria_code,
                'criteria_name' => $criteria_name,
                'criteria_type' => $criteria_type,
                'uom' => $uom,
                'remark' => $remark,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            IdGenerator::where('remark', '=', 'criteria')->update(['index' => $id_generator->index + 1]);
            
            DB::commit();

            $response = array(
                'status' => true,
                'message' => 'Kriteria berhasil ditambah.',
            );

            return Response::json($response);
        } catch (\Throwable $e){
            DB::rollback();
            
            $response = array(
                'status' => false,
                'message' => 'Kriteria gagal ditambah.',
            );

            return Response::json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $criteria = Criteria::find($id);

        return view('criteria.edit', 
        ['criteria'=> $criteria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{

            $criteria = Criteria::find($id);

            $criteria->criteria_name = $request->get('criteria_name');
            $criteria->criteria_type = $request->get('criteria_type');
            $criteria->uom = $request->get('uom');
            $criteria->remark = $request->get('remark');

            $update_criteria = Criteria::find($id)->update([
                'criteria_name' => $criteria->criteria_name,
                'criteria_type' => $criteria->criteria_type,
                'uom' => $criteria->uom,
                'remark' => $criteria->remark,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $response = array(
                'status' => true,
                'message' => 'Kriteria berhasil diubah.',
            );

            return Response::json($response);
        } catch (\Throwable $e) {
            
            $response = array(
                'status' => false,
                'message' => 'Kriteria gagal diubah.',
            );
            return Response::json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $criteria = Criteria::find($id);

            if (!$criteria) {
                return redirect()->route('criteria.index')->with('error', 'criteria not found.');
            } else{
                $criteria->delete();

                return redirect()->route('criteria.index')->with('success', 'criteria deleted successfully.');
            }

        } catch (\Throwable $th) {
            return redirect()->route('criteria.index')->with('error', $th->getMessage());
        }
    }

    // add CRUD function here of Criteria list & details, and Edit Criteria Values    
}
