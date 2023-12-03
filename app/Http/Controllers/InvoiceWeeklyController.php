<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Orderan;
use App\Models\Penerima;
use App\Models\Surat_angkut;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceWeeklyController extends Controller
{
    public function index()
    {
        $orderans = Surat_angkut::where('surat_angkuts.status', 3);

        return view('invoiceWeekly.index', compact('orderans'));
    }
    public function data()
    {
        $today = Carbon::now();
        $startDate = $today->copy()->startOfWeek()->toDateString();
        $endDate = $today->copy()->endOfWeek()->toDateString();

        $invoices = Invoice::where('status', 1)->get();

        $invoiceWeekly = DB::table('parties')
            ->select([
                'orderans.tagihan_by',
                'parties.tanggal_pembuatan',
                DB::raw('
                    CASE
                        WHEN orderans.tagihan_by = 1 THEN orderans.nama_customer
                        WHEN orderans.tagihan_by = 2 THEN orderans.nama_penerima
                        ELSE ""
                    END AS nama
                '),
                DB::raw('COUNT(parties.nomor_sa) AS total_sa'),
            ])
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate])
            ->where('surat_angkuts.status', 3)
            ->groupBy('orderans.tagihan_by', 'nama')
            ->get();

        // dd($invoiceWeekly);

        $filteredInvoiceWeekly = [];

        foreach ($invoiceWeekly as $weekly) {
            $isInInvoices = $invoices->contains('nama', $weekly->nama);

            if (!$isInInvoices) {
                $filteredInvoiceWeekly[] = $weekly;
            }
        }

        $invoiceData = [];

        foreach ($filteredInvoiceWeekly as $weekly) {
            $invoice = Invoice::where('nama', $weekly->nama)
                ->where('tagihan_by', $weekly->tagihan_by)
                ->where('status', 2)
                ->first();

            if ($invoice) {
                $weekly->invoice_id = $invoice->id;
                $weekly->nomor_invoice = $invoice->nomor_invoice;
                $weekly->nomor_kwitansi = $invoice->nomor_kwitansi;
            } else {
                $weekly->invoice_id = null;
                $weekly->nomor_invoice = null;
                $weekly->nomor_kwitansi = null;
            }

            $invoiceData[] = $weekly;
        }

        return datatables()
            ->of($filteredInvoiceWeekly)
            ->addIndexColumn()
            ->addColumn('kwitansi', function ($weekly) {
                $routeadd = route('invoiceWeekly.store', ['nama' => $weekly->nama, 'tagihan_by' => $weekly->tagihan_by]);
                $routecetak = route('invoiceWeekly.cetakKwitansi', ['id' => $weekly->invoice_id]);

                return '
                <div class="btn-group">
                <button type="button" onclick="editForm(\'' . $routeadd . '\')" class="btn btn-xs btn-success btn-flat"><i class="fa fa-plus-circle"></i></button>

                <button type="button" onclick="cetakKwitansi(\'' . $routecetak . '\')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i></button>

                </div>';
            })
            ->addColumn('aksi', function ($weekly) {
                $route = route('showinvoiceWeekly.show', ['nama' => $weekly->nama, 'tagihan_by' => $weekly->tagihan_by]);
                $routecetak2 = route('invoiceWeekly.cetakCustomer', ['id' => $weekly->invoice_id]);

                return '
                <div class="btn-group">
                <button type="button" onclick="showDetail(\'' . $route . '\')" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button></button>
                    <button type="button" onclick="cetakCustomer(\'' . $routecetak2 . '\')" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i></button>
                </div>';
            })
            ->rawColumns(['kwitansi', 'aksi'])
            ->make(true);
    }

    public function show(Request $request)
    {
        $nama = $request->nama;
        $tagihan_by = $request->tagihan_by;

        $today = Carbon::now();
        $startDate = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Tanggal awal pekan (minggu ini, dimulai dari Minggu)
        $endDate = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Tanggal akhir pekan (minggu ini, berakhir pada Minggu)

        $invoiceWeekly = DB::table('parties')
            ->select('parties.*', 'surat_angkuts.*', 'orderans.id_harga', 'orderans.tagihan_by', 'surat_angkuts.status as status', 'surat_angkuts.tanggal_pengambilan as tanggal_pengambilan', 'surat_angkuts.tanggal_kirim as tanggal_kirim', 'surat_angkuts.tanggal_kembali as tanggal_kembali', 'surat_angkuts.tanggal_ditagihkan as tanggal_ditagihkan', 'hargas.*')
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate])
            ->where('orderans.tagihan_by', $tagihan_by);

        $invoiceWeekly->distinct()
            ->addSelect(DB::raw('
                CASE
                WHEN orderans.jenis_berat = "roll" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_roll
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_roll
                    END
                WHEN orderans.jenis_berat = "ball" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_ball
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_ball
                    END
                WHEN orderans.jenis_berat = "tonase" THEN
                    CASE
                        WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                            CASE
                                WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                    ((parties.berat_barang - hargas.sub_syarat_berat) * hargas.harga_tonase) + hargas.diskon_tonase_sub
                                END
                        ELSE
                            parties.berat_barang * hargas.harga_tonase
                        END
                ELSE 0
                END AS total_harga
            '));

        if ($tagihan_by == 1) {
            $invoiceWeekly->where('orderans.nama_customer', $nama);
        } else {
            $invoiceWeekly->where('orderans.nama_penerima', $nama);
        }

        // Fetch the data
        $invoiceWeeklyData = $invoiceWeekly->distinct()->get();

        // Calculate total_sa
        $totalSa = count($invoiceWeeklyData);

        // Calculate total_keseluruhan
        $totalKeseluruhan = 0;
        foreach ($invoiceWeeklyData as $item) {
            $totalKeseluruhan += $item->total_harga;
        }

        // Create a response array that includes the new values
        $response = [
            'invoiceWeekly' => $invoiceWeeklyData,
            'total_sa' => $totalSa,
            'total_keseluruhan' => $totalKeseluruhan,
        ];

        return response()->json($response);
    }

    // public function cetakKwitansi(Request $request)
    // {
    //     $id = $request->id;
    //     $invoiceWeekly = Invoice::find($id);

    //     // dd($invoiceWeekly);

    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml(view('invoiceWeekly.kwitansi', compact('invoiceWeekly'))->render());
    //     $dompdf->setPaper('A4', 'portrait');
    //     $dompdf->render();

    //     // Menghasilkan file PDF dan mengirimkan ke browser
    //     $pdfFileName = 'invoiceWeekly' . $invoiceWeekly->nomor_invoice . $invoiceWeekly->created_at . '.pdf';
    //     $dompdf->stream($pdfFileName);

    // }

    public function cetakKwitansi(Request $request)
    {
        $today = Carbon::now();
        $startDate = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Tanggal awal pekan (minggu ini, dimulai dari Minggu)
        $endDate = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Tanggal akhir pekan (minggu ini, berakhir pada Minggu)

        $date = $today->format('d F Y');
        $id = $request->id;
        $invoice = Invoice::find($id);
        if ($invoice) {
            $tagihanString = ($invoice->tagihan_by == 1) ? 'Pengirim' : 'Penerima';
            $invoice->tagihan_string = $tagihanString;
        }
        $tagihan_by = $invoice->tagihan_by;
        $client = '';
        if ($tagihan_by == 1) {
            $client = Customer::select('nama_customer as nama', 'alamat_customer as alamat', 'telepon_customer as telepon')->where('nama_customer', $invoice->nama)->first();
        } elseif ($tagihan_by == 2) {
            $client = Penerima::select('nama_penerima as nama', 'alamat_penerima as alamat', 'telepon_penerima as telepon')->where('nama_penerima', $invoice->nama)->first();
        } else {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $invoiceWeekly = DB::table('parties')
            ->select('parties.*', 'surat_angkuts.*', 'orderans.id_harga', 'orderans.jenis_berat', 'orderans.tagihan_by', 'surat_angkuts.status as status', 'hargas.*')
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate])
            ->where('orderans.tagihan_by', $invoice->tagihan_by);

        $invoiceWeekly->distinct()
            ->addSelect(DB::raw('
                CASE
                WHEN orderans.jenis_berat = "roll" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_roll
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_roll
                    END
                WHEN orderans.jenis_berat = "ball" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_ball
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_ball
                    END
                WHEN orderans.jenis_berat = "tonase" THEN
                    CASE
                        WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                            CASE
                                WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                    ((parties.berat_barang - hargas.sub_syarat_berat) * hargas.harga_tonase) + hargas.diskon_tonase_sub
                                END
                        ELSE
                            parties.berat_barang * hargas.harga_tonase
                        END
                ELSE 0
                END AS total_harga
            '));

        if ($invoice->tagihan_by == 1) {
            $invoiceWeekly->where('orderans.nama_customer', $invoice->nama);
        } else {
            $invoiceWeekly->where('orderans.nama_penerima', $invoice->nama);
        }

        // Fetch the data
        $invoiceWeeklyData = $invoiceWeekly->distinct()->get();
        // dd($invoiceWeeklyData);
        foreach ($invoiceWeeklyData as $result) {
            $hargaTerpakai = 0;

            // Hitung harga_terpakai sesuai dengan jenis_berat
            if ($result->jenis_berat === "roll") {
                $hargaTerpakai = $result->harga_roll;
            } elseif ($result->jenis_berat === "ball") {
                $hargaTerpakai = $result->harga_ball;
            } elseif ($result->jenis_berat === "tonase") {
                $hargaTerpakai = $result->harga_tonase;
            }

            // Tambahkan kolom harga_terpakai ke hasil yang ada
            $result->harga_terpakai = $hargaTerpakai;
        }

        // dd($invoiceWeeklyData);

        // Calculate totalSa
        $totalSa = $invoiceWeeklyData->count();

        $totalKeseluruhan = 0;
        $totalberat = 0;
        $totaljumlah = 0;
        foreach ($invoiceWeeklyData as $item) {
            $totalKeseluruhan += $item->total_harga;
            $totalberat += $item->berat_barang;
            $totaljumlah += $item->jumlah_barang;
        }
        $totalKeseluruhan = intval($totalKeseluruhan);
        $totalKeseluruhan = 147027500;
        $totalberat = intval($totalberat);
        $totaljumlah = intval($totaljumlah);

        $huruf = array("", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN");
        $disebut = "";

        if ($totalKeseluruhan < 12) {
            $disebut = " " . strtoupper($huruf[$totalKeseluruhan]);
        } elseif ($totalKeseluruhan < 20) {
            $disebut = strtoupper(terbilang($totalKeseluruhan - 10)) . " BELAS";
        } elseif ($totalKeseluruhan < 100) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 10))) . " PULUH " . strtoupper($huruf[$totalKeseluruhan % 10]);
        } elseif ($totalKeseluruhan < 200) {
            $disebut = " SERATUS " . strtoupper(terbilang($totalKeseluruhan - 100));
        } elseif ($totalKeseluruhan < 1000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 100))) . " RATUS " . strtoupper(terbilang($totalKeseluruhan % 100));
        } elseif ($totalKeseluruhan < 2000) {
            $disebut = " SERIBU " . strtoupper(terbilang($totalKeseluruhan - 1000));
        } elseif ($totalKeseluruhan < 1000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000))) . " RIBU " . strtoupper(terbilang($totalKeseluruhan % 1000));
        } elseif ($totalKeseluruhan < 1000000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000000))) . " JUTA " . strtoupper(terbilang($totalKeseluruhan % 1000000));
        } elseif ($totalKeseluruhan < 1000000000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000000000))) . " MILYAR " . strtoupper(terbilang($totalKeseluruhan % 1000000000));
        } elseif ($totalKeseluruhan < 1000000000000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000000000000))) . " TRILYUN " . strtoupper(terbilang($totalKeseluruhan % 1000000000000));
        }

        // dd($disebut);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('invoiceWeekly.kwitansi', compact('invoice', 'invoiceWeeklyData', 'client', 'date', 'disebut', 'totalKeseluruhan', 'totalSa', 'totalberat', 'totaljumlah'))->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        if ($dompdf->output()) {
            // The PDF rendering and export were successful
            if ($invoice) {
                $newStatus = 4;

                // Update the Surat_angkut records and retrieve the kode_tanda_penerima
                $updatedSAs = Surat_angkut::whereIn('nomor_sa', [$invoice->sa_id])
                    ->update([
                        'status' => $newStatus,
                        'tanggal_ditagihkan' => now(),
                    ]);

                if ($updatedSAs) {
                    // Retrieve the kode_tanda_penerima from the updated Surat_angkut record(s)
                    $kodeTandaPenerima = Surat_angkut::whereIn('nomor_sa', [$invoice->sa_id])
                        ->pluck('kode_tanda_penerima')
                        ->toArray();

                    // Update the Orderan records using the retrieved kode_tanda_penerima
                    $updateOrderan = Orderan::whereIn('kode_tanda_penerima', $kodeTandaPenerima)
                        ->update([
                            'status' => $newStatus,
                        ]);

                    if ($updateOrderan) {
                        // The database updates were successful
                    } else {
                        return response()->json(['error' => 'Failed to update Orderan records.']);
                    }
                } else {
                    return response()->json(['error' => 'Failed to update Surat_angkut records.']);
                }
            }
        } else {
            return response()->json(['error' => 'PDF rendering and export failed.']);
        }

        // Generate PDF file and send it to the browser
        $pdfFileName = 'Kwitansi' . $invoice->nomor_kwitansi . $invoice->created_at . '.pdf';
        $dompdf->stream($pdfFileName);
    }

    public function cetakCustomer(Request $request)
    {
        $today = Carbon::now();
        $startDate = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Tanggal awal pekan (minggu ini, dimulai dari Minggu)
        $endDate = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Tanggal akhir pekan (minggu ini, berakhir pada Minggu)

        $date = $today->format('d F Y');
        $id = $request->id;
        $invoice = Invoice::find($id);
        if ($invoice) {
            $tagihanString = ($invoice->tagihan_by == 1) ? 'Pengirim' : 'Penerima';
            $invoice->tagihan_string = $tagihanString;
        }
        $tagihan_by = $invoice->tagihan_by;
        $client = '';
        if ($tagihan_by == 1) {
            $client = Customer::select('nama_customer as nama', 'alamat_customer as alamat', 'telepon_customer as telepon')->where('nama_customer', $invoice->nama)->first();
        } elseif ($tagihan_by == 2) {
            $client = Penerima::select('nama_penerima as nama', 'alamat_penerima as alamat', 'telepon_penerima as telepon')->where('nama_penerima', $invoice->nama)->first();
        } else {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $invoiceWeekly = DB::table('parties')
            ->select('parties.*', 'surat_angkuts.*', 'orderans.id_harga', 'orderans.jenis_berat', 'orderans.tagihan_by', 'surat_angkuts.status as status', 'hargas.*')
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate])
            ->where('orderans.tagihan_by', $invoice->tagihan_by);

        $invoiceWeekly->distinct()
            ->addSelect(DB::raw('
                CASE
                WHEN orderans.jenis_berat = "roll" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_roll
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_roll
                    END
                WHEN orderans.jenis_berat = "ball" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_ball
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_ball
                    END
                WHEN orderans.jenis_berat = "tonase" THEN
                    CASE
                        WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                            CASE
                                WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                    ((parties.berat_barang - hargas.sub_syarat_berat) * hargas.harga_tonase) + hargas.diskon_tonase_sub
                                END
                        ELSE
                            parties.berat_barang * hargas.harga_tonase
                        END
                ELSE 0
                END AS total_harga
            '));

        if ($invoice->tagihan_by == 1) {
            $invoiceWeekly->where('orderans.nama_customer', $invoice->nama);
        } else {
            $invoiceWeekly->where('orderans.nama_penerima', $invoice->nama);
        }

        // Fetch the data
        $invoiceWeeklyData = $invoiceWeekly->distinct()->get();
        // dd($invoiceWeeklyData);
        foreach ($invoiceWeeklyData as $result) {
            $hargaTerpakai = 0;

            // Hitung harga_terpakai sesuai dengan jenis_berat
            if ($result->jenis_berat === "roll") {
                $hargaTerpakai = $result->harga_roll;
            } elseif ($result->jenis_berat === "ball") {
                $hargaTerpakai = $result->harga_ball;
            } elseif ($result->jenis_berat === "tonase") {
                $hargaTerpakai = $result->harga_tonase;
            }

            // Tambahkan kolom harga_terpakai ke hasil yang ada
            $result->harga_terpakai = $hargaTerpakai;
        }

        // dd($invoiceWeeklyData);

        // Calculate totalSa
        $totalSa = $invoiceWeeklyData->count();

        $totalKeseluruhan = 0;
        $totalberat = 0;
        $totaljumlah = 0;
        foreach ($invoiceWeeklyData as $item) {
            $totalKeseluruhan += $item->total_harga;
            $totalberat += $item->berat_barang;
            $totaljumlah += $item->jumlah_barang;
        }
        $totalKeseluruhan = intval($totalKeseluruhan);
        $totalKeseluruhan = 147027500;
        $totalberat = intval($totalberat);
        $totaljumlah = intval($totaljumlah);

        $huruf = array("", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN");
        $disebut = "";

        if ($totalKeseluruhan < 12) {
            $disebut = " " . strtoupper($huruf[$totalKeseluruhan]);
        } elseif ($totalKeseluruhan < 20) {
            $disebut = strtoupper(terbilang($totalKeseluruhan - 10)) . " BELAS";
        } elseif ($totalKeseluruhan < 100) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 10))) . " PULUH " . strtoupper($huruf[$totalKeseluruhan % 10]);
        } elseif ($totalKeseluruhan < 200) {
            $disebut = " SERATUS " . strtoupper(terbilang($totalKeseluruhan - 100));
        } elseif ($totalKeseluruhan < 1000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 100))) . " RATUS " . strtoupper(terbilang($totalKeseluruhan % 100));
        } elseif ($totalKeseluruhan < 2000) {
            $disebut = " SERIBU " . strtoupper(terbilang($totalKeseluruhan - 1000));
        } elseif ($totalKeseluruhan < 1000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000))) . " RIBU " . strtoupper(terbilang($totalKeseluruhan % 1000));
        } elseif ($totalKeseluruhan < 1000000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000000))) . " JUTA " . strtoupper(terbilang($totalKeseluruhan % 1000000));
        } elseif ($totalKeseluruhan < 1000000000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000000000))) . " MILYAR " . strtoupper(terbilang($totalKeseluruhan % 1000000000));
        } elseif ($totalKeseluruhan < 1000000000000000) {
            $disebut = strtoupper(terbilang(floor($totalKeseluruhan / 1000000000000))) . " TRILYUN " . strtoupper(terbilang($totalKeseluruhan % 1000000000000));
        }

        // dd($disebut);

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('invoiceWeekly.cetakCustomer', compact('invoice', 'invoiceWeeklyData', 'client', 'date', 'disebut', 'totalKeseluruhan', 'totalSa', 'totalberat', 'totaljumlah'))->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generate PDF file and send it to the browser
        $pdfFileName = 'invoice-mingguan-' . $invoice->nomor_invoice . $invoice->created_at . '.pdf';
        $dompdf->stream($pdfFileName);
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari request

        $today = Carbon::now();
        $startDate = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Tanggal awal pekan (minggu ini, dimulai dari Minggu)
        $endDate = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Tanggal akhir pekan (minggu ini, berakhir pada Minggu)

        $request->validate([
            'nomor_kwitansi' => 'required|unique:invoices',
        ]);
        $nama_customer = $request->nama_customer;
        $nama_penerima = $request->nama_penerima;
        $nama = $request->nama;

        $tagihan_by = $request->tagihan_by;

        $invoiceWeekly = DB::table('parties')
            ->select('parties.*', 'surat_angkuts.*', 'orderans.id_harga', 'orderans.tagihan_by', 'surat_angkuts.status as status', 'surat_angkuts.tanggal_pengambilan as tanggal_pengambilan', 'surat_angkuts.tanggal_kirim as tanggal_kirim', 'surat_angkuts.tanggal_kembali as tanggal_kembali', 'surat_angkuts.tanggal_ditagihkan as tanggal_ditagihkan', 'hargas.*')
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate])
            ->where('orderans.tagihan_by', $tagihan_by);

        $invoiceWeekly->distinct()
            ->addSelect(DB::raw('
                CASE
                WHEN orderans.jenis_berat = "roll" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_roll
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_roll
                    END
                WHEN orderans.jenis_berat = "ball" THEN
                    CASE
                        WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_ball
                        ELSE surat_angkuts.jumlah_barang * hargas.harga_ball
                    END
                WHEN orderans.jenis_berat = "tonase" THEN
                    CASE
                        WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                            CASE
                                WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                    ((parties.berat_barang - hargas.sub_syarat_berat) * hargas.harga_tonase) + hargas.diskon_tonase_sub
                                END
                        ELSE
                            parties.berat_barang * hargas.harga_tonase
                        END
                ELSE 0
                END AS total_harga
            '));

        if ($tagihan_by == 1) {
            $invoiceWeekly->where('orderans.nama_customer', $nama);
        } else {
            $invoiceWeekly->where('orderans.nama_penerima', $nama);
        }

        // Fetch the data
        $invoiceWeeklyData = $invoiceWeekly->distinct()->get();
        // dd($invoiceWeeklyData);

        // Calculate total_sa
        $totalSa = count($invoiceWeeklyData);

        // Calculate total_keseluruhan
        $totalKeseluruhan = 0;
        foreach ($invoiceWeeklyData as $item) {
            $totalKeseluruhan += $item->total_harga;
        }
        $nomorSaArray = $invoiceWeeklyData->pluck('nomor_sa')->toArray();

// Menggabungkan nomor_sa menjadi satu string dengan koma
        $nomorSaString = implode(',', $nomorSaArray);
        // dd($nomorSaString);

        // Membuat instance model Invoice
        $invoice = new Invoice([
            'nomor_kwitansi' => $request->nomor_kwitansi,
            'sa_id' => $nomorSaString,
            'nama' => $nama,
            'tagihan_by' => $tagihan_by,
            'status' => 2,
        ]);

        // Mengatur nomor_invoice dengan format tahun-bulan-XXXX
        $year = now()->year;
        $month = now()->format('m');

        // Mencari nomor_invoice terakhir dengan format yang sama
        $lastInvoice = Invoice::where('nomor_invoice', 'like', "{$year}-{$month}-%")->latest('nomor_invoice')->first();

        // Menentukan nomor_invoice berikutnya
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->nomor_invoice, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $invoice->nomor_invoice = "{$year}-{$month}-{$nextNumber}";

        // Menyimpan data ke dalam database
        $invoice->save();
        // Redirect atau tindakan lain yang sesuai setelah berhasil menyimpan data
        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil disimpan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportCSV()
    {
        $today = Carbon::now();
        $startDate = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Tanggal awal pekan (minggu ini, dimulai dari Minggu)
        $endDate = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Tanggal akhir pekan (minggu ini, berakhir pada Minggu)
        $invoiceWeekly = DB::table('parties')
            ->select(

                'invoices.nomor_kwitansi',
                'invoices.nomor_invoice',
                'invoices.tanggal_lunas',
                // 'orderans.tagihan_by',
                // 'parties.tanggal_pembuatan',
                DB::raw('
                CASE
                WHEN orderans.tagihan_by = 1 THEN orderans.nama_customer
                WHEN orderans.tagihan_by = 2 THEN orderans.nama_penerima
                ELSE ""
                END AS nama
                '),
                'parties.nomor_sa',
            )
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->wherenotnull('invoices.nomor_kwitansi')
            ->where('invoices.status', 2)
            ->orderby('nomor_kwitansi', 'asc')
            ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate]);

        $invoiceWeekly->distinct()
            ->addSelect(DB::raw('
        CASE
            WHEN orderans.jenis_berat = "roll" THEN
                CASE
                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_roll
                    ELSE surat_angkuts.jumlah_barang * hargas.harga_roll
                END
            WHEN orderans.jenis_berat = "ball" THEN
                CASE
                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_ball
                    ELSE surat_angkuts.jumlah_barang * hargas.harga_ball
                END
            WHEN orderans.jenis_berat = "tonase" THEN
                CASE
                    WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                        CASE
                            WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                ((parties.berat_barang - hargas.sub_syarat_berat) * hargas.harga_tonase) + hargas.diskon_tonase_sub
                            END
                    ELSE
                        parties.berat_barang * hargas.harga_tonase
                    END
            ELSE 0
        END AS harga
        '))
            ->addSelect(DB::raw('
                    CASE
                        WHEN orderans.jenis_berat = "roll" THEN
                            CONCAT(parties.berat_barang, " kg, ", surat_angkuts.jumlah_barang, " ", orderans.jenis_berat, " x ",
                                CASE
                                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN CONCAT(hargas.diskon_roll)
                                    ELSE CONCAT(hargas.harga_roll)
                                END
                            )
                        WHEN orderans.jenis_berat = "ball" THEN
                            CONCAT(surat_angkuts.jumlah_barang, " ", orderans.jenis_berat, ", ", parties.berat_barang, " kg x ",
                                CASE
                                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN CONCAT(hargas.diskon_ball)
                                    ELSE CONCAT(hargas.harga_ball)
                                END
                            )
                        WHEN orderans.jenis_berat = "tonase" THEN
                            CONCAT(parties.berat_barang, " kg x ",
                                CASE
                                    WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                                        CASE
                                            WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                                CONCAT("(", parties.berat_barang - hargas.sub_syarat_berat, " x ", hargas.harga_tonase, " + ", hargas.diskon_tonase_sub, ")")
                                            ELSE CONCAT(parties.berat_barang, " x ", hargas.harga_tonase)
                                        END
                                    ELSE
                                        CONCAT(parties.berat_barang, " x ", hargas.harga_tonase)
                                END
                            )
                        ELSE "Tipe Berat Tidak Diketahui"
                    END AS keterangan
                '));

        // Menambahkan join ke tabel "invoices" berdasarkan nama dan tagihan_by
        $invoiceWeekly->leftJoin('invoices', function ($join) {
            $join->on('orderans.nama_customer', '=', 'invoices.nama')
                ->where('orderans.tagihan_by', '=', '1');
            $join->orOn('orderans.nama_penerima', '=', 'invoices.nama')
                ->where('orderans.tagihan_by', '=', '2');
        });

        $invoiceWeeklyData = $invoiceWeekly->distinct()->get();
        // Assuming you already have $invoiceWeeklyData from your previous code
        $totalPerName = [];

        // Calculate the count of nomor_sa for each unique nama
        foreach ($invoiceWeeklyData as $invoice) {
            $nama = $invoice->nama;
            $nomorSa = $invoice->nomor_sa;

            if (!isset($totalPerName[$nama])) {
                $totalPerName[$nama] = 0;
            }

            if ($nomorSa !== null) {
                $totalPerName[$nama]++;
            }
        }

        // Initialize an array to store the total harga per 'nama'
        $totalHargaPerName = [];

        // Iterate through the result set
        foreach ($invoiceWeeklyData as $invoice) {
            $nama = $invoice->nama;
            $totalHarga = $invoice->harga;

            if (!isset($totalHargaPerName[$nama])) {
                $totalHargaPerName[$nama] = 0;
            }

            // Add the total_harga to the existing total for the current 'nama'
            $totalHargaPerName[$nama] += $totalHarga;
        }

        // Now, $totalHargaPerName contains the total harga per 'nama'

        // Assign the count of nomor_sa to each row in $invoiceWeeklyData
        foreach ($invoiceWeeklyData as $key => $invoice) {
            $nama = $invoice->nama;
            $invoiceWeeklyData[$key]->total_sa = $totalPerName[$nama];
            $invoiceWeeklyData[$key]->total_harga = $totalHargaPerName[$nama];
        }

        $uniqueCombinations = [];

        // Iterate through the result set
        foreach ($invoiceWeeklyData as $key => $invoice) {
            $combinationKey = "{$invoice->nama}_{$invoice->nomor_kwitansi}_{$invoice->nomor_invoice}_{$invoice->total_sa}_{$invoice->total_harga}";

            // Check if the combination already exists, and if not, add it to the unique combinations
            if (!isset($uniqueCombinations[$combinationKey])) {
                $uniqueCombinations[$combinationKey] = $key;
            } else {
                // Combination exists, set the current row's values to empty strings
                $invoiceWeeklyData[$key]->nama = '';
                $invoiceWeeklyData[$key]->nomor_kwitansi = '';
                $invoiceWeeklyData[$key]->nomor_invoice = '';
                $invoiceWeeklyData[$key]->total_sa = '';
                $invoiceWeeklyData[$key]->total_harga = '';
            }
        }

        $newColumnOrder = ['nomor_kwitansi', 'nomor_invoice', 'nama', 'total_sa', 'total_harga', 'harga', 'nomor_sa', 'keterangan', 'tanggal_lunas'];

        // Create a new array to hold the results with the desired column order
        $reorderedData = [];

        foreach ($invoiceWeeklyData as $row) {
            $reorderedRow = [
                'nomor_kwitansi' => $row->nomor_kwitansi,
                'nomor_invoice' => $row->nomor_invoice,
                'nama' => $row->nama,
                'total_sa' => $row->total_sa, // Kolom total_sa ditambahkan di sini
                'total_harga' => $row->total_harga,
                'harga' => $row->harga,
                'nomor_sa' => $row->nomor_sa,
                'keterangan' => $row->keterangan,
                'tanggal_lunas' => $row->tanggal_lunas,
            ];

            $reorderedData[] = $reorderedRow;
        }

        $totalKeseluruhan = $invoiceWeeklyData->sum('harga');

        // Add total_keseluruhan to the collection
        $invoiceWeeklyData->push(['Total Surat Angkut', $invoiceWeeklyData->count()]);
        $invoiceWeeklyData->push(['Total Keseluruhan', $totalKeseluruhan]);

        // Ubah data menjadi koleksi
        $invoiceWeeklyData = collect($reorderedData);

        // Konversi data ke JSON
        $jsonResult = $invoiceWeeklyData->toJson();

        // dd($jsonResult);

        // Convert the JSON data to CSV
        $csv = fopen('php://temp', 'w');

        $data = json_decode($jsonResult, true);

        if (count($data) > 0) {
            fputcsv($csv, array_keys($data[0]));

            foreach ($data as $row) {
                fputcsv($csv, $row);
            }
        }

        rewind($csv);
        $csvData = stream_get_contents($csv);
        fclose($csv);

        // Create headers for the response
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoiceWeekly_' . $startDate . '.csv"',
        ];

        // Create the response with CSV data
        $callback = function () use ($csvData) {
            echo $csvData;
        };

        return response()->stream($callback, 200, $headers);
    }
    public function exportPDF()
    {
        $today = Carbon::now();
        $formattedDate = $today->format('d F Y');
        $startDate = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Tanggal awal pekan (minggu ini, dimulai dari Minggu)
        $endDate = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Tanggal akhir pekan (minggu ini, berakhir pada Minggu)
        $invoiceWeekly = DB::table('parties')
            ->select(

                'invoices.nomor_kwitansi',
                'invoices.nomor_invoice',
                'invoices.tanggal_lunas',
                // 'orderans.tagihan_by',
                // 'parties.tanggal_pembuatan',
                DB::raw('
                CASE
                WHEN orderans.tagihan_by = 1 THEN orderans.nama_customer
                WHEN orderans.tagihan_by = 2 THEN orderans.nama_penerima
                ELSE ""
                END AS nama
                '),
                'parties.nomor_sa',
            )
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->wherenotnull('invoices.nomor_kwitansi')
            ->where('invoices.status', 2)
            ->orderby('nomor_kwitansi', 'asc')
            ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate]);

        $invoiceWeekly->distinct()
            ->addSelect(DB::raw('
        CASE
            WHEN orderans.jenis_berat = "roll" THEN
                CASE
                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_roll
                    ELSE surat_angkuts.jumlah_barang * hargas.harga_roll
                END
            WHEN orderans.jenis_berat = "ball" THEN
                CASE
                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_ball
                    ELSE surat_angkuts.jumlah_barang * hargas.harga_ball
                END
            WHEN orderans.jenis_berat = "tonase" THEN
                CASE
                    WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                        CASE
                            WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                ((parties.berat_barang - hargas.sub_syarat_berat) * hargas.harga_tonase) + hargas.diskon_tonase_sub
                            END
                    ELSE
                        parties.berat_barang * hargas.harga_tonase
                    END
            ELSE 0
        END AS harga
        '))
            ->addSelect(DB::raw('
                    CASE
                        WHEN orderans.jenis_berat = "roll" THEN
                            CONCAT(parties.berat_barang, " kg, ", surat_angkuts.jumlah_barang, " ", orderans.jenis_berat, " x ",
                                CASE
                                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN CONCAT(hargas.diskon_roll)
                                    ELSE CONCAT(hargas.harga_roll)
                                END
                            )
                        WHEN orderans.jenis_berat = "ball" THEN
                            CONCAT(surat_angkuts.jumlah_barang, " ", orderans.jenis_berat, ", ", parties.berat_barang, " kg x ",
                                CASE
                                    WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN CONCAT(hargas.diskon_ball)
                                    ELSE CONCAT(hargas.harga_ball)
                                END
                            )
                        WHEN orderans.jenis_berat = "tonase" THEN
                            CONCAT(parties.berat_barang, " kg x ",
                                CASE
                                    WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                                        CASE
                                            WHEN hargas.sub_syarat_berat IS NOT NULL THEN
                                                CONCAT("(", parties.berat_barang - hargas.sub_syarat_berat, " x ", hargas.harga_tonase, " + ", hargas.diskon_tonase_sub, ")")
                                            ELSE CONCAT(parties.berat_barang, " x ", hargas.harga_tonase)
                                        END
                                    ELSE
                                        CONCAT(parties.berat_barang, " x ", hargas.harga_tonase)
                                END
                            )
                        ELSE "Tipe Berat Tidak Diketahui"
                    END AS keterangan
                '));

        // Menambahkan join ke tabel "invoices" berdasarkan nama dan tagihan_by
        $invoiceWeekly->leftJoin('invoices', function ($join) {
            $join->on('orderans.nama_customer', '=', 'invoices.nama')
                ->where('orderans.tagihan_by', '=', '1');
            $join->orOn('orderans.nama_penerima', '=', 'invoices.nama')
                ->where('orderans.tagihan_by', '=', '2');
        });

        $invoiceWeeklyData = $invoiceWeekly->distinct()->get();
        // Assuming you already have $invoiceWeeklyData from your previous code
        $totalPerName = [];

        // Calculate the count of nomor_sa for each unique nama
        foreach ($invoiceWeeklyData as $invoice) {
            $nama = $invoice->nama;
            $nomorSa = $invoice->nomor_sa;

            if (!isset($totalPerName[$nama])) {
                $totalPerName[$nama] = 0;
            }

            if ($nomorSa !== null) {
                $totalPerName[$nama]++;
            }
        }

        // Initialize an array to store the total harga per 'nama'
        $totalHargaPerName = [];

        // Iterate through the result set
        foreach ($invoiceWeeklyData as $invoice) {
            $nama = $invoice->nama;
            $totalHarga = $invoice->harga;

            if (!isset($totalHargaPerName[$nama])) {
                $totalHargaPerName[$nama] = 0;
            }

            // Add the total_harga to the existing total for the current 'nama'
            $totalHargaPerName[$nama] += $totalHarga;
        }

        // Now, $totalHargaPerName contains the total harga per 'nama'

        // Assign the count of nomor_sa to each row in $invoiceWeeklyData
        foreach ($invoiceWeeklyData as $key => $invoice) {
            $nama = $invoice->nama;
            $invoiceWeeklyData[$key]->total_sa = $totalPerName[$nama];
            $invoiceWeeklyData[$key]->total_harga = $totalHargaPerName[$nama];
        }

        $uniqueCombinations = [];

        // Iterate through the result set
        foreach ($invoiceWeeklyData as $key => $invoice) {
            $combinationKey = "{$invoice->nama}_{$invoice->nomor_kwitansi}_{$invoice->nomor_invoice}_{$invoice->total_sa}_{$invoice->total_harga}";

            // Check if the combination already exists, and if not, add it to the unique combinations
            if (!isset($uniqueCombinations[$combinationKey])) {
                $uniqueCombinations[$combinationKey] = $key;
            } else {
                // Combination exists, set the current row's values to empty strings
                $invoiceWeeklyData[$key]->nama = '';
                $invoiceWeeklyData[$key]->nomor_kwitansi = '';
                $invoiceWeeklyData[$key]->nomor_invoice = '';
                $invoiceWeeklyData[$key]->total_sa = '';
                $invoiceWeeklyData[$key]->total_harga = '';
            }
        }

        $totalKeseluruhan = $invoiceWeeklyData->sum('harga');
        $totalSa = $invoiceWeeklyData->count();

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('invoiceWeekly.pdf', compact('invoiceWeeklyData', 'totalKeseluruhan', 'totalSa', 'formattedDate'))->render());
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Generate PDF file and send it to the browser
        $pdfFileName = 'invoice_bulanan' . $startDate . '.pdf';
        $dompdf->stream($pdfFileName);
    }

    // public function exportPDF(Request $request)
    // {
    //     $nama = $request->nama;
    //     $tagihan_by = $request->tagihan_by;
    //     $today = Carbon::now();
    // $startDate = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Tanggal awal pekan (minggu ini, dimulai dari Minggu)
    // $endDate = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Tanggal akhir pekan (minggu ini, berakhir pada Minggu)

    //     $invoiceWeekly = DB::table('parties')
    //         ->select('parties.*', 'surat_angkuts.*', 'orderans.id_harga', 'orderans.tagihan_by', 'surat_angkuts.status as status', 'surat_angkuts.tanggal_pengambilan as tanggal_pengambilan', 'surat_angkuts.tanggal_kirim as tanggal_kirim', 'surat_angkuts.tanggal_kembali as tanggal_kembali', 'surat_angkuts.tanggal_ditagihkan as tanggal_ditagihkan', 'hargas.*')
    //         ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
    //         ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
    //         ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
    //         ->whereBetween('parties.tanggal_pembuatan', [$startDate, $endDate])
    //         ->where('orderans.tagihan_by', $tagihan_by);

    //     $invoiceWeekly->distinct()
    //         ->addSelect(DB::raw('
    //             CASE
    //             WHEN orderans.jenis_berat = "roll" THEN
    //                 CASE
    //                     WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_roll
    //                     ELSE surat_angkuts.jumlah_barang * hargas.harga_roll
    //                 END
    //             WHEN orderans.jenis_berat = "ball" THEN
    //                 CASE
    //                     WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah THEN surat_angkuts.jumlah_barang * hargas.diskon_ball
    //                     ELSE surat_angkuts.jumlah_barang * hargas.harga_ball
    //                 END
    //             WHEN orderans.jenis_berat = "tonase" THEN
    //                 CASE
    //                     WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
    //                         CASE
    //                             WHEN hargas.sub_syarat_berat IS NOT NULL THEN
    //                                 ((parties.berat_barang - hargas.sub_syarat_berat) * hargas.harga_tonase) + hargas.diskon_tonase_sub
    //                             END
    //                     ELSE
    //                         parties.berat_barang * hargas.harga_tonase
    //                     END
    //             ELSE 0
    //             END AS total_harga
    //         '));

    //     if ($tagihan_by == 1) {
    //         $invoiceWeekly->where('orderans.nama_customer', $nama);
    //     } else {
    //         $invoiceWeekly->where('orderans.nama_penerima', $nama);
    //     }

    //     // Fetch the data
    //     $invoiceWeeklyData = $invoiceWeekly->distinct()->first();
    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml(view('invoiceWeekly.pdf', compact('invoiceWeekly'))->render());
    //     $dompdf->setPaper('A4', 'portrait');
    //     $dompdf->render();

    //     // Menghasilkan file PDF dan mengirimkan ke browser
    //     $pdfFileName = 'invoice' . '.pdf';
    //     $dompdf->stream($pdfFileName);

    // }

}
