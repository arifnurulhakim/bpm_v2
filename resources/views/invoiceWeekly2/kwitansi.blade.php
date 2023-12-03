<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }
    </style>
</head>
<body style="font-family: tahoma; font-size: 8pt;">
    <center>
        <table style="width:600px; font-size: 8pt; font-family: calibri; border-collapse: collapse;" border="0">
            <tr>
                <td width="50%" align="left" style="padding-right: 80px; vertical-align: top">
                    <span style="font-size: 12pt"><b>BHUANA PUTRA MANDIRI</b></span><br>
                    BANDUNG<br>
                    Telp :  -
                </td>
                <td style="vertical-align: top" width="30%" align="left">
                    <b><span style="font-size: 12pt">Keterangan</span></b><br>
                    <table style="border: none; width: 100%;">
                <tr>
                    <td>No Kwitansi:</td>
                    <td>{{ $invoice->nomor_kwitansi }}</td>
                </tr>
                <tr>
                    <td>No Invoice:</td>
                    <td>{{ $invoice->nomor_invoice }}</td>
                </tr>
                <tr>
                    <td>Beban ditagihan pada:</td>
                    <td>{{ $invoice->tagihan_string }}</td>
                </tr>
                <tr>
                    <td>Tanggal:</td>
                    <td>{{ $date }}</td>
                </tr>
            </table>

                </td>
            </tr>
        </table>
        <table style="width:600px; font-size: 8pt; font-family: calibri; border-collapse: collapse;" border="0">
    <tr>
        <td width="50%" align="left" style="padding-right: 80px; vertical-align: top">
            <table style="width:50%;"border="0">
                <tr>
                    <td>Sudah diterima dari</td>
                    <td>:{{ $client->nama }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:{{ $client->alamat }}</td>
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
        </td>
        <!-- You can add more <td> elements here if needed -->
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
            @foreach($invoiceWeeklyData as $item)
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
            <tr>
                <td colspan="8">
                    <div style="text-align:right">Terbilang : {{$disebut}}</div>
                </td>
            </tr>
            </table>
<br><br><br>

<table cellspacing="0" style="width:600px; font-size:10pt; font-family:calibri; border-collapse: collapse;" border="0">

                <td colspan="8" >
                    <div style="text-align:left">Catatan Informasi Pembayaran : </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="text-align:left">No. Rekening Tagihan Kwitansi Bertarif Pajak</div>
                </td>
                <td colspan="5">
                    <div style="text-align:left">: Rek BCA 3370856825 (AN. Tri Sukses Transindo PT)</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="text-align:left">No. Rekening Tagihan Kwitansi Bertarif Non Pajak</div>
                </td>
                <td colspan="5">
                    <div style="text-align:left">: Rek BCA 2791316969 (AN. Esther Satiani)</div>
                </td>
            </tr>

        </table>
        <br><br><br>

        <table style="width:650px; font-size:9pt;" cellspacing="2">
            <tr>
                <td align="center">Diterima Oleh,<br><br><br><br><br><u>(.................................)</u></td>
                <td style="border:0px solid black; padding:5px; text-align:left; width:30%"></td>
                <td align="center">TTD,<br><br><br><br><br><u>(.................................)</u></td>
            </tr>
        </table>
    </center>
</body>
</html>
