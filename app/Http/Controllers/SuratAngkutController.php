<?php

namespace App\Http\Controllers;

use App\Models\Orderan;
use App\Models\Surat_angkut;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class SuratAngkutController extends Controller
{

    public function index()
    {

        $orderans = Orderan::where('status', 1)->get();
        $customer = Orderan::select('nama_customer')
            ->where('sisa_jumlah_barang', '>', 0)
            ->where('status', 1)
            ->orderby('nama_customer', 'asc')
            ->distinct()->get();

        $penerima = Orderan::select('nama_penerima')
            ->where('sisa_jumlah_barang', '>', 0)
            ->where('status', 1)
            ->distinct()
            ->orderby('nama_penerima', 'asc')
            ->get();

        // $surat_angkut = Surat_angkut::where('jumlah_barang', 0)->get();

        return view('surat_angkut.index', compact('customer', 'penerima', 'orderans'));

    }
    public function sa_terkirim()
    {

        $orderans = Orderan::where('status', 1)->get();
        $customer = Orderan::select('nama_customer')
            ->where('sisa_jumlah_barang', '>', 0)
            ->where('status', 1)
            ->orderby('nama_customer', 'asc')
            ->distinct()->get();

        $penerima = Orderan::select('nama_penerima')
            ->where('sisa_jumlah_barang', '>', 0)
            ->where('status', 1)
            ->distinct()
            ->orderby('nama_penerima', 'asc')
            ->get();

        // $surat_angkut = Surat_angkut::where('jumlah_barang', 0)->get();

        return view('surat_angkut.sa_terkirim', compact('customer', 'penerima', 'orderans'));

    }

    public function data()
    {

        $surat_angkut = Surat_angkut::orderBy('created_at', 'desc')

            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')

            ->select('surat_angkuts.*', 'orderans.tagihan_by', 'surat_angkuts.status as status', 'orderans.tanggal_pengambilan as tanggal_pengambilan')
            ->where('surat_angkuts.status', 1)
            ->distinct()
            ->get();

        // dd($surat_angkut);

        return datatables()

            ->of($surat_angkut)

            ->addIndexColumn()

            ->addColumn('aksi', function ($surat_angkut) {
                $disabled = '';
                if (auth()->user()->level == 2 || auth()->user()->level == 3) {
                    if ($orderan->status > 1) {
                        $disabled = 'disabled';
                    }
                } else {
                    $disabled = 'enabled';
                }

                return '

                    <div class="btn-group">

                    <button type="button" onclick="showDetail(`' . route('surat_angkut.show', $surat_angkut->id_sa) . '`)" class="btn btn-xs btn-primary btn-flat" ><i class="fa fa-eye"></i></button>

                        <button type="button" onclick="editForm(`' . route('surat_angkut.update', $surat_angkut->id_sa) . '`)" class="btn btn-xs btn-info btn-flat" ' . $disabled . '><i class="fa fa-pencil"></i></button>

                        <button type="button" onclick="deleteData(`' . route('surat_angkut.destroy', $surat_angkut->id_sa) . '`)" class="btn btn-xs btn-danger btn-flat"' . $disabled . '><i class="fa fa-trash"></i></button>

                        <button type="button" onclick="exportPDF(`' . route('surat_angkut.exportPDF', $surat_angkut->id_sa) . '`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-book"></i></button>

                    </div>

                ';

            })

            ->rawColumns(['aksi'])

            ->make(true);

    }
    public function data2()
    {

        $surat_angkut = Surat_angkut::orderBy('created_at', 'desc')

            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')

            ->select('surat_angkuts.*', 'orderans.tagihan_by', 'surat_angkuts.status as status', 'orderans.tanggal_pengambilan as tanggal_pengambilan')
            ->where('surat_angkuts.status', 2)
            ->distinct()
            ->get();

        // dd($surat_angkut);
        return datatables()
            ->of($surat_angkut)
            ->addIndexColumn()
            ->addColumn('update_status', function ($surat_angkut) {
                return '
            <div class="btn-group">
                <button type="button" class="btn btn-xs btn-warning btn-flat" onclick="updateStatus(`' . route('surat_angkut.update_status', $surat_angkut->id_sa) . '`)">
                    <i class="fa fa-edit"></i> SA dikembalikan
                </button>
            </div>';
            })
            ->addColumn('aksi', function ($surat_angkut) {
                return '
            <div class="btn-group">
                <button type="button" class="btn btn-xs btn-primary btn-flat" onclick="showDetail(`' . route('surat_angkut.show', $surat_angkut->id_sa) . '`)">
                    <i class="fa fa-eye"></i>
                </button>
            </div>';
            })
            ->rawColumns(['update_status', 'aksi'])
            ->make(true);

    }

    public function update_status($id)
    {

        $surat_angkut = Surat_angkut::find($id);

        $orderans = Orderan::where('status', 1)->get();

        // dd( $surat_angkut);

        if ($surat_angkut) {

            $surat_angkut->status = 3;

            $surat_angkut->tanggal_kembali = now();

            $surat_angkut->update();

            $surat_angkuts = Orderan::all();

            $customer = Orderan::select('nama_customer')
                ->where('sisa_jumlah_barang', '>', 0)
                ->distinct()->get();

            $penerima = Orderan::select('nama_penerima')
                ->where('sisa_jumlah_barang', '>', 0)
                ->distinct()
                ->get();

            // $surat_angkut = Surat_angkut::where('jumlah_barang', 0)->get();

            return view('surat_angkut.sa_terkirim', compact('customer', 'penerima', 'orderans'));

        }
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
        $get_jumlah = $request->total_jumlah_barang;
        $orderan = Orderan::where('nama_customer', $get_customer)
            ->where('nama_penerima', $get_penerima)
            ->where('jumlah_barang', $get_jumlah)
            ->where('sisa_jumlah_barang', '>', 0)
            ->first();
        $get_sisa_jumlah_barang = $orderan->sisa_jumlah_barang;
        $status = 1;
        // dd($orderan);

        if (!empty($orderan)) {
            $sisa_jumlah_barang = "";
            if ($get_sisa_jumlah_barang == 0) {
                $sisa_jumlah_barang = $get_jumlah - $request->jumlah_barang;
            } else {
                $sisa_jumlah_barang = $get_sisa_jumlah_barang - $request->jumlah_barang;
            }

            $Surat_angkut = new Surat_angkut();

            $Surat_angkut->nomor_sa = $request->nomor_sa;

            $Surat_angkut->kode_tanda_penerima = $orderan->kode_tanda_penerima;

            $Surat_angkut->nama_customer = $orderan->nama_customer;

            $Surat_angkut->alamat_customer = $orderan->alamat_customer;

            $Surat_angkut->telepon_customer = $orderan->telepon_customer;

            $Surat_angkut->nama_barang = $orderan->nama_barang;

            $Surat_angkut->total_jumlah_barang = $request->total_jumlah_barang;

            $Surat_angkut->jumlah_barang = $request->jumlah_barang;

            $Surat_angkut->sisa_jumlah_barang = $sisa_jumlah_barang;

            // $Surat_angkut->berat_barang = $orderan->berat_barang;

            $Surat_angkut->nama_penerima = $orderan->nama_penerima;

            $Surat_angkut->alamat_penerima = $orderan->alamat_penerima;

            $Surat_angkut->telepon_penerima = $orderan->telepon_penerima;

            // $Surat_angkut->supir = $orderan->supir;

            // $Surat_angkut->no_mobil = $orderan->no_mobil;

            $Surat_angkut->keterangan = $orderan->keterangan;

            $Surat_angkut->tanggal_pengambilan = $orderan->tanggal_pengambilan;
            $Surat_angkut->status = $status;

            $Surat_angkut->save();

            $orderan->sisa_jumlah_barang = $get_jumlah - $request->jumlah_barang;
            if ($orderan->sisa_jumlah_barang == 0) {
                $orderan->status = 2;
                $orderan->update();
            } else {
                $orderan->update();
            }

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

        $surat_angkut = Surat_angkut::find($id);

        return response()->json($surat_angkut);

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

        $get_customer = $request->nama_customer;
        $get_penerima = $request->nama_penerima;
        $get_jumlah = $request->total_jumlah_barang;

        $orderan = Orderan::where('nama_customer', $get_customer)
            ->where('nama_penerima', $get_penerima)
            ->where('jumlah_barang', $get_jumlah)
            ->where('sisa_jumlah_barang', '>', 0)
            ->first();

        if (!empty($orderan)) {

            $Surat_angkut = new Surat_angkut();

            $Surat_angkut->nomor_sa = $request->nomor_sa;

            $Surat_angkut->kode_tanda_penerima = $orderan->kode_tanda_terima;

            $Surat_angkut->nama_customer = $orderan->nama_customer;

            $Surat_angkut->alamat_customer = $orderan->alamat_customer;

            $Surat_angkut->telepon_customer = $orderan->telepon_customer;

            $Surat_angkut->nama_barang = $orderan->nama_barang;

            $Surat_angkut->total_jumlah_barang = $request->total_jumlah_barang;

            $Surat_angkut->jumlah_barang = $request->jumlah_barang;

            $Surat_angkut->jumlah_sisa_barang = $get_jumlah - $request->jumlah_barang;

            // $Surat_angkut->berat_barang = $orderan->berat_barang;

            $Surat_angkut->nama_penerima = $orderan->nama_penerima;

            $Surat_angkut->alamat_penerima = $orderan->alamat_penerima;

            $Surat_angkut->telepon_penerima = $orderan->telepon_penerima;

            // $Surat_angkut->supir = $orderan->supir;

            // $Surat_angkut->no_mobil = $orderan->no_mobil;

            $Surat_angkut->keterangan = $orderan->keterangan;

            $Surat_angkut->tanggal_pengambilan = $orderan->tanggal_pengambilan;

            // $Surat_angkut->harga = $orderan->harga;

            $Surat_angkut->save();

            $orderan->sisa_jumlah_barang = $get_jumlah - $request->jumlah_barang;
            $orderan->update();

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

        $surat_angkut = Surat_angkut::find($id);

        $orderan = Orderan::where('kode_tanda_penerima', $surat_angkut->kode_tanda_terima)->first();

        $surat_angkut->delete();

        return response(null, 204);

    }

    public function deleteSelected(Request $request)
    {

        foreach ($request->surat_angkut as $id) {

            $surat_angkut = Surat_angkut::find($id);

            $surat_angkut->delete();

        }

        return response(null, 204);

    }

    public function exportCSV()
    {

        $surat_angkut = Surat_angkut::get()->toArray();

        $headers = [

            'Content-Type' => 'text/csv',

            'Content-Disposition' => 'attachment; filename="sa_' . date('Ymd_His') . '.csv"',

        ];

        $callback = function () use ($surat_angkut) {

            $file = fopen('php://output', 'w');

            fputcsv($file, array_keys($surat_angkut[0]));

            foreach ($surat_angkut as $row) {

                fputcsv($file, $row);

            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);

    }

    public function exportPDF($id)
    {
        $surat_angkut = Surat_angkut::find($id);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('surat_angkut.pdf', compact('surat_angkut'))->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Menghasilkan file PDF dan mengirimkan ke browser
        $pdfFileName = 'surat_angkut' . $surat_angkut->nomor_sa . $surat_angkut->created_at . '.pdf';
        $dompdf->stream($pdfFileName);

    }

}
