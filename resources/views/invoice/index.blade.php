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
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>

                            <th width="5%">No</th>
                            <th>Update Status</th>
                            <th>Nomor kwitansi</th>
                            <th>Nomor Invoice</th>
                            <th>Nama</th>
                            <th>beban tagihan oleh</th>
                            <th>status periode</th>
                            <th>status</th>

                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@includeIf('invoice.show')
@push('scripts')
<script>

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('invoice.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'update_status', searchable: false, sortable: false},

                {
                data: 'nomor_kwitansi',
            },
                {
                data: 'nomor_invoice',
            },
                {
                data: 'nama',
            },
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
                {
                data: 'status_periode',
            },
            {
    data: 'tanggal_lunas',
    render: function(data, type, row, meta){
        return data ? "Lunas" : "Belum Lunas";
    }
},

            //     {
            //     data: 'status_surat',
            // },


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
            $('#detail-nama_customer').text(response.invoice.nama_customer);
            $('#detail-nama_penerima').text(response.invoice.nama_penerima);
            $('#detail-nomor_sa').text(response.invoice.nomor_sa);
            $('#detail-nomor_party').text(response.invoice.nomor_party);

            $('#invoiceTable tbody').empty();

            // Populate the table with data from the response
            response.invoice.forEach((invoice) => {
                $('#invoiceTable tbody').append(`
                    <tr>
                        <td>${invoice.nomor_sa}</td>
                        <td>${invoice.nama_customer}</td>
                        <td>${invoice.nama_penerima}</td>
                        <td>${invoice.tanggal_pengambilan}</td>
                        <td>${invoice.total_harga}</td>
                    </tr>
                `);
            });
        })
        .fail((errors) => {
            alert('Tidak dapat menampilkan detail');
        });
    }

    function updateStatus(url) {

if (confirm('Apakah Anda yakin ingin Melunaskan invoice ini?')) {

window.location.href = url;

}

}







</script>
@endpush
