<!DOCTYPE html>
<html>
<head>
    <title>Larave Generate Invoice PDF - Nicesnippest.com</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;
    }
    .w-85{
        width:85%;
    }
    .w-15{
        width:15%;
    }
    .logo img{
        width:45px;
        height:45px;
        padding-top:30px;
    }
    .logo span{
        margin-left:8px;
        top:19px;
        position: absolute;
        font-weight: bold;
        font-size:25px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Surat Angkut</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Nomor SA : <span class="gray-color">{{ $invoiceWeekly->nomor_sa }}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Kode Tanda Penerima : <span class="gray-color">{{ $invoiceWeekly->kode_tanda_penerima }}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Supir : <span class="gray-color">{{ $invoiceWeekly->supir }}</span></p>
        <p class="m-0 pt-5 text-bold w-100">No Mobil : <span class="gray-color">{{ $invoiceWeekly->no_mobil }}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Keterangan : <span class="gray-color">{{ $invoiceWeekly->keterangan }}</span></p>
        <p class="m-0 pt-5 text-bold w-100">tanggal Orderan : <span class="gray-color">{{ $invoiceWeekly->created_at }}</span></p>
    </div>

    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">PENGIRIM</th>
            <th class="w-50">PENERIMA</th>
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <p>{{ $invoiceWeekly->nama_customer }}</p>
                    <p>{{ $invoiceWeekly->alamat_customer }}</p>
                    <p>Telepon : {{ $invoiceWeekly->telepon_customer }}</p>
                </div>
            </td>
            <td>
                <div class="box-text">
                    <p>{{ $invoiceWeekly->nama_penerima }}</p>
                    <p>{{ $invoiceWeekly->alamat_penerima }}</p>
                    <p>Telepon : {{ $invoiceWeekly->telepon_penerima }}</p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">

</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Nama Barang</th>
            <th class="w-50">Jumlah Barang</th>
            <th class="w-50">Berat Barang</th>

        </tr>
        <tr align="center">
            <td>{{ $invoiceWeekly->nama_barang }}</td>
            <td>{{ $invoiceWeekly->jumlah_barang }}</td>
            <td>{{ $invoiceWeekly->berat_barang }}</td>
        </tr>
    </table>
    <div class="signature-section mt-10">
        <div class="float-left w-50">
            <p class="text-center">Diterima Oleh:</p>
            <br>
            <br>
            <br>
            <br>
            <br>
            <p class="text-center">.............................................</p>
        </div>
    </div>
</div>

</html>
