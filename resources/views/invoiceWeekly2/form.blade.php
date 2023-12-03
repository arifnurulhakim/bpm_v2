<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
    <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')


            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>

                <div class="modal-body">


                    <div class="form-group row">
                        <label for="nomor_kwitansi" class="col-lg-2 col-lg-offset-1 control-label">Nomor Kwitansi</label>
                        <div class="col-lg-6">
                            <input type="text" name="nomor_kwitansi" id="nomor_kwitansi" class="form-control" required>
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
    const inputElement = document.getElementById("nomor_kwitansi");
    inputElement.addEventListener("input", function(event) {
        const inputValue = event.target.value;
        event.target.value = inputValue.replace(/[^0-9]/g, "");
    });
</script>
