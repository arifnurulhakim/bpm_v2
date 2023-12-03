<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('dm.exportfilter') }}" method="post" class="form-horizontal">
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
                    <label for="nomor_sa" class="col-lg-2 col-lg-offset-1 control-label">Kode Daftar Muat</label>
                    <div class="col-lg-6">
                        <input type="text" name="kode_daftar_muat" id="kode_daftar_muat" class="form-control" >
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
                    <label for="jumlah_barang" class="col-lg-2 col-lg-offset-1 control-label">Jumlah Barang</label>
                    <div class="col-lg-6">
                        <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary" href="{{ route('dm.exportfilter') }}"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>