<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harga;
use App\Models\Customer;
use App\Models\Penerima;

class HargaController extends Controller
{
    public function index()
    {
        $customers = Customer::orderby('nama_customer','asc')->get();
        $penerimas = Penerima::orderby('nama_penerima','asc')->get();
                
        return view('harga.index', compact('customers', 'penerimas'));
    }

    public function data()
    {
        $harga = Harga::orderBy('id_harga', 'desc')->get();

        return datatables()
            ->of($harga)
            ->addIndexColumn()
            ->addColumn('aksi', function ($harga) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('harga.update', $harga->id_harga) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('harga.destroy', $harga->id_harga) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
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

        
        $get_customer = $request->nama_customer;
        $get_penerima = $request->nama_penerima;

        $customer = Customer::where('nama_customer', $get_customer)->first();
        $penerima = Penerima::where('nama_penerima', $get_penerima)->first();

        if(!empty($customer)||!empty($penerima)){
            $harga = new Harga();
            $harga->nama_customer = $request->nama_customer;
            $harga->alamat_customer = $customer->alamat_customer;
            $harga->nama_penerima = $request->nama_penerima;
            $harga->alamat_penerima = $penerima->alamat_penerima;
            $harga->harga_roll = $request->harga_roll;
            $harga->harga_ball = $request->harga_ball;
            $harga->harga_tonase = $request->harga_tonase;
            $harga->main_syarat_berat = $request->main_syarat_berat;
            $harga->sub_syarat_berat = $request->sub_syarat_berat;
            $harga->syarat_jumlah = $request->syarat_jumlah;
            $harga->diskon_roll = $request->diskon_roll;
            $harga->diskon_ball = $request->diskon_ball;
         
            $harga->diskon_tonase_sub = $request->diskon_tonase_sub;

            $harga->save();
        }
        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $harga = Harga::find($id);

        return response()->json($harga);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $harga = Harga::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $harga = Harga::find($id)->delete();

        return response(null, 204);
    }
}
