@extends('layouts.master')



@section('title')

    Daftar Party

@endsection



@section('breadcrumb')

    @parent

    <li class="active">Daftar Party</li>

@endsection



@section('content')

<div class="row">

    <div class="col-lg-12">

        <div class="box">

            <div class="box-header with-border">

            <button onclick="addForm2('{{ route('party.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>

            <button onclick="addForm('{{ route('party.exportfilter') }}')" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i>Export PDF dengan filter</button>

                <a href="{{ route('party.exportCSV') }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export CSV</a>

            </div>



            <div class="box-body table-responsive">

                <form action="" method="post" class="form-produk">

                    @csrf

                    <table class="table table-stiped table-bordered">

                        <thead>

                            <th width="5%">No</th>

                            <th>Nomor Party</th>

                            <th>Nomor DM</th>

                            <th>Nomor SA</th>

                            <th>Nama Customer</th>

                            <th>Alamat Customer</th>

                            <!-- <th>Telepon Customer</th>

                            <th>Total Jumlah Barang</th>

                            <th>Jumlah Barang</th>

                            <th>Berat Barang</th>

                            <th>Total Harga</th> -->

                            <th>Nama Penerima</th>

                            <th>Alamat Penerima</th>

                            <!-- <th>Telepon Penerima</th> -->

                            <th>Supir</th>

                            <th>No Mobil</th>

                            <!-- <th>Keterangan</th>

                            <th>status</th>

                            <th>beban tagihan oleh</th>

                            <th>Tanggal Pengambilan</th>

                            <th>Tanggal Dikirim</th>

                            <th>Tanggal dikembalikan</th>

                            <th>Tanggal Ditangihkan</th> -->

                            <th>Tanggal Pembuatan</th>



                            <th width="15%"><i class="fa fa-cog"></i></th>

                        </thead>

                    </table>

                </form>

            </div>

        </div>

    </div>

</div>



@includeIf('party.form')

@includeIf('party.form2')
@includeIf('party.show')

@endsection



@push('scripts')

<script>
     // Mendapatkan elemen tombol "Simpan"
     var simpanButton = document.querySelector('#modal-form .modal-footer button.btn-primary');

// Menambahkan event listener untuk menutup modal saat tombol "Simpan" ditekan
simpanButton.addEventListener('click', function() {
    // Mendapatkan elemen modal
    var modal = document.querySelector('#modal-form');

    // Menutup modal
    $(modal).modal('hide');
});

    let table;



    $(function () {

        table = $('.table').DataTable({

            responsive: true,

            processing: true,

            serverSide: true,

            autoWidth: false,

            ajax: {

                url: '{{ route('party.data') }}',

            },

            columns: [



                {data: 'DT_RowIndex', searchable: false, sortable: false},

                {data: 'nomor_party'},

                {data: 'nomor_dm'},

                {data: 'nomor_sa'},

                {data: 'nama_customer'},

                {data: 'alamat_customer'},

                // {data: 'telepon_customer'},

                // {data: 'total_jumlah_barang'},

                // {data: 'jumlah_barang'},

                // {data: 'berat_barang'},

                // {data: 'total_harga'},

                {data: 'nama_penerima'},

                {data: 'alamat_penerima'},

                // {data: 'telepon_penerima'},

                {data: 'supir'},

                {data: 'no_mobil'},

                // {data: 'keterangan'},



                // {

                //     data: 'status',

                //     render: function(data, type, row, meta){

                //         if (data === 1) {

                //             return "Diambil";

                //         } else if (data === 2) {

                //             return "Dikirim";

                //         } else if (data === 3) {

                //             return "Dikembalikan";

                //         } else if (data === 4) {

                //             return "Ditagihkan";

                //         } else {

                //             return "";

                //         }

                //     }

                // },

                // {

                //     data: 'tagihan_by',

                //     render: function(data, type, row, meta){

                //         if (data === 1) {

                //             return "Pengirim";

                //         } else if (data === 2) {

                //             return "Penerima";

                //         } else {

                //             return "";

                //         }

                //     }

                // },

                // {data: 'tanggal_pengambilan'},

                // {data: 'tanggal_kirim'},

                // {data: 'tanggal_kembali'},

                // {data: 'tanggal_ditagihkan'},

                {data: 'tanggal_pembuatan'},

                {data: 'aksi', searchable: false, sortable: false},

            ]

        });

        $('#modal-form2').validator().on('submit', function (e) {



if (! e.preventDefault()) {



    $.post($('#modal-form2 form').attr('action'), $('#modal-form2 form').serialize())



        .done((response) => {



            $('#modal-form2').modal('hide');



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

        $('#modal-form .modal-title').text('filter export');



        $('#modal-form form')[0].reset();

        $('#modal-form form').attr('action', url);

        $('#modal-form [name=_method]').val('post');


    }

    function addForm2(url) {

        $('#modal-form2').modal('show');

        $('#modal-form2 .modal-title').text('Tambah party');



        $('#modal-form2 form')[0].reset();

        $('#modal-form2 form').attr('action', url);

        $('#modal-form2 [name=nomor_party]').focus();

    }



    function editForm(url) {

        $('#modal-form2').modal('show');

        $('#modal-form2 .modal-title').text('Edit party');



        $('#modal-form2 form')[0].reset();

        $('#modal-form2 form').attr('action', url);

        $('#modal-form2 [name=_method]').val('put');

        $('#modal-form2 [name=nomor_party]').focus();



        $.get(url)

            .done((response) => {

                $('#modal-form2 [name=nomor_sa]').val(response.nomor_sa);

                $('#modal-form2 [name=kode_tanda_penerima]').val(response.kode_tanda_penerima);

                $('#modal-form2 [name=nama_customer]').val(response.nama_customer);

                $('#modal-form2 [name=alamat_customer]').val(response.alamat_customer);

                $('#modal-form2 [name=telepon_customer]').val(response.telepon_customer);

                $('#modal-form2 [name=nama_barang]').val(response.nama_barang);

                $('#modal-form2 [name=jumlah_barang]').val(response.jumlah_barang);

                $('#modal-form2 [name=berat_barang]').val(response.berat_barang);

                $('#modal-form2 [name=nama_penerima]').val(response.nama_penerima);

                $('#modal-form2 [name=alamat_penerima]').val(response.alamat_penerima);

                $('#modal-form2 [name=telepon_penerima]').val(response.telepon_penerima);

                $('#modal-form2 [name=supir]').val(response.supir);

                $('#modal-form2 [name=no_mobil]').val(response.no_mobil);

                $('#modal-form2 [name=keterangan]').val(response.keterangan);

                $('#modal-form2 [name=tanggal_kirim]').val(response.tanggal_kirim);

                $('#modal-form2 [name=tanggal_pengambilan]').val(response.tanggal_pengambilan);

                $('#modal-form2 [name=tanggal_kembali]').val(response.tanggal_kembali);

                $('#modal-form2 [name=harga]').val(response.harga);

                $('#modal-form2 [name=created_at]').val(response.created_at);

            })

            .fail((errors) => {

                alert('Tidak dapat menampilkan data');

                return;

            });

    }
    function showDetail(url) {
    // Show the detail modal
    $('#modal-detail').modal('show');
    $('#modal-detail .modal-title').text('Detail Surat Party');
    $.get(url)
        .done((response) => {
            console.log(response); // Log the response data
            // Populate the modal with data
            $('#detail-nomor_party').text(response.nomor_party);
            $('#detail-nomor_dm').text(response.nomor_dm);
            $('#detail-nomor_sa').text(response.nomor_sa);
            $('#detail-kode_tanda_penerima').text(response.kode_tanda_penerima);
            $('#detail-nama_customer').text(response.nama_customer);
            $('#detail-alamat_customer').text(response.alamat_customer);
            $('#detail-telepon_customer').text(response.telepon_customer);
            $('#detail-total_jumlah_barang').text(response.total_jumlah_barang);
            $('#detail-jumlah_barang').text(response.jumlah_barang);
            $('#detail-berat_barang').text(response.berat_barang);
            $('#detail-total_harga').text(response.total_harga);
            $('#detail-nama_penerima').text(response.nama_penerima);
            $('#detail-alamat_penerima').text(response.alamat_penerima);
            $('#detail-telepon_penerima').text(response.telepon_penerima);
            $('#detail-supir').text(response.supir);
            $('#detail-no_mobil').text(response.no_mobil);
            $('#detail-keterangan').text(response.keterangan);
            $('#detail-status').text(response.status == 1 ? "Diambil" : (response.status == 2 ? "Dikirim" : (response.status == 3 ? "Dikembalikan" : (response.status == 4 ? "Ditagihkan"  : (response.status == 5 ? "Lunas": "")))));
            $('#detail-beban_tagihan_oleh').text(response.tagihan_by == 1 ? "Pengirim" : (response.tagihan_by == 2 ? "Penerima" : ""));
            $('#detail-tanggal_pengambilan').text(response.tanggal_pengambilan);
            $('#detail-tanggal_kirim').text(response.tanggal_kirim);
            $('#detail-tanggal_kembali').text(response.tanggal_kembali);
            $('#detail-tanggal_ditagihkan').text(response.tanggal_ditagihkan);
            $('#detail-tanggal_pembuatan').text(response.tanggal_pembuatan);
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



    function deleteSelected(url) {

        if ($('input:checked').length > 1) {

            if (confirm('Yakin ingin menghapus data terpilih?')) {

                $.post(url, $('.form-produk').serialize())

                    .done((response) => {

                        table.ajax.reload();

                    })

                    .fail((errors) => {

                        alert('Tidak dapat menghapus data');

                        return;

                    });

            }

        } else {

            alert('Pilih data yang akan dihapus');

            return;

        }

    }
</script>

@endpush
