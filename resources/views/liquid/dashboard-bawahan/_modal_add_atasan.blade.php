<div class="modal fade" id="modal_add_atasan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="title title-top">Pilih Atasan</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_liquid" value="{{ $idLiquid }}">
                <input type="hidden" name="pernr" value="{{ auth()->user()->strukturJabatan->pernr }}">
                <div class="form-group">
                    <label for="pejabat" class="col-form-label">Nama :</label>
                    <select name="atasan" id="select_atasan" class="select2 form-control">
                        <option value="">--Pilih--</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveAtasan()">
                    Pilih
                </button>
            </div>
        </div>
    </div>
</div>
