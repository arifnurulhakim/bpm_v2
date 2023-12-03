<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                <!-- <div class="form-group row">
                    <label for="kode_tanda_penerima" class="col-lg-2 col-lg-offset-1 control-label">Kode Tanda Terima</label>
                    <div class="col-lg-6">
                        <select name="kode_tanda_penerima" id="kode_tanda_penerima" class="form-control" required autofocus>
                            <option value="">-- Pilih Kode Tanda Terima --</option>
                            @foreach($orderans as $orderan)
                            <option value="{{ $orderan->kode_tanda_penerima }}">{{ $orderan->kode_tanda_penerima }}</option>
                            @endforeach
                        </select>
                        <span class="help-block with-errors"></span>
                    </div>
                </div> -->
                <div class="form-group row">
                        <label for="nomor_sa" class="col-lg-2 col-lg-offset-1 control-label">Nomor Surat Angkut</label>
                        <div class="col-lg-6">
                            <input type="text" name="nomor_sa" id="nomor_sa" class="form-control"  required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                </div>

                <div class="form-group row">

                    <label for="nama_customer" class="col-lg-2 col-lg-offset-1 control-label">Nama Customer</label>

                    <div class="col-lg-6">

                        <select name="nama_customer" id="nama_customer" class="form-control" required autofocus>

                            <option value="">-- Pilih nama customer --</option>

                            @foreach($customer as $cs)

                            <option value="{{ $cs->nama_customer }}">{{ $cs->nama_customer }}</option>

                            @endforeach

                        </select>

                        <span class="help-block with-errors"></span>

                    </div>

                </div>


                <div class="form-group row">

                    <label for="nama_penerima" class="col-lg-2 col-lg-offset-1 control-label">Nama Penerima</label>

                    <div class="col-lg-6">

                        <select name="nama_penerima" id="nama_penerima" class="form-control" required autofocus>

                            <option value="">-- Pilih Nama Penerima --</option>

                            @foreach($penerima as $pm)

                            <option value="{{ $pm->nama_penerima }}">{{ $pm->nama_penerima }}</option>

                            @endforeach

                        </select>

                        <span class="help-block with-errors"></span>

                </div>
</div>



                <div class="form-group row">

                    <label for="total_jumlah_barang" class="col-lg-2 col-lg-offset-1 control-label">total Jumlah Barang</label>

                    <div class="col-lg-6">

                        <input type="number" name="total_jumlah_barang" id="total_jumlah_barang" class="form-control" required>

                        <span class="help-block with-errors"></span>

                    </div>

                </div>

                <div class="form-group row">

                    <label for="jumlah_barang" class="col-lg-2 col-lg-offset-1 control-label">Jumlah Barang</label>

                    <div class="col-lg-6">

                        <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required>

                        <span class="help-block with-errors"></span>

                    </div>

                </div>


                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
  const inputElement = document.getElementById("nomor_sa");
  inputElement.addEventListener("input", function(event) {
    const inputValue = event.target.value;
    event.target.value = inputValue.replace(/[^0-9]/g, "");
  });
</script>
