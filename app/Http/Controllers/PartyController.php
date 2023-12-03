<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Party;
use App\Models\Penerima;
use App\Models\Surat_angkut;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartyController extends Controller
{
    public function index()
    {
        $surat_angkut = Surat_angkut::leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->where('surat_angkuts.status', 1)
            ->orderby('surat_angkuts.nomor_sa', 'asc')
            ->get();

        return view('party.index', compact('surat_angkut'));

    }

    public function data()
    {
        $party = DB::table('parties')->orderBy('parties.tanggal_pembuatan', 'desc')
            ->select('parties.*',
                'orderans.id_harga',
                'orderans.tagihan_by',
                'surat_angkuts.kode_tanda_penerima',
                'surat_angkuts.status as status',
                'surat_angkuts.tanggal_pengambilan as tanggal_pengambilan',
                'surat_angkuts.tanggal_kirim as tanggal_kirim',
                'surat_angkuts.tanggal_kembali as tanggal_kembali',
                'surat_angkuts.tanggal_ditagihkan as tanggal_ditagihkan',
                'hargas.*')
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->distinct()
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

                                ((parties.berat_barang-hargas.sub_syarat_berat) * hargas.harga_tonase)+(hargas.diskon_tonase_sub)

                            END

                    ELSE
                        parties.berat_barang * hargas.harga_tonase
                END
            ELSE 0
        END AS total_harga
    '))
            ->get();
        return datatables()
            ->of($party)
            ->addIndexColumn()
            ->addColumn('aksi', function ($party) {
                $disabled = '';
                if (auth()->user()->level == 2 || auth()->user()->level == 3) {
                    if ($party->status > 1) {
                        $disabled = 'disabled';
                    }
                } else {
                    $disabled = 'enabled';
                }

                return '

                    <div class="btn-group">

                    <button type="button" onclick="showDetail(`' . route('party.show', $party->id_party) . '`)" class="btn btn-xs btn-primary btn-flat" ><i class="fa fa-eye"></i></button>

                        <button type="button" onclick="deleteData(`' . route('party.destroy', $party->id_party) . '`)" class="btn btn-xs btn-danger btn-flat"' . $disabled . '><i class="fa fa-trash"></i></button>


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
        $nomor_sa = $request->nomor_sa;
        // dd($nomor_sa);
        $surat_angkut = Surat_angkut::where('nomor_sa', $nomor_sa)->first();
        // dd($surat_angkut);
        $customer = Customer::where('nama_customer', $surat_angkut->nama_customer)->first();
        $penerima = Penerima::where('nama_penerima', $surat_angkut->nama_penerima)->first();

        $parties = new party();

        // Memeriksa apakah ada data di database dengan nilai supir dan no_mobil yang sama
        $parties->nomor_party = $request->nomor_party;

        $parties->nomor_dm = $request->nomor_party;

        $parties->nomor_sa = $surat_angkut->nomor_sa;

        $parties->nama_customer = $surat_angkut->nama_customer;

        $parties->alamat_customer = $surat_angkut->alamat_customer;

        $parties->telepon_customer = $surat_angkut->telepon_customer;

        $parties->total_jumlah_barang = $surat_angkut->total_jumlah_barang;

        $parties->jumlah_barang = $surat_angkut->jumlah_barang;

        $parties->berat_barang = $request->berat_barang;

        $parties->nama_penerima = $surat_angkut->nama_penerima;

        $parties->alamat_penerima = $surat_angkut->alamat_penerima;

        $parties->telepon_penerima = $surat_angkut->telepon_penerima;

        $parties->supir = $request->supir;

        $parties->no_mobil = $request->no_mobil;

        $parties->keterangan = $surat_angkut->keterangan;

        $parties->tanggal_pembuatan = $request->tanggal_pembuatan;
        $parties->save();

        $surat_angkut->tanggal_kirim = now();
        $surat_angkut->status = 2;
        $surat_angkut->update();
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
        $party = DB::table('parties')->orderBy('parties.created_at', 'desc')
            ->where('parties.id_party', $id)
            ->select('parties.*',
                'orderans.id_harga',
                'orderans.tagihan_by',
                'surat_angkuts.kode_tanda_penerima',
                'surat_angkuts.status as status',
                'surat_angkuts.tanggal_pengambilan as tanggal_pengambilan',
                'surat_angkuts.tanggal_kirim as tanggal_kirim',
                'surat_angkuts.tanggal_kembali as tanggal_kembali',
                'surat_angkuts.tanggal_ditagihkan as tanggal_ditagihkan',
                'hargas.*')
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->distinct()
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

                                ((parties.berat_barang-hargas.sub_syarat_berat) * hargas.harga_tonase)+(hargas.diskon_tonase_sub)

                            END

                    ELSE
                        parties.berat_barang * hargas.harga_tonase
                END
            ELSE 0
        END AS total_harga
    '))
            ->first();

        return response()->json($party);
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
        $get_party = $request->kode_party;

        $party = Party::where('kode_party', $get_party)->first();
        if (!empty($party)) {
            $party = Party::find($id);

            $party->nomor_sa = $request->nomor_sa;

            $party->nama_customer = $request->nama_customer;
            $party->alamat_customer = $request->alamat_customer;
            $party->telepon_customer = $request->telepon_customer;

            $party->total_jumlah_barang = $request->total_jumlah_barang;
            $party->total_berat_barang = $request->total_berat_barang;

            $party->nama_penerima = $request->nama_penerima;
            $party->alamat_penerima = $request->alamat_penerima;
            $party->telepon_penerima = $request->telepon_penerima;

            $party->supir = $orderan->supir;
            $party->no_mobil = $orderan->no_mobil;
            $party->keterangan = $request->keterangan;
            $party->update();
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
        $party = Party::find($id)->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->party as $id) {
            $party = Party::find($id);
            $party->delete();
        }

        return response(null, 204);
    }

    public function exportCSV()
    {
        $party = Party::get()->toArray();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="party_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($party) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($party[0]));
            foreach ($party as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function exportfilter(Request $request)
    {
        $kode_party = $request->kode_party;
        $kode_dm = $request->kode_dm;
        $nomor_sa = $request->nomor_sa;
        $nama_customer = $request->nama_customer;
        $nama_penerima = $request->nama_penerima;
        $supir = $request->supir;
        $no_mobil = $request->no_mobil;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $party = DB::table('parties')
            ->select(
                'parties.tanggal_pembuatan',
                'parties.nomor_dm',
                'parties.nomor_sa',
                'parties.nama_customer',
                'parties.nama_penerima',
                'parties.jumlah_barang',
                'parties.berat_barang',
                'orderans.id_harga',
                'orderans.jenis_berat',
                'orderans.supir as supir_pengambil',
                'orderans.no_mobil as no_mobil_pengambil',
                'parties.supir as supir_pengantar',
                'parties.no_mobil as no_mobil_pengantar',

            )
            ->leftJoin('surat_angkuts', 'parties.nomor_sa', '=', 'surat_angkuts.nomor_sa')
            ->leftJoin('orderans', 'surat_angkuts.kode_tanda_penerima', '=', 'orderans.kode_tanda_penerima')
            ->leftJoin('hargas', 'orderans.id_harga', '=', 'hargas.id_harga')
            ->distinct();
        if ('orderans.jenis_berat' == 'roll') {
            if ('surat_angkuts.jumlah_barang < hargas.syarat_jumlah') {
                $party->addSelect(DB::raw('hargas.diskon_roll as harga'));
            } else {
                $party->addSelect(DB::raw('hargas.harga_roll as harga'));
            }
        } else if ('orderans.jenis_berat' == 'ball') {
            if ('surat_angkuts.jumlah_barang < hargas.syarat_jumlah') {
                $party->addSelect(DB::raw('hargas.diskon_ball as harga'));
            } else {
                $party->addSelect(DB::raw('hargas.harga_ball as harga'));
            }
        } else {
            if ('parties.berat_barang < hargas.main_syarat_berat') {
                if ('hargas.sub_syarat_berat IS NOT NULL') {
                    $party->addSelect(DB::raw('hargas.harga_tonase as harga'));
                } else {
                    $party->addSelect(DB::raw('hargas.harga_tonase as harga'));
                }
            } else {
                $party->addSelect(DB::raw('hargas.harga_tonase as harga'));
            }
        }
        $party->addSelect(DB::raw('
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

                            ((parties.berat_barang-hargas.sub_syarat_berat) * hargas.harga_tonase)+(hargas.diskon_tonase_sub)

                        END

                ELSE
                    parties.berat_barang * hargas.harga_tonase
            END
        ELSE 0
        END AS total_harga
            '))
            ->addSelect(
                DB::raw(
                    ' CASE
                    WHEN orderans.jenis_berat = "roll" THEN
                        CASE
                            WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah
                            THEN hargas.diskon_roll
                            ELSE hargas.harga_roll
                        END
                    WHEN orderans.jenis_berat = "ball" THEN
                        CASE
                            WHEN surat_angkuts.jumlah_barang > hargas.syarat_jumlah
                            THEN hargas.diskon_ball
                            ELSE hargas.harga_ball
                        END
                    WHEN orderans.jenis_berat = "tonase" THEN
                        CASE
                            WHEN parties.berat_barang <= hargas.main_syarat_berat THEN
                            hargas.harga_tonase
                            ELSE
                            hargas.harga_tonase
                        END
                    ELSE 0
                END AS harga_satuan'
                )
            )
        ;

        if ($kode_party) {
            $party->where('nomor_party', $kode_party);
        }

        if ($kode_dm) {
            $party->where('nomor_dm', $kode_dm);
        }

        if ($nomor_sa) {
            $party->where('nomor_sa', $nomor_sa);
        }

        if ($nama_customer) {
            $party->where('nama_customer', $nama_customer);
        }

        if ($nama_penerima) {
            $party->where('nama_penerima', $nama_penerima);
        }

        if ($supir) {
            $party->where('supir', $supir);
        }

        if ($no_mobil) {
            $party->where('no_mobil', $no_mobil);
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $party->whereBetween('tanggal_pembuatan', [$tanggal_awal, $tanggal_akhir]);
        } else if ($tanggal_awal) {
            $party->where('tanggal_pembuatan', $tanggal_awal);
        }

        $results = $party->orderby('nomor_dm', 'asc')->get();

        // dd($results);

        $party = $results->toArray(); // Convert the results to an array

        // Create a new Dompdf instance
        $dompdf = new Dompdf();

        // Set the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Set the content
        // Set the content
        $content = '<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    caption {
        text-align: left;
        font-weight: bold;
        font-size: small;
    }

    th, td {
        border: 1px solid black;
        padding: 8px;
        font-size: small; /* Mengubah ukuran font menjadi kecil */
    }

    th {
        background-color: #dddddd;
    }
    </style>';

        $current_dm = null;
        $total_harga_dm = 0; // Total harga keseluruhan for each nomor_dm
        $total_barang_dm = 0; // Total banyak barang for each nomor_dm
        $total_berat_dm = 0; // Total berat keseluruhan for each nomor_dm

        foreach ($party as $pt) {
            // Check if the nomor_dm has changed
            if ($current_dm !== $pt->nomor_dm) {
                // Display the total harga for the previous nomor_dm (if any)
                if ($current_dm !== null) {
                    $content .= '<tr>
                                            <td colspan="10">Total Harga Keseluruhan</td>
                                            <td>Rp.' . $total_harga_dm . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="10">Total Jumlah Keseluruhan</td>
                                            <td>' . $total_barang_dm . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="10">Total Berat Keseluruhan</td>
                                            <td>' . $total_berat_dm . '</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br></div>';
                }

                // Start a new table group for the current nomor_dm
                $content .= '<div style="page-break-inside: avoid;">
                        <caption>Nama Supir Pengantar: ' . $pt->supir_pengantar . '</caption>
                        <caption>Nomor Mobil: ' . $pt->no_mobil_pengantar . '</caption>
                        <table>
                        <thead>
                            <tr>
                                <th>Tanggal Pembuatan</th>
                                <th>Supir Pengambil</th>
                                <th>No mobil Pengambil</th>
                                <th>Nomor DM</th>
                                <th>Nomor SA</th>
                                <th>Nama Customer</th>
                                <th>Nama Penerima</th>
                                <th>Jumlah Barang</th>
                                <th>Berat Barang</th>

                                <th>Harga</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>';

                $current_dm = $pt->nomor_dm;
                $total_harga_dm = 0; // Reset the total harga for the current nomor_dm
                $total_barang_dm = 0; // Reset the total banyak barang for the current nomor_dm
                $total_berat_dm = 0; // Reset the total berat for the current nomor_dm
            }

            $content .= '<tr>
                                    <td>' . $pt->tanggal_pembuatan . '</td>
                                    <td>' . $pt->supir_pengambil . '</td>
                                    <td>' . $pt->no_mobil_pengambil . '</td>
                                    <td>' . $pt->nomor_dm . '</td>
                                    <td>' . $pt->nomor_sa . '</td>
                                    <td>' . $pt->nama_customer . '</td>
                                    <td>' . $pt->nama_penerima . '</td>
                                    <td>' . $pt->jumlah_barang . '</td>
                                    <td>' . $pt->berat_barang . '</td>

                                    <td>Rp.' . $pt->harga_satuan . '</td>
                                    <td>Rp.' . $pt->total_harga . '</td>
                                </tr>';

            $total_harga_dm += $pt->total_harga; // Accumulate the total harga for the current nomor_dm
            $total_barang_dm += $pt->jumlah_barang; // Accumulate the total banyak barang for the current nomor_dm
            $total_berat_dm += $pt->berat_barang; // Accumulate the total berat for the current nomor_dm
        }

        if ($current_dm !== null) {
            $content .= '<tr>
                                        <td colspan="10">Total Harga Keseluruhan</td>
                                        <td>Rp.' . $total_harga_dm . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="10">Total Jumlah Keseluruhan</td>
                                        <td>' . $total_barang_dm . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="10">Total Berat Keseluruhan</td>
                                        <td>' . $total_berat_dm . '</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br></div>';
        }

        $dompdf->loadHtml($content);

        $dompdf->render();

        // Output the PDF as a download

        $pdfFileName = 'party' . now() . '.pdf';
        $dompdf->stream($pdfFileName, ['Attachment' => true]);

    }
}
