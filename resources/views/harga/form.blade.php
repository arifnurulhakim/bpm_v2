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
                        <label for="nama" class="col-lg-2 col-lg-offset-1 control-label">Nama customer</label>
                        <div class="col-lg-6">
                            <select name="nama_customer" id="nama_customer" class="form-control" required autofocus>
                                <option value="">-- Pilih Nama Customer --</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->nama_customer }}">{{ $customer->nama_customer }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama" class="col-lg-2 col-lg-offset-1 control-label">Nama penerima</label>
                        <div class="col-lg-6">
                            <select name="nama_penerima" id="nama_penerima" class="form-control" required autofocus>
                                <option value="">-- Pilih Nama penerima --</option>
                                @foreach($penerimas as $penerima)
                                <option value="{{ $penerima->nama_penerima }}">{{ $penerima->nama_penerima }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="harga_roll" class="col-lg-2 col-lg-offset-1 control-label">harga/roll</label>
                        <div class="col-lg-6">
                            <input type="number" name="harga_roll" id="harga_roll" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_ball" class="col-lg-2 col-lg-offset-1 control-label">harga/ball</label>
                        <div class="col-lg-6">
                            <input type="number" name="harga_ball" id="harga_ball" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_tonase" class="col-lg-2 col-lg-offset-1 control-label">harga/tonase</label>
                        <div class="col-lg-6">
                            <input type="number" name="harga_tonase" id="harga_tonase" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="main_syarat_berat" class="col-lg-2 col-lg-offset-1 control-label">syarat berat utama</label>
                        <div class="col-lg-6">
                            <input type="number" name="main_syarat_berat" id="main_syarat_berat" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sub_syarat_berat" class="col-lg-2 col-lg-offset-1 control-label">syarat berat tambahan</label>
                        <div class="col-lg-6">
                            <input type="number" name="sub_syarat_berat" id="sub_syarat_berat" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="syarat_jumlah" class="col-lg-2 col-lg-offset-1 control-label">syarat jumlah</label>
                        <div class="col-lg-6">
                            <input type="number" name="syarat_jumlah" id="syarat_jumlah" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="diskon_roll" class="col-lg-2 col-lg-offset-1 control-label">diskon/roll</label>
                        <div class="col-lg-6">
                            <input type="number" name="diskon_roll" id="diskon_roll" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diskon_ball" class="col-lg-2 col-lg-offset-1 control-label">diskon/ball</label>
                        <div class="col-lg-6">
                            <input type="number" name="diskon_ball" id="diskon_ball" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="diskon_tonase_sub" class="col-lg-2 col-lg-offset-1 control-label">harga khusus tonase pertama</label>
                        <div class="col-lg-6">
                            <input type="number" name="diskon_tonase_sub" id="diskon_tonase_sub" class="form-control">
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