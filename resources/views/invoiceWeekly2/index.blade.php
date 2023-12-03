@extends('layouts.master')

@section('title')
    Daftar Invoice
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Invoice</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
            <!-- <button href="('{{ route('invoiceWeekly.exportfilter') }}')" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i> Export PDF</button> -->

                <a href="{{ route('invoiceWeekly.exportfilter') }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>
                <a href="{{ route('invoiceWeekly.exportCSV') }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export CSV</a>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>

                            <th width="5%">No</th>
                            <th>Kelola Kwitansi</th>
                            <th>Nomor kwitansi</th>
                            <th>Nomor Invoice</th>
                            <th>Nama</th>
                            <th>beban tagihan oleh</th>
                            <th>aksi</th>

                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@includeIf('invoiceWeekly.show')
@includeIf('invoiceWeekly.form')
<!-- @includeIf('invoiceWeekly.form') -->
@push('scripts')
<script>

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('invoiceWeekly.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                // {data: 'update_status', searchable: false, sortable: false},
                {data: 'kwitansi', searchable: false, sortable: false},
                {
                data: 'nomor_kwitansi',
            },
                {
                data: 'nomor_invoice',
            },
                {
                data: 'nama',
            },
            //     {
            //     data: 'nama_penerima',
            // },
                {
                    data: 'tagihan_by',
                    render: function(data, type, row, meta){
                        if (data === 1) {
                            return "Pengirim";
                        } else if (data === 2) {
                            return "Penerima";
                        } else {
                            return "";
                        }
                    }
                },
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

    function showDetail(url) {
    // Show the detail modal
    $('#modal-detail').modal('show');
    $('#modal-detail .modal-title').text('Detail Surat Party');
    $.get(url)
        .done((response) => {
            console.log(response); // Log the response data
            // Clear existing table data

            $('#detail-total_sa').text(response.total_sa);
$('#detail-total_keseluruhan').text(response.total_keseluruhan);

$('#detail-nama_customer').text(response.invoiceWeekly.nama_customer);
$('#detail-nama_penerima').text(response.invoiceWeekly.nama_penerima);
$('#detail-nomor_sa').text(response.invoiceWeekly.nomor_sa);
$('#detail-nomor_party').text(response.invoiceWeekly.nomor_party);

            $('#invoiceTable tbody').empty();

            // Populate the table with data from the response
            response.invoiceWeekly.forEach((invoice) => {
                $('#invoiceTable tbody').append(`
                    <tr>
                        <td>${invoice.nomor_sa}</td>
                        <td>${invoice.nama_customer}</td>
                        <td>${invoice.nama_penerima}</td>
                        <td>${invoice.tanggal_pengambilan}</td>
                        <td>${invoice.total_harga}</td>
                        <!-- Add more <td> elements for other fields as needed -->
                    </tr>
                `);
            });
        })
        .fail((errors) => {
            alert('Tidak dapat menampilkan detail');
        });


    }
    function editForm(url) {

    $('#modal-form').modal('show');

    $('#modal-form .modal-title').text('Cetak Kwitansi');
    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=tagihan_by]').focus();
    $.get(url)
    .done((response) => {

    $('#modal-form [name=tagihan_by]').val(response.tagihan_by);

    $('#modal-form [name=nama_customer]').val(response.nama_customer);

    $('#modal-form [name=nama_penerima]').val(response.nama_penerima);


    })

    .fail((errors) => {

    alert('Tidak dapat menampilkan data');

    return;

    });

    }

    function cetakKwitansi(url) {

window.location.href = url;

}
    function cetakCustomer(url) {

window.location.href = url;

}




</script>
@endpush
