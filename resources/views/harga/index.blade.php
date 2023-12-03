@extends('layouts.master')

@section('title')
    Daftar Harga
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Harga</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('harga.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama customer</th>
                        <th>Alamat customer</th>
                        <th>Nama Penerima</th>
                        <th>Alamat penerima</th>
                        <th>Harga/roll</th>
                        <th>Harga/ball</th>
                        <th>Harga/tonase</th>
                        
                        <th>syarat berat utama</th>
                        <th>syarat berat tambahan</th>
                        <th>syarat jumlah</th>

                        <th>diskon/roll</th>
                        <th>diskon/ball</th>
                       
                        <th>harga khusus tonase pertama</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('harga.form')
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
                url: '{{ route('harga.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_customer'},
                {data: 'alamat_customer'},
                {data: 'nama_penerima'},
                {data: 'alamat_penerima'},
                {data: 'harga_roll'},
                {data: 'harga_ball'},
                {data: 'harga_tonase'},
                {data: 'main_syarat_berat'},
                {data: 'sub_syarat_berat'},
                {data: 'syarat_jumlah'},

                {data: 'diskon_roll'},
                {data: 'diskon_ball'},
              
                {data: 'diskon_tonase_sub'},
                {data: 'aksi', searchable: false, sortable: false},
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
        $('#modal-form .modal-title').text('Tambah Harga');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_customer]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Harga');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_customer]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_customer]').val(response.nama_customer);
                $('#modal-form [name=alamat_customer]').val(response.alamat_customer);
                $('#modal-form [name=nama_penerima]').val(response.nama_penerima);
                $('#modal-form [name=alamat_penerima]').val(response.alamat_penerima);
                $('#modal-form [name=harga_roll]').val(response.harga_roll);
                $('#modal-form [name=harga_ball]').val(response.harga_ball);
                $('#modal-form [name=harga_tonase]').val(response.harga_tonase);
                $('#modal-form [name=main_syarat_berat]').val(response.main_syarat_berat);
                $('#modal-form [name=sub_syarat_berat]').val(response.sub_syarat_berat);
                $('#modal-form [name=syarat_jumlah]').val(response.syarat_jumlah);
                $('#modal-form [name=diskon_roll]').val(response.diskon_roll);
                $('#modal-form [name=diskon_ball]').val(response.diskon_ball);

                $('#modal-form [name=diskon_tonase_sub]').val(response.diskon_tonase_sub);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
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
</script>
@endpush