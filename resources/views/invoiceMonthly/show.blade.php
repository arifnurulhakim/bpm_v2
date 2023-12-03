<style>
    /* Tambahkan CSS berdasarkan kebutuhan Anda */
.table2 {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
}

.table2 th,
.table2 td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

.table2 th {
    background-color: #f5f5f5;
}

.table2 tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

.table2 tbody tr:hover {
    background-color: #e0e0e0;
}

</style>
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Detail Surat Angkut</h4>
            </div>
            <div class="modal-body">
            <table id="invoiceTable" class="table2">
    <thead>
        <tr>
            <th>Nomor Surat Angkut</th>
            <th>Nama Customer</th>
            <th>Nama Penerima</th>
            <th>Tanggal Pengambilan</th>
            <th>Total Harga</th>
            <!-- Add more headers for other fields as needed -->
        </tr>
    </thead>
    <tbody>
        <!-- Data will be added here dynamically -->
    </tbody>
</table>
<dl class="dl-horizontal">
                    <dt>Jumlah Surat Angkut:</dt>
                    <dd id="detail-total_sa"></dd>

                    <dt>Nominal Keseluruhan:</dt>
                    <dd id="detail-total_keseluruhan"></dd>
                    </dl>

            </div>
            <div class="modal-footer">

    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Tutup</button>
</div>

        </div>
    </div>
</div>
