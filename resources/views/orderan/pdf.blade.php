<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Jalan Orderan {{ $orderan->id_orderan }}</title>
    <style>
        /* CSS untuk surat jalan */
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80mm;
            margin: auto;
            padding: 10mm;
            border: 1px solid #000;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 16pt;
            margin-bottom: 10mm;
        }
        .info {
            margin-bottom: 10mm;
        }
        .info .label {
            display: inline-block;
            width: 70mm;
            font-weight: bold;
        }
        .info .value {
            display: inline-block;
            width: 100mm;
            font-weight: normal;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10mm;
        }
        .table td, .table th {
            border: 1px solid #000;
            padding: 3mm;
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 10pt;
            margin-top: 10mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Surat Jalan Order {{ $orderan->id_orderan }}</div>
        <div class="header">Order {{ $orderan->id_orderan }}</div>
        <div class="info">
            <div class="label">Kode Tanda Terima:</div>
            <div class="value">{{ $orderan->kode_tanda_penerima }}</div>
        </div>
        <div class="info">
            <div class="label">Tanggal Order:</div>
            <div class="value">{{ $orderan->created_at }}</div>
        </div>
        <div class="info">
            <div class="label">Nama Customer:</div>
            <div class="value">{{ $orderan->nama_customer }}</div>
        </div>
        <div class="info">
            <div class="label">Alamat Customer:</div>
            <div class="value">{{ $orderan->alamat_customer }}</div>
        </div>
        <div class="info">
            <div class="label">Nama penerima:</div>
            <div class="value">{{ $orderan->nama_penerima }}</div>
        </div>
        <div class="info">
            <div class="label">Alamat penerima:</div>
            <div class="value">{{ $orderan->alamat_penerima }}</div>
        </div>
        <table class="table">
            <thead>
                <tr>

                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>{{ $orderan->nama_barang }}</td>
                    <td>{{ $orderan->jumlah_barang }}</td>
                </tr>

            </tbody>
        </table>
