<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerima;

class PenerimaController extends Controller
{
    public function index()
    {
        return view('penerima.index');
    }

    public function data()
    {
        $penerima = Penerima::orderBy('id_penerima', 'desc')->get();

        return datatables()
            ->of($penerima)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penerima) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('penerima.update', $penerima->id_penerima) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('penerima.destroy', $penerima->id_penerima) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $penerima = Penerima::create($request->all());

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
        $penerima = Penerima::find($id);

        return response()->json($penerima);
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
        $penerima = Penerima::find($id)->update($request->all());

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
        $penerima = Penerima::find($id)->delete();

        return response(null, 204);
    }
}
