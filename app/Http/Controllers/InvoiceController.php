<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Orderan;
use App\Models\Surat_angkut;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $orderans = Surat_angkut::where('surat_angkuts.status', 3);
        return view('invoice.index', compact('orderans'));
    }

    public function data()
    {
        $invoices = Invoice::select([
            'invoices.id as invoice_id',
            'invoices.nama as nama',
            'invoices.nomor_kwitansi',
            'invoices.nomor_invoice',
            'invoices.tanggal_lunas',
            'invoices.tagihan_by',
            DB::raw('CASE
        WHEN invoices.status = 1 THEN "Bulanan"
        WHEN invoices.status = 2 THEN "Mingguan"
        ELSE ""
    END AS status_periode'),
        ])->get();

        return datatables()
            ->of($invoices) // Use $invoiceData instead of $invoices
            ->addIndexColumn()
            ->addColumn('update_status', function ($invoice) {
                return '<button type="button" onclick="updateStatus(`' . route('invoice.update_status', $invoice->invoice_id) . '`) " class="btn btn-xs btn-warning btn-flat" ><i class="fa fa-edit"></i> Lunas </button>';
            })
            ->rawColumns(['update_status'])
            ->make(true);

    }
    public function update_status(Request $request)
    {
        $invoice = Invoice::find($request->id);

        if (!$invoice) {
            // Handle the case where the invoice is not found
            return redirect()->route('invoice.index')->with('error', 'Invoice not found');
        }

        $surat_angkut = Surat_angkut::whereIn('nomor_sa', [$invoice->sa_id])->get();

        if ($surat_angkut->isNotEmpty()) {
            foreach ($surat_angkut as $sa) {
                $sa->status = 5;
                $sa->update();
            }

            $invoice->tanggal_lunas = now();
            $invoice->update();

            $orderans = Orderan::whereIn('kode_tanda_penerima', $surat_angkut->pluck('kode_tanda_penerima'))->get();

            foreach ($orderans as $orderan) {
                $orderan->status = 5;
                $orderan->update();
            }

            return view('invoice.index', compact('orderans'));
        } else {
            // Handle the case where Surat_angkut is not found
            return redirect()->route('invoice.index')->with('error', 'Surat_angkut not found');
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

    // public function store(Request $request)
    // {

    //     $get_orderan = $request->kode_tanda_penerima;

    //     $orderan = Orderan::where('kode_tanda_penerima', $get_orderan)->first();

    //     if(!empty($orderan)){
    //         $Surat_angkut = new Surat_angkut();
    //         $Surat_angkut->nomor_sa = $request->nomor_sa;
    //         $Surat_angkut->kode_tanda_penerima = $request->kode_tanda_penerima;
    //         $Surat_angkut->nama_customer = $orderan->nama_customer;
    //         $Surat_angkut->alamat_customer = $orderan->alamat_customer;
    //         $Surat_angkut->telepon_customer = $orderan->telepon_customer;
    //         $Surat_angkut->nama_barang = $orderan->nama_barang;
    //         $Surat_angkut->jumlah_barang = $orderan->jumlah_barang;
    //         $Surat_angkut->berat_barang = $orderan->berat_barang;
    //         $Surat_angkut->nama_penerima = $orderan->nama_penerima;
    //         $Surat_angkut->alamat_penerima = $orderan->alamat_penerima;
    //         $Surat_angkut->telepon_penerima = $orderan->telepon_penerima;
    //         $Surat_angkut->supir = $orderan->supir;
    //         $Surat_angkut->no_mobil = $orderan->no_mobil;
    //         $Surat_angkut->keterangan = $orderan->keterangan;
    //         $Surat_angkut->tanggal_pengambilan = $orderan->tanggal_pengambilan;
    //         $Surat_angkut->harga = $orderan->harga;

    //         $Surat_angkut->save();

    //         if(!empty($Surat_angkut)){
    //             $daftar_muat = new Daftar_muat();
    //             // Memeriksa apakah ada data di database dengan nilai supir dan no_mobil yang sama
    //             $existing_daftar_muat = Daftar_muat::select('kode_daftar_muat')->where('supir', $orderan->supir)
    //             ->where('no_mobil', $orderan->no_mobil)
    //             ->first();

    //             if ($existing_daftar_muat) {
    //             // Jika data sudah ada, gunakan kode_daftar_muat yang sama dengan data yang ada
    //             $daftar_muat->kode_daftar_muat = $existing_daftar_muat->kode_daftar_muat;
    //             } else {
    //             // Jika data tidak ada, jalankan langkah-langkah seperti biasa

    //             $max_kode = Daftar_muat::max('kode_daftar_muat');
    //             $new_kode = $max_kode + 1;

    //             $daftar_muat->kode_daftar_muat = $new_kode;
    //             }
    //         $total_harga = ($orderan->jumlah_barang * $orderan->berat_barang) * $orderan->harga;

    //         $daftar_muat->nomor_sa = $request->nomor_sa;
    //         $daftar_muat->supir = $orderan->supir;
    //         $daftar_muat->no_mobil = $orderan->no_mobil;
    //         $daftar_muat->nama_customer = $orderan->nama_customer;
    //         $daftar_muat->nama_penerima = $orderan->nama_penerima;
    //         $daftar_muat->jumlah_barang = $orderan->jumlah_barang;
    //         $daftar_muat->berat_barang = $orderan->berat_barang;
    //         $daftar_muat->harga = $orderan->harga;
    //         $daftar_muat->total_harga =$total_harga;

    //         $daftar_muat->keterangan = $orderan->keterangan;
    //         $daftar_muat->save();

    //         }
    //         if (!empty($daftar_muat)){
    //             $parties = new parties();
    //             // Memeriksa apakah ada data di database dengan nilai supir dan no_mobil yang sama
    //             $existing_parties = parties::select('kode_invoices')
    //             ->where('nama_customer', $orderan->nama_customer)
    //             ->where('nama_penerima', $orderan->nama_penerima)
    //             ->first();

    //             if ($existing_parties) {
    //             // Jika data sudah ada, gunakan kode_daftar_muat yang sama dengan data yang ada
    //             $parties->kode_invoices = $existing_parties->kode_invoices;
    //             } else {
    //             // Jika data tidak ada, jalankan langkah-langkah seperti biasa

    //             $max_kode = parties::max('kode_invoices');
    //             $new_kode = $max_kode + 1;

    //             $parties->kode_invoices =  $new_kode;
    //             }
    //             $total_harga = ($orderan->jumlah_barang * $orderan->berat_barang) * $orderan->harga;

    //             $parties->kode_dm = $daftar_muat->kode_daftar_muat;
    //             $parties->nomor_sa = $request->nomor_sa;
    //             $parties->nama_customer = $orderan->nama_customer;
    //             $parties->alamat_customer = $orderan->alamat_customer;
    //             $parties->telepon_customer = $orderan->telepon_customer;

    //             $parties->jumlah_barang = $orderan->jumlah_barang;
    //             $parties->berat_barang = $orderan->berat_barang;

    //             $parties->nama_penerima = $orderan->nama_penerima;
    //             $parties->alamat_penerima = $orderan->alamat_penerima;
    //             $parties->telepon_penerima = $orderan->telepon_penerima;

    //             $parties->supir = $orderan->supir;
    //             $parties->no_mobil = $orderan->no_mobil;
    //             $parties->keterangan = $orderan->keterangan;

    //             $parties->save();

    //         }
    //         return response()->json('berhasil', 200);
    //     }else{
    //         return response()->json('gagal', 200);
    //     }

    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $surat_angkut = Surat_angkut::find($id);

    //     return response()->json($surat_angkut);
    // }

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
    // public function update(Request $request, $id)
    // {
    //     $get_orderan = $request->kode_tanda_penerima;

    //     $orderan = Orderan::where('kode_tanda_penerima', $get_orderan)
    //     ->where('status', 2)->first();

    //     if(!empty($orderan)){
    //         $Surat_angkut = Surat_angkut::find($id);
    //         $berat_barang = $orderan->berat_barang;
    //         $Surat_angkut->nomor_sa = $request->nomor_sa;
    //         $Surat_angkut->kode_tanda_penerima = $request->kode_tanda_penerima;
    //         $Surat_angkut->nama_customer = $request->nama_customer;
    //         $Surat_angkut->alamat_customer = $request->alamat_customer;
    //         $Surat_angkut->telepon_customer = $request->telepon_customer;
    //         $Surat_angkut->nama_barang = $request->nama_barang;
    //         $Surat_angkut->jumlah_barang = $orderan->jumlah_barang;
    //         $Surat_angkut->berat_barang = $orderan->berat_barang;
    //         $Surat_angkut->nama_penerima = $orderan->nama_penerima;
    //         $Surat_angkut->alamat_penerima = $orderan->alamat_penerima;
    //         $Surat_angkut->telepon_penerima = $orderan->telepon_penerima;
    //         $Surat_angkut->supir = $orderan->supir;
    //         $Surat_angkut->no_mobil = $orderan->no_mobil;
    //         $Surat_angkut->keterangan = $orderan->keterangan;
    //         $Surat_angkut->tanggal_pengambilan = $orderan->tanggal_pengambilan;
    //         $Surat_angkut->harga = $orderan->harga;
    //         $Surat_angkut->update();
    //         return response()->json('berhasil', 200);
    //     }else{
    //         return response()->json('gagal', 200);
    // }

    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $surat_angkut = Surat_angkut::find($id);
    //     $orderan = Orderan::where('kode_tanda_penerima', $surat_angkut->kode_tanda_terima)->first();
    //     if($orderan->status == 2){

    //         return response(null, 422);

    //     }else{
    //         $surat_angkut->delete();
    //         return response(null, 204);
    //     }

    // }

    // public function deleteSelected(Request $request)
    // {
    //     foreach ($request->surat_angkut as $id) {
    //         $surat_angkut = Surat_angkut::find($id);
    //         $surat_angkut->delete();
    //     }

    //     return response(null, 204);
    // }

    public function exportCSV()
    {
        $invoices = Party::get()->toArray();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoices_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($invoices[0]));
            foreach ($invoices as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF($id)
    {
        $surat_angkut = Surat_angkut::find($id);

        // ...

        // Membuat file PDF
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('surat_angkut.pdf', compact('surat_angkut'))->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Menghasilkan file PDF dan mengirimkan ke browser
        $orderans = Orderan::all();
        $pdfFileName = 'surat_angkut' . $surat_angkut->nomor_sa . $surat_angkut->created_at . '.pdf';
        $dompdf->stream($pdfFileName);
        if ($pdfFileName) {
            // Return a redirect to the 'surat_angkut.index' route with the $orderans data
            return view('invoice.index', compact('orderans'));
        }
    }
    public function exportfilter(Request $request)
    {
        $nama_customer = $request->nama_customer;
        $nama_penerima = $request->nama_penerima;

        $invoices = Party::when($kode_invoices, function ($query) use ($kode_invoices) {
            return $query->where('kode_invoices', $kode_invoices);
        })
            ->when($kode_dm, function ($query) use ($kode_dm) {
                return $query->where('kode_dm', $kode_dm);
            })
            ->when($nomor_sa, function ($query) use ($nomor_sa) {
                return $query->where('nomor_sa', $nomor_sa);
            })
            ->when($nama_customer, function ($query) use ($nama_customer) {
                return $query->where('nama_customer', $nama_customer);
            })
            ->when($nama_penerima, function ($query) use ($nama_penerima) {
                return $query->where('nama_penerima', $nama_penerima);
            })
            ->when($supir, function ($query) use ($supir) {
                return $query->where('supir', $supir);
            })
            ->when($no_mobil, function ($query) use ($no_mobil) {
                return $query->where('no_mobil', $no_mobil);
            })
            ->get()->toArray();

        $total_berat = 0;
        $total_semua_harga = 0;
        $total_jumlah_barang = 0;
        foreach ($invoices as $pt) {
            $total_berat += $pt['berat_barang'];
            $total_semua_harga += $pt['total_harga'];
            $total_jumlah_barang += $pt['jumlah_barang'];
        }
        $invoices[] = ['', '', '', '', '', ''];
        $invoices[] = ['Total Berat Barang', $total_berat, '', '', '', ''];
        $invoices[] = ['Total Semua Harga', $total_semua_harga, '', '', ''];
        $invoices[] = ['Total Jumlah Barang', $total_jumlah_barang, '', '', ''];

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoices_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($invoices[0]));
            foreach ($invoices as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

// public function exportPDF(Request $request)
// {
//     // Mendapatkan data dari database atau input form
//     //    $surat_angkut = Surat_angkut::find($id);
//     $id_sa = 1;
//     $nomor_sa = 'SA001';
//     $kode_tanda_penerima = 'KTP001';
//     $nama_customer = 'John Doe';
//     $alamat_customer = 'Jl. Raya No.123';
//     $telepon_customer = '08123456789';
//     $nama_barang = 'Sepatu';
//     $jumlah_barang = 2;
//     $berat_barang = '1 kg';
//     $nama_penerima = 'Jane Doe';
//     $alamat_penerima = 'Jl. Raya No.456';
//     $telepon_penerima = '08987654321';
//     $supir = 'Budi';
//     $no_mobil = 'B 1234 XYZ';
//     $keterangan = 'Pengiriman Cepat';
//     $tanggal_pengambilan = '2023-03-06';
//     $tanggal_kirim = '2023-03-07';
//     $tanggal_kembali = '2023-03-08';
//     $harga = 250000;

//     // Membuat instance Invoice
//     $invoice = Invoice::make('surat');

//     // Mengisi data invoice
//     $invoice->series('INV')
//             ->sequence($id_sa)
//             ->getDate(now()->addDays(14))
//             ->currencySymbol('Rp')
//             ->currencyCode('IDR')
//             ->currencyFormat('{VALUE}')
//             ->taxRate(10)
//             ->discount(0)
//             ->shipping(0)
//             ->addItem(new InvoiceItem([
//                 'name' => $nama_barang,
//                 'quantity' => $jumlah_barang,
//                 'price' => $harga,
//                 'discount' => 0,
//                 'tax' => 0
//             ]))
//             ->customer(new Party([
//                 'name' => $nama_customer,
//                 'address' => $alamat_customer,
//                 'city' => '',
//                 'zip' => '',
//                 'country' => '',
//                 'phone' => $telepon_customer,
//                 'custom_fields' => [
//                     'Kode Tanda Penerima' => $kode_tanda_penerima,
//                 ],
//             ]))
//             ->shippingTo(new Party([
//                 'name' => $nama_penerima,
//                 'address' => $alamat_penerima,
//                 'city' => '',
//                 'zip' => '',
//                 'country' => '',
//                 'phone' => $telepon_penerima,
//                 'custom_fields' => [
//                     'Supir' => $supir,
//                     'No. Mobil' => $no_mobil,
//                 ],
//             ]))
//             ->notes($keterangan)
//             ->date($tanggal_kembali)
//             ->taxRate($harga * 0.1);

//     // Mendownload invoice dalam format PDF
//     return $invoice->download();
// }

}
