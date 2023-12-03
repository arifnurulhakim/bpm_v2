<!DOCTYPE html>
<html>
<head>
    <title>Invoice Customer</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }

        body {
            font-family: tahoma;
            font-size: 8pt;
            text-align: center; /* Mengatur seluruh konten dalam body menjadi di tengah */
        }
    </style>
</head>
<body style="font-family: tahoma; font-size: 8pt;">
            <table style="width:50%;"border="0">
                <tr>
                    <td colspan="2">  <b><span style="font-size: 8pt">kami PT Tri Sukses Transindo menagihkan ongkos angkut barang dengan rincian sebagai berikut:</span></b></td>
                </tr>
                <tr>
                    <td>No Kwitansi</td>
                    <td>:{{ $invoice->nomor_kwitansi }}</td>
                </tr>
                <tr>
                    <td>No Invoice</td>
                    <td>:{{ $invoice->nomor_invoice }}</td>
                </tr>
                <tr>
                    <td>Beban ditagihan pada</td>
                    <td>:{{ $invoice->tagihan_string }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:{{ $date }}</td>
                </tr>
                <tr>
                    <td>Nama Customer</td>
                    <td>:{{ $client->nama }}</td>
                </tr>
                <tr>
                    <td>No Telp</td>
                    <td>:{{ $client->telepon }}</td>
                </tr>
                <tr>
                    <td>Terlapir</td>
                    <td>:{{$totalSa}} Lembar SA</td>
                </tr>
            </table>


        <table cellspacing="1" style="width:600px; font-size:8pt; font-family:calibri; border-collapse: collapse;" border="1">
            <tr align="center">
                <td width="10%">Nomor Surat Angkut</td>
                <td width="20%">Nama Pengirim</td>
                <td width="20%">Nama Penerima</td>
                <td width="13%">Harga</td>
                <td width="4%">Jenis Berat</td>
                <td width="7%">Berat</td>
                <td width="7%">Qty</td>
                <td width="13%">Total Harga</td>
            </tr>
            @foreach($invoiceMonthlyData as $item)
                <tr>
                    <td>{{ $item->nomor_sa }}</td>
                    <td>{{ $item->nama_customer }}</td>
                    <td>{{ $item->nama_penerima }}</td>
                    <td>Rp.{{ $item->harga_terpakai }}</td>
                    <td>{{ $item->jenis_berat }}</td>
                    <td>{{ $item->berat_barang }}</td>
                    <td>{{ $item->jumlah_barang }}</td>
                    <td style="text-align:right"><b>Rp.{{ $item->total_harga }}</b></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5">
                    <div style="text-align:right">Total Keseluruhan : </div>
                </td>
                <td style="text-align:right"><b>{{$totalberat}}</b></td>
                <td style="text-align:right"><b>{{$totaljumlah}}</b></td>
                <td style="text-align:right"><b>Rp.{{$totalKeseluruhan}}</b></td>
            </tr>

            </table>
<br><br><br>

<table cellspacing="0" style="width:100%; font-size:10pt; font-family:calibri; border-collapse: collapse;" border="0">
    <tr>
        <td colspan="8">
            <div style="text-align:left; font-weight: bold;">TERBILANG : <span style="font-style: italic;">#{{$disebut}}#</span></div>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            <div style="text-align:left; font-weight: bold;">Catatan Informasi Pembayaran :</div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div style="text-align:left; font-weight: bold;">No. Rekening Tagihan Kwitansi Bertarif Pajak</div>
        </td>
        <td colspan="5">
            <div style="text-align:left; font-weight: bold;">: Rek BCA 3370856825 (AN. Tri Sukses Transindo PT)</div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div style="text-align:left; font-weight: bold;">No. Rekening Tagihan Kwitansi Bertarif Non Pajak</div>
        </td>
        <td colspan="5">
            <div style="text-align:left; font-weight: bold;">: Rek BCA 2791316969 (AN. Esther Satiani)</div>
        </td>
    </tr>
</table>
</body>
</html>
