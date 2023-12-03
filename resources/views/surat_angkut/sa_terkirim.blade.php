@extends('layouts.master')



@section('title')

    Daftar surat angkut

@endsection



@section('breadcrumb')

    @parent

    <li class="active">Daftar surat angkut terkirim</li>

@endsection



@section('content')

<div class="row">

    <div class="col-lg-12">

        <div class="box">

            <div class="box-header with-border">

                <a href="{{ route('surat_angkut.exportCSV') }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export CSV</a>

            </div>

            <div class="box-body table-responsive">

                <form action="" method="post" class="form-produk">

                    @csrf

                    <table class="table table-stiped table-bordered">

                        <thead>



                            <th width="5%">No</th>

                            <th>Update Status</th>

                            <th>Nomor surat angkut</th>

<th>Kode Tanda Penerima</th>

<th>nama customer</th>

<th>alamat customer</th>
<!--
<th>telepon customer</th>

<th>nama barang</th>

<th>Total Jumlah Barang</th>

<th>Jumlah Barang</th>

<th>Sisa Jumlah Barang</th> -->

<th>Nama Penerima</th>

<th>Alamat Penerima</th>

<!-- <th>Telepon Penerima</th> -->

<!-- <th>Supir</th>

                    <th>Nomor Mobil</th> -->

<!-- <th>Keterangan</th> -->

<!-- <th>harga</th> -->

<th>status</th>
<!--
<th>beban tagihan oleh</th>

<th>Tanggal Pengambilan</th>

<th>Tanggal Dikirim</th>

<th>Tanggal dikembalikan</th>

<th>Tanggal Ditanggihkan</th> -->

<th>Tanggal Dibuat</th>

<th width="15%"><i class="fa fa-cog"></i></th>

                        </thead>

                    </table>

                </form>

            </div>

        </div>

    </div>

</div>



@includeIf('surat_angkut.form')
@includeIf('surat_angkut.show')
@endsection



@push('scripts')

<script>

    let table;



    $(function () {

        table = $('.table').DataTable({

            responsive: true,

            processing: true,

            serverSide: true,

            autoWidth: false,

            ajax: {

                url: '{{ route('surat_angkut.data2') }}',

            },

            columns: [

                {data: 'DT_RowIndex', searchable: false, sortable: false},

                {data: 'update_status', searchable: false, sortable: false},

                {
data: 'nomor_sa'
},

{
data: 'kode_tanda_penerima'
},

{
data: 'nama_customer'
},

{
data: 'alamat_customer'
},

// {
// data: 'telepon_customer'
// },

// {
// data: 'nama_barang'
// },

// {
// data: 'total_jumlah_barang'
// },

// {
// data: 'jumlah_barang'
// },

// {
// data: 'sisa_jumlah_barang'
// },

// {data: 'berat_barang'},

{
data: 'nama_penerima'
},

{
data: 'alamat_penerima'
},

// {
// data: 'telepon_penerima'
// },

// {data: 'supir'},

// {data: 'no_mobil'},

// {
// data: 'keterangan'
// },

// {data: 'harga'},

{

data: 'status',

render: function(data, type, row, meta) {

    if (data == 1) {

        return "Diambil";

    } else if (data == 2) {

        return "Dikirim";

    } else if (data == 3) {

        return "Dikembalikan";

    } else if (data == 4) {

        return "Ditagihkan";

    } else {

        return "";

    }

}

},

// {

// data: 'tagihan_by',

// render: function(data, type, row, meta) {

//     if (data == 1) {

//         return "Pengirim";

//     } else if (data == 2) {

//         return "Penerima";

//     } else {

//         return "";

//     }

// }

// },

// {
// data: 'tanggal_pengambilan'
// },

// {
// data: 'tanggal_kirim'
// },

// {
// data: 'tanggal_kembali'
// },

// {
// data: 'tanggal_ditagihkan'
// },

{
data: 'created_at'
},
{
data: 'aksi',
searchable: false,
sortable: false
},


            ]

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

        $('#modal-form .modal-title').text('Tambah Produk');



        $('#modal-form form')[0].reset();

        $('#modal-form form').attr('action', url);

        $('#modal-form [name=_method]').val('post');

        $('#modal-form [name=kode_tanda_penerima]').focus();

    }



    function editForm(url) {

        $('#modal-form').modal('show');

        $('#modal-form .modal-title').text('Edit Produk');



        $('#modal-form form')[0].reset();

        $('#modal-form form').attr('action', url);

        $('#modal-form [name=_method]').val('put');

        $('#modal-form [name=kode_tanda_penerima]').focus();



        $.get(url)

            .done((response) => {

                $('#modal-form [name=nomor_sa]').val(response.nomor_sa);

                $('#modal-form [name=kode_tanda_penerima]').val(response.kode_tanda_penerima);

                $('#modal-form [name=nama_customer]').val(response.nama_customer);

                $('#modal-form [name=alamat_customer]').val(response.alamat_customer);

                $('#modal-form [name=telepon_customer]').val(response.telepon_customer);

                $('#modal-form [name=nama_barang]').val(response.nama_barang);

                $('#modal-form [name=total_jumlah_barang]').val(response.total_jumlah_barang);

                $('#modal-form [name=jumlah_barang]').val(response.jumlah_barang);

                $('#modal-form [name=sisa_jumlah_barang]').val(response.sisa_jumlah_barang);

                // $('#modal-form [name=berat_barang]').val(response.berat_barang);

                $('#modal-form [name=nama_penerima]').val(response.nama_penerima);

                $('#modal-form [name=alamat_penerima]').val(response.alamat_penerima);

                $('#modal-form [name=telepon_penerima]').val(response.telepon_penerima);

                // $('#modal-form [name=supir]').val(response.supir);

                // $('#modal-form [name=no_mobil]').val(response.no_mobil);

                $('#modal-form [name=keterangan]').val(response.keterangan);

                $('#modal-form [name=tanggal_kirim]').val(response.tanggal_kirim);

                $('#modal-form [name=tanggal_pengambilan]').val(response.tanggal_pengambilan);

                $('#modal-form [name=tanggal_kembali]').val(response.tanggal_kembali);

                $('#modal-form [name=harga]').val(response.harga);

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
    $('#modal-detail .modal-title').text('Detail Surat Angkut');
    $.get(url)
        .done((response) => {
            console.log(response); // Log the response data
            // Populate the modal with data
            $('#detail-nomor_sa').text(response.nomor_sa);
            $('#detail-kode_tanda_penerima').text(response.kode_tanda_penerima);
            $('#detail-nama_customer').text(response.nama_customer);
            $('#detail-alamat_customer').text(response.alamat_customer);
            $('#detail-telepon_customer').text(response.telepon_customer);
            $('#detail-nama_barang').text(response.nama_barang);
            $('#detail-total_jumlah_barang').text(response.total_jumlah_barang);
            $('#detail-jumlah_barang').text(response.jumlah_barang);
            $('#detail-sisa_jumlah_barang').text(response.sisa_jumlah_barang);
            $('#detail-nama_penerima').text(response.nama_penerima);
            $('#detail-alamat_penerima').text(response.alamat_penerima);
            $('#detail-telepon_penerima').text(response.telepon_penerima);
            $('#detail-status').text(response.status == 1 ? "Diambil" : (response.status == 2 ? "Dikirim" : (response.status == 3 ? "Dikembalikan" : (response.status == 4 ? "Ditagihkan" : ""))));
            $('#detail-tagihan_by').text(response.tagihan_by == 1 ? "Pengirim" : (response.tagihan_by == 2 ? "Penerima" : ""));
            $('#detail-tanggal_pengambilan').text(response.tanggal_pengambilan);
            $('#detail-tanggal_kirim').text(response.tanggal_kirim);
            $('#detail-tanggal_kembali').text(response.tanggal_kembali);
            $('#detail-tanggal_ditagihkan').text(response.tanggal_ditagihkan);
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



    function updateStatus(url) {

        if (confirm('Apakah Anda yakin ingin mengubah status?')) {

            window.location.href = url;

        }

    }





    // function deleteSelected(url) {

    //     if ($('input:checked').length > 1) {

    //         if (confirm('Yakin ingin menghapus data terpilih?')) {

    //             $.post(url, $('.form-produk').serialize())

    //                 .done((response) => {

    //                     table.ajax.reload();

    //                 })

    //                 .fail((errors) => {

    //                     alert('Tidak dapat menghapus data');

    //                     return;

    //                 });

    //         }

    //     } else {

    //         alert('Pilih data yang akan dihapus');

    //         return;

    //     }

    // }



    // function cetakBarcode(url) {

    //     if ($('input:checked').length < 1) {

    //         alert('Pilih data yang akan dicetak');

    //         return;

    //     } else if ($('input:checked').length < 3) {

    //         alert('Pilih minimal 3 data untuk dicetak');

    //         return;

    //     } else {

    //         $('.form-produk')

    //             .attr('target', '_blank')

    //             .attr('action', url)

    //             .submit();

    //     }

    // }

</script>

@endpush
