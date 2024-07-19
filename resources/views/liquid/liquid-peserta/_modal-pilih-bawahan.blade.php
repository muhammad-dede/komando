<div class="modal fade" id="modalPilihBawahan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="title title-top">Cari Pejabat</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('liquid.peserta.store', $liquid) }}" id="formTambahBawahan">
                    {!! csrf_field() !!}
                    {!! method_field('POST') !!}
                    <input type="hidden" name="atasan_id" value="">
                    <div class="form-group">
                        <label for="pejabat" class="col-form-label">Nama :</label>
                        {!!
                            Form::select(
                                'bawahan[]',
                                $listBawahan,
                                null,
                                [
                                    'multiple' => 'multiple',
                                    'class' => 'select2 form-control'
                                ]
                            )
                        !!}
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Jabatan :</label>
                        {!!
                            Form::select(
                                'jabatan',
                                $listJabatan,
                                null,
                                [
                                    'class' => 'form-control'
                                ]
                            )
                        !!}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>
                    Cancel
                </button>
                <button form="formTambahBawahan" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>
