<?php

namespace App\Http\Controllers;

use App\Models\Daftar_muat;
use App\Models\Orderan;
use Illuminate\Http\Request;

class DaftarMuatController extends Controller
{
    public function index()
    {
        return view('daftar_muat.index');
        // ini index
    }

    public function data()
    {
        $daftar_muat = Daftar_muat::get();

        return datatables()
            ->of($daftar_muat)
            ->addIndexColumn()
            ->addColumn('aksi', function ($daftar_muat) {
                return '
                <div class="btn-group">

                    <button type="button" onclick="deleteData(`' . route('dm.destroy', $daftar_muat->id_dm) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $get_orderan = $request->kode_tanda_penerima;

        $orderan = Orderan::where('kode_tanda_penerima', $get_orderan)->first();

        if (!empty($orderan)) {
            $daftar_muat = new Daftar_muat();
            $daftar_muat->nomor_sa = $request->nomor_sa;
            $daftar_muat->kode_tanda_penerima = $request->kode_tanda_penerima;
            $daftar_muat->nama_customer = $orderan->nama_customer;
            $daftar_muat->alamat_customer = $orderan->alamat_customer;
            $daftar_muat->telepon_customer = $orderan->telepon_customer;
            $daftar_muat->nama_barang = $orderan->nama_barang;
            $daftar_muat->jumlah_barang = $orderan->jumlah_barang;
            $daftar_muat->berat_barang = $orderan->berat_barang;
            $daftar_muat->nama_penerima = $orderan->nama_penerima;
            $daftar_muat->alamat_penerima = $orderan->alamat_penerima;
            $daftar_muat->telepon_penerima = $orderan->telepon_penerima;
            $daftar_muat->supir = $orderan->supir;
            $daftar_muat->no_mobil = $orderan->no_mobil;
            $daftar_muat->keterangan = $orderan->keterangan;
            $daftar_muat->tanggal_pengambilan = $orderan->tanggal_pengambilan;
            $daftar_muat->harga = $orderan->harga;
            $daftar_muat->save();
            return response()->json('berhasil', 200);
        } else {
            return response()->json('gagal', 200);
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
        $daftar_muat = Daftar_muat::find($id);

        return response()->json($daftar_muat);
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
        $get_orderan = $request->kode_tanda_penerima;

        $orderan = Orderan::where('kode_tanda_penerima', $get_orderan)->first();
        if (!empty($orderan)) {
            $daftar_muat = Daftar_muat::find($id);
            $berat_barang = $orderan->berat_barang;
            $daftar_muat->nomor_sa = $request->nomor_sa;
            $daftar_muat->kode_tanda_penerima = $request->kode_tanda_penerima;
            $daftar_muat->nama_customer = $request->nama_customer;
            $daftar_muat->alamat_customer = $request->alamat_customer;
            $daftar_muat->telepon_customer = $request->telepon_customer;
            $daftar_muat->nama_barang = $request->nama_barang;
            $daftar_muat->jumlah_barang = $orderan->jumlah_barang;
            $daftar_muat->berat_barang = $orderan->berat_barang;
            $daftar_muat->nama_penerima = $orderan->nama_penerima;
            $daftar_muat->alamat_penerima = $orderan->alamat_penerima;
            $daftar_muat->telepon_penerima = $orderan->telepon_penerima;
            $daftar_muat->supir = $orderan->supir;
            $daftar_muat->no_mobil = $orderan->no_mobil;
            $daftar_muat->keterangan = $orderan->keterangan;
            $daftar_muat->tanggal_pengambilan = $orderan->tanggal_pengambilan;
            $daftar_muat->harga = $orderan->harga;
            $daftar_muat->update();
            return response()->json('berhasil', 200);
        } else {
            return response()->json('gagal', 200);
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
        $daftar_muat = Daftar_muat::find($id)->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->daftar_muat as $id) {
            $daftar_muat = Daftar_muat::find($id);
            $daftar_muat->delete();
        }

        return response(null, 204);
    }

    // public function exportCSV()
    // {

    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment; filename="dm_' . date('Ymd_His') . '.csv"',
    //     ];

    //     $callback = function () use ($daftar_muat) {
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, array_keys($daftar_muat[0]));
    //         foreach ($daftar_muat as $row) {
    //             fputcsv($file, $row);
    //         }
    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }

    public function exportCSV()
    {
        $daftar_muat = Daftar_muat::get()->toArray();

        $total_berat = 0;
        $total_semua_harga = 0;
        foreach ($daftar_muat as $dm) {
            $total_berat += $dm['berat_barang'];
            $total_semua_harga += $dm['total_harga'];
        }
        $daftar_muat[] = ['', '', '', '', '', ''];
        $daftar_muat[] = ['Total Berat', $total_berat, '', '', '', ''];
        $daftar_muat[] = ['Total Semua Harga', $total_semua_harga, '', '', ''];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dm_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($daftar_muat) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($daftar_muat[0]));
            foreach ($daftar_muat as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function exportfilter(Request $request)
    {
        $kode_daftar_muat = $request->kode_daftar_muat;
        $nomor_sa = $request->nomor_sa;
        $supir = $request->supir;
        $no_mobil = $request->no_mobil;
        $nama_customer = $request->nama_customer;
        $nama_penerima = $request->nama_penerima;

        $daftar_muat = Daftar_muat::when($kode_daftar_muat, function ($query) use ($kode_daftar_muat) {
            return $query->where('kode_daftar_muat', $kode_daftar_muat);
        })
            ->when($nomor_sa, function ($query) use ($nomor_sa) {
                return $query->where('nomor_sa', $nomor_sa);
            })
            ->when($supir, function ($query) use ($supir) {
                return $query->where('supir', $supir);
            })
            ->when($no_mobil, function ($query) use ($no_mobil) {
                return $query->where('no_mobil', $no_mobil);
            })
            ->when($nama_customer, function ($query) use ($nama_customer) {
                return $query->where('nama_customer', $nama_customer);
            })
            ->when($nama_penerima, function ($query) use ($nama_penerima) {
                return $query->where('nama_penerima', $nama_penerima);
            })
            ->get()->toArray();

        $total_berat = 0;
        $total_semua_harga = 0;
        foreach ($daftar_muat as $dm) {
            $total_berat += $dm['berat_barang'];
            $total_semua_harga += $dm['total_harga'];
        }
        $daftar_muat[] = ['', '', '', '', '', ''];
        $daftar_muat[] = ['Total Berat', $total_berat, '', '', '', ''];
        $daftar_muat[] = ['Total Semua Harga', $total_semua_harga, '', '', ''];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dm_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($daftar_muat) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($daftar_muat[0]));
            foreach ($daftar_muat as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
