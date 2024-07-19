<div class="form-group">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="form-group">
                <label>Judul Media & Banner<span class="text-danger">*</span></label>
                {!! Form::text('judul', old('judul', $item->judul), ['class' => 'form-control', 'required' => true]) !!}
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Jenis <span class="text-danger">*</span></label>
                        {!! Form::select('jenis', $jenis, old('jenis', $item->jenis), ['class' => 'select2 form-control form-control-danger', 'required' => true]) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        {!! Form::select('status', $status, old('status', $item->status), ['class' => 'select2 form-control form-control-danger', 'required' => true]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
