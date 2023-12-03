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

                    <div class="form-group row">

                        <label for="kode_tanda_penerima" class="col-lg-2 col-lg-offset-1 control-label">Kode Tanda Terima</label>

                        <div class="col-lg-6">

                            <input type="number" name="kode_tanda_penerima" id="kode_tanda_penerima" class="form-control" required autofocus>

                            <span class="help-block with-errors"></span>

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

                        <label for="nama_barang" class="col-lg-2 col-lg-offset-1 control-label">Nama barang</label>

                        <div class="col-lg-6">

                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>

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



                    <!-- <div class="form-group row">

                        <label for="berat_barang" class="col-lg-2 col-lg-offset-1 control-label">Berat Barang</label>

                        <div class="col-lg-6">

                            <input type="number" name="berat_barang" id="berat_barang" class="form-control" >

                            <span class="help-block with-errors"></span>

                        </div>

                    </div> -->



                    <div class="form-group row">

                        <label for="jenis_harga" class="col-lg-2 col-lg-offset-1 control-label">Jenis Satuan</label>

                        <div class="col-lg-6">

                            <select name="jenis_harga" id="jenis_harga" class="form-control" required>

                                <option value="">-- Pilih Jenis Satuan --</option>

                                <option value="roll">roll</option>

                                <option value="ball">ball</option>

                                <option value="tonase">tonase</option>

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

                        <label for="supir" class="col-lg-2 col-lg-offset-1 control-label">Supir</label>

                        <div class="col-lg-6">

                            <input type="text" name="supir" id="supir" class="form-control" >

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>



                    <div class="form-group row">

                        <label for="no_mobil" class="col-lg-2 col-lg-offset-1 control-label">Nomor Mobil</label>

                        <div class="col-lg-6">

                            <input type="text" name="no_mobil" id="no_mobil" class="form-control" >

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>



                    <div class="form-group row">

                        <label for="keterangan" class="col-lg-2 col-lg-offset-1 control-label">Keterangan</label>

                        <div class="col-lg-6">

                            <input type="text" name="keterangan" id="keterangan" class="form-control" >

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>




                    <div class="form-group row">

                        <label for="tagihan_by" class="col-lg-2 col-lg-offset-1 control-label">beban tagihan oleh</label>

                        <div class="col-lg-6">

                            <select name="tagihan_by" id="tagihan_by" class="form-control" required>

                                <option value="">-- Pilih penerima tagihan --</option>

                                <option value=1>Pengirim</option>

                                <option value=2>Penerima</option>

                            </select>

                            <span class="help-block with-errors"></span>

                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="tanggal_pengambilan" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Pengambilan</label>
                        <div class="col-lg-6">
                            <input type="date" name="tanggal_pengambilan" id="tanggal_pengambilan" class="form-control">
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