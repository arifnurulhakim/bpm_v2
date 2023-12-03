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

                <label for="kode_party" class="col-lg-2 col-lg-offset-1 control-label">Kode Party</label>

                <div class="col-lg-6">

                    <input type="text" name="kode_party" id="kode_party" class="form-control" >

                    <span class="help-block with-errors"></span>

                </div>

            </div>

            <div class="form-group row">

                <label for="kode_dm" class="col-lg-2 col-lg-offset-1 control-label">Kode DM</label>

                <div class="col-lg-6">

                    <input type="text" name="kode_dm" id="kode_dm" class="form-control" >

                    <span class="help-block with-errors"></span>

                </div>

            </div>

            

            <div class="form-group row">

                <label for="nomor_sa" class="col-lg-2 col-lg-offset-1 control-label">Nomor Surat Angkut</label>

                <div class="col-lg-6">

                    <input type="text" name="nomor_sa" id="nomor_sa" class="form-control" >

                    <span class="help-block with-errors"></span>

                </div>

            </div>

            

            <div class="form-group row">

                <label for="nama_customer" class="col-lg-2 col-lg-offset-1 control-label">Nama Customer</label>

                <div class="col-lg-6">

                    <input type="text" name="nama_customer" id="nama_customer" class="form-control" >

                    <span class="help-block with-errors"></span>

                </div>

            </div>

            

            <div class="form-group row">

                <label for="nama_penerima" class="col-lg-2 col-lg-offset-1 control-label">Nama Penerima</label>

                <div class="col-lg-6">

                    <input type="text" name="nama_penerima" id="nama_penerima" class="form-control" >

                    <span class="help-block with-errors"></span>

                </div>

            </div>

            <div class="form-group row">

                <label for="supir" class="col-lg-2 col-lg-offset-1 control-label">supir</label>

                <div class="col-lg-6">

                    <input type="text" name="supir" id="supir" class="form-control" >

                    <span class="help-block with-errors"></span>

                </div>

            </div>

            <div class="form-group row">

                <label for="no_mobil" class="col-lg-2 col-lg-offset-1 control-label">no_mobil</label>

                <div class="col-lg-6">

                    <input type="text" name="no_mobil" id="no_mobil" class="form-control" >

                    <span class="help-block with-errors"></span>

                </div>

            </div>  
            
            <div class="form-group row">
                        <label for="tanggal_awal" class="col-lg-2 col-lg-offset-1 control-label">Tanggal</label>
                        <div class="col-lg-6">
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tanggal_akhir" class="col-lg-2 col-lg-offset-1 control-label">sampai</label>
                        <div class="col-lg-6">
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control">
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