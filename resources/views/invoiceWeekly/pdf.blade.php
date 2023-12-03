<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
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
            padding: 1px;
            font-size: small;
        }

        th {
            background-color: #dddddd;
        }

        /* Tambahkan gaya sesuai kebutuhan Anda */
        .total-row td {
            font-weight: bold;
        }
    </style>
</head>
<body>

<caption><b>RINCIAN TAGIHAN BANDUNG</b></caption>
<caption>Tanggal : {{ $formattedDate }}</caption>
<br>
    <table>
        <thead>
            <tr>
                <th>Nomor Kwitansi</th>
                <th>Nomor Invoice</th>
                <th>Nama</th>
                <th>Jumlah SA</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Rincian SA</th>
                <th>Keterangan</th>
                <th>Tanggal Lunas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoiceWeeklyData as $row)
                <tr>
                    <td style="text-align:center">{{ $row->nomor_kwitansi }}</td>
                    <td style="text-align:center">{{ $row->nomor_invoice }}</td>
                    <td style="text-align:center">{{ $row->nama }}</td>
                    <td style="text-align:center">{{ $row->total_sa }}</td>
                    <td style="text-align:right">{{ $row->total_harga }}</td>
                    <td style="text-align:right">{{ $row->harga }}</td>
                    <td style="text-align:right">{{ $row->nomor_sa }}</td>
                    <td style="text-align:center">{{ $row->keterangan }}</td>
                    <td style="text-align:center">{{ $row->tanggal_lunas }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td style="text-align:right">{{ $totalSa }}</td>
                <td style="text-align:right">Rp. {{ $totalKeseluruhan }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
