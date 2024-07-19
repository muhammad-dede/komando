<div class="modal fade" id="modalAtasanPengganti"  role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="title title-top">Cari Atasan Pengganti</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('liquid.peserta.update', $liquid) }}" id="formGantiAtasan">
                    {!! csrf_field() !!}
                    {!! method_field('PUT') !!}
                    <input type="hidden" name="atasan_lama" value="">
                    <div class="form-group">
                        <label for="pejabat" class="col-form-label">Nama :</label>
                        {!!
                            Form::select(
                                'atasan_baru',
                                $listAtasan,
                                null,
                                [
                                    'class' => 'select2 form-control'
                                ]
                            )
                        !!}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                    Cancel
                </button>
                <button form="formGantiAtasan" type="submit" class="btn btn-primary"><em class="fa fa-plus"></em>
                    Ganti Atasan
                </button>
            </div>
        </div>
    </div>
</div>
