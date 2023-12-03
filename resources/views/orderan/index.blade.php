@extends('layouts.master')
@section('title')
    Daftar Orderan
@endsection
@section('breadcrumb')
    @parent
    <li class="active">Daftar Orderan</li>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('orderan.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                <a href="{{ route('orderan.exportCSV') }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export CSV</a>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode Tanda Penerima</th>
                            <th>nama customer</th>
                            <th>alamat customer</th>
                            <!-- <th>telepon customer</th> -->
                            <!-- <th>nama barang</th>
                            <th>Jumlah Barang</th>
                            <th>Sisa Jumlah Barang</th> -->
                            <!-- <th>Berat Barang</th> -->
                            <!-- <th>jenis Berat</th> -->
                            <th>Nama Penerima</th>
                            <th>Alamat Penerima</th>
                            <!-- <th>Telepon Penerima</th>
                            <th>Supir</th>
                            <th>Nomor Mobil</th>
                            <th>Keterangan</th> -->
                            <!-- <th>harga</th> -->
                            <th>status</th>
                            <!-- <th>beban tagihan oleh</th> -->
                            <!-- <th>Tanggal Pengambilan</th> -->
                            <th>Tanggal Dibuat</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@includeIf('orderan.form')
@includeIf('orderan.show')
@endsection
@push('scripts')
<!-- jQuery -->

<script>
    let table;
    var urlshow;
    $(function () {
        urlshow = '';
table = $('.table').DataTable({
responsive: true,
processing: true,
serverSide: true,
autoWidth: false,
ajax: {
url: '{{ route('orderan.data') }}',
},
columns: [
{data: 'DT_RowIndex', searchable: false, sortable: false},
{data: 'kode_tanda_penerima'},
{data: 'nama_customer'},
{data: 'alamat_customer'},
// {data: 'telepon_customer'},
// {data: 'nama_barang'},
// {data: 'jumlah_barang'},
// {data: 'sisa_jumlah_barang'},
// {data: 'berat_barang'},
// {data: 'jenis_berat'},
{data: 'nama_penerima'},
{data: 'alamat_penerima'},
// {data: 'telepon_penerima'},
// {data: 'supir'},
// {data: 'no_mobil'},
// {data: 'keterangan'},
// {data: 'harga'},
{
    data: 'status',
    render: function(data, type, row, meta){
        if (data == 1) {
            return "belum dibuat SA";
        } else if (data == 2) {
            return "sudah dibuat SA";
        } else {
            return "";
        }
    }
},
// {
//     data: 'tagihan_by',
//     render: function(data, type, row, meta){
//         if (data == 1) {
//             return "Pengirim";
//         } else if (data == 2) {
//             return "Penerima";
//         } else {
//             return "";
//         }
//     }
// },
// {data: 'tanggal_pengambilan'},
{data: 'created_at'},
{data: 'aksi', searchable: false, sortable: false},
],
});
        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });
    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Orderan');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=kode_tanda_penerima]').focus();
    }
    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Orderan');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=kode_tanda_penerima]').focus();
        $.get(url)
            .done((response) => {
                $('#modal-form [name=nomor_sa]').val(response.nomor_sa);
                $('#modal-form [name=id_orderan]').val(response.id_orderan);
                $('#modal-form [name=kode_tanda_penerima]').val(response.kode_tanda_penerima);
                $('#modal-form [name=nama_customer]').val(response.nama_customer);
                $('#modal-form [name=alamat_customer]').val(response.alamat_customer);
                $('#modal-form [name=telepon_customer]').val(response.telepon_customer);
                $('#modal-form [name=nama_barang]').val(response.nama_barang);
                $('#modal-form [name=jumlah_barang]').val(response.jumlah_barang);
                // $('#modal-form [name=berat_barang]').val(response.berat_barang);
                $('#modal-form [name=jenis_berat]').val(response.jenis_berat);
                $('#modal-form [name=nama_penerima]').val(response.nama_penerima);
                $('#modal-form [name=alamat_penerima]').val(response.alamat_penerima);
                $('#modal-form [name=telepon_penerima]').val(response.telepon_penerima);
                $('#modal-form [name=supir]').val(response.supir);
                $('#modal-form [name=no_mobil]').val(response.no_mobil);
                $('#modal-form [name=keterangan]').val(response.keterangan);
                $('#modal-form [name=tanggal_pengambilan]').val(response.tanggal_pengambilan);
                // $('#modal-form [name=harga]').val(response.harga);
                $('#modal-form [name=status]').val(response.status);
                $('#modal-form [name=tangihan_by]').val(response.tagihan_by);
                $('#modal-form [name=created_at]').val(response.created_at);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }
    function showDetail(url) {
    // Show the detail modal
    $('#modal-detail').modal('show');
    $('#modal-detail .modal-title').text('Detail Orderan');
    $.get(url)
        .done((response) => {
            console.log(response); // Log the response data
            // Populate the modal with data
            $('#detail-kode_tanda_penerima').text(response.kode_tanda_penerima);
            $('#detail-nama_customer').text(response.nama_customer);
            $('#detail-alamat_customer').text(response.alamat_customer);
            $('#detail-telepon_customer').text(response.telepon_customer);
            $('#detail-nama_barang').text(response.nama_barang);
            $('#detail-jumlah_barang').text(response.jumlah_barang);
            $('#detail-sisa_jumlah_barang').text(response.sisa_jumlah_barang);
            $('#detail-berat_barang').text(response.berat_barang);
            $('#detail-jenis_berat').text(response.jenis_berat);
            $('#detail-nama_penerima').text(response.nama_penerima);
            $('#detail-alamat_penerima').text(response.alamat_penerima);
            $('#detail-telepon_penerima').text(response.telepon_penerima);
            $('#detail-supir').text(response.supir);
            $('#detail-no_mobil').text(response.no_mobil);
            $('#detail-keterangan').text(response.keterangan);
            $('#detail-harga').text(response.harga);
            $('#detail-status').text(response.status == 1 ? "belum dibuat SA" : (response.status == 2 ? "sudah dibuat SA" : ""));
            $('#detail-tagihan_by').text(response.tagihan_by == 1 ? "Pengirim" : (response.tagihan_by == 2 ? "Penerima" : ""));
            $('#detail-tanggal_pengambilan').text(response.tanggal_pengambilan);
            $('#detail-created_at').text(response.created_at);
            // Add more lines to set other details if needed
        })
        .fail((errors) => {
            alert('Tidak dapat menampilkan detail');
        });
}



    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
    function exportPDF(url) {
        window.location.href = url;
    }
</script>
@endpush
