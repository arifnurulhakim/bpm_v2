<div class="modal fade" id="modal-form2" tabindex="-1" role="dialog" aria-labelledby="modal-form">

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

                    <div class="form-group row">

                        <label for="nomor_party" class="col-lg-2 col-lg-offset-1 control-label">nomor party</label>

                        <div class="col-lg-6">

                            <input type="number" name="nomor_party" id="nomor_party" class="form-control" required autofocus>

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>

                    <div class="form-group row">

                        <label for="nomor_sa" class="col-lg-2 col-lg-offset-1 control-label">nomor surat angkut</label>

                        <div class="col-lg-6">

                            <select name="nomor_sa" id="nomor_sa" class="form-control" required autofocus>

                                <option value="">-- Pilih Nomor Surat Angkut --</option>

                                @foreach($surat_angkut as $sa)

                                <option value="{{ $sa->nomor_sa }}">{{ $sa->nomor_sa }}</option>

                                @endforeach

                            </select>

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>

                    <div class="form-group row">

                        <label for="jumlah_barang" class="col-lg-2 col-lg-offset-1 control-label">Berat Barang</label>

                        <div class="col-lg-6">

                            <input type="number" name="berat_barang" id="berat_barang" class="form-control" step="0.01" required>

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>


                    <div class="form-group row">

                        <label for="supir" class="col-lg-2 col-lg-offset-1 control-label">Supir</label>

                        <div class="col-lg-6">

                            <input type="text" name="supir" id="supir" class="form-control" required>

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>



                    <div class="form-group row">

                        <label for="no_mobil" class="col-lg-2 col-lg-offset-1 control-label">Nomor Mobil</label>

                        <div class="col-lg-6">

                            <input type="text" name="no_mobil" id="no_mobil" class="form-control" required>

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>



                    <div class="form-group row">
                        <label for="tanggal_pembuatan" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Pembuatan</label>
                        <div class="col-lg-6">
                            <input type="date" name="tanggal_pembuatan" id="tanggal_pembuatan" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
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
  // Mendapatkan referensi elemen input
  var input = document.getElementById("berat_barang");

  // Menangkap peristiwa ketika nilai input berubah
  input.addEventListener("change", function() {
    // Mengubah nilai input menjadi tipe data double
    var nilaiDouble = parseFloat(input.value);

    // Memperbarui nilai input dengan tipe data double
    input.value = nilaiDouble;
  });
</script>
