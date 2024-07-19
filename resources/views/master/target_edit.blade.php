@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Edit Target</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                {!! Form::open(['url'=>'master-data/target-coc/'.$tahun.'/'.$jenjang->id.'/edit'])!!}
                <fieldset class="form-group">
                    <label for="tahun">Tahun</label>
                    {!! Form::text('tahun', $tahun, ['class'=>'form-control','id'=>'tahun', 'readonly']) !!}
                    {{--<input type="text" class="form-control" id="tahun" placeholder="Tahun" name="tahun">--}}
                    {{--<small class="text-muted">We'll never share your email with anyone else.</small>--}}
                </fieldset>
                <fieldset class="form-group">
                    <label for="jenjang">Jenjang Jabatan</label>
                    {!! Form::text('jenjang', $jenjang->jenjang_jabatan, ['class'=>'form-control','id'=>'jenjang', 'readonly']) !!}
                </fieldset>
                <fieldset class="form-group m-t-30">
                    <label>Triwulan I</label>
                    <div class="row">
                        <div class="col-md-2">
                            Cluster 1 {!! Form::text('t1_c1', @$cluster1->tw1, ['class'=>'form-control', 'placeholder'=>'Cluster 1', 'id'=>'t1_c1']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 2 {!! Form::text('t1_c2', @$cluster2->tw1, ['class'=>'form-control', 'placeholder'=>'Cluster 2', 'id'=>'t1_c2']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 3 {!! Form::text('t1_c3', @$cluster3->tw1, ['class'=>'form-control', 'placeholder'=>'Cluster 3', 'id'=>'t1_c3']) !!}
                        </div>
                    </div>
                </fieldset>
                <fieldset class="form-group m-t-30">
                    <label>Triwulan II</label>
                    <div class="row">
                        <div class="col-md-2">
                            Cluster 1 {!! Form::text('t2_c1', @$cluster1->tw2, ['class'=>'form-control', 'placeholder'=>'Cluster 1', 'id'=>'t2_c1']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 2 {!! Form::text('t2_c2', @$cluster2->tw2, ['class'=>'form-control', 'placeholder'=>'Cluster 2', 'id'=>'t2_c2']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 3 {!! Form::text('t2_c3', @$cluster3->tw2, ['class'=>'form-control', 'placeholder'=>'Cluster 3', 'id'=>'t2_c3']) !!}
                        </div>
                    </div>
                </fieldset>
                <fieldset class="form-group m-t-30">
                    <label>Triwulan III</label>
                    <div class="row">
                        <div class="col-md-2">
                            Cluster 1 {!! Form::text('t3_c1', @$cluster1->tw3, ['class'=>'form-control', 'placeholder'=>'Cluster 1', 'id'=>'t3_c1']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 2 {!! Form::text('t3_c2', @$cluster2->tw3, ['class'=>'form-control', 'placeholder'=>'Cluster 2', 'id'=>'t3_c2']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 3 {!! Form::text('t3_c3', @$cluster3->tw3, ['class'=>'form-control', 'placeholder'=>'Cluster 3', 'id'=>'t3_c3']) !!}
                        </div>
                    </div>
                </fieldset>
                <fieldset class="form-group m-t-30">
                    <label>Triwulan IV</label>
                    <div class="row">
                        <div class="col-md-2">
                            Cluster 1 {!! Form::text('t4_c1', @$cluster1->tw4, ['class'=>'form-control', 'placeholder'=>'Cluster 1', 'id'=>'t4_c1']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 2 {!! Form::text('t4_c2', @$cluster2->tw4, ['class'=>'form-control', 'placeholder'=>'Cluster 2', 'id'=>'t4_c2']) !!}
                        </div>
                        <div class="col-md-2">
                            Cluster 3 {!! Form::text('t4_c3', @$cluster3->tw4, ['class'=>'form-control', 'placeholder'=>'Cluster 3', 'id'=>'t4_c3']) !!}
                        </div>
                    </div>
                </fieldset>

                <div class="row">
                    <div class="col-md-12 pull-right">
                        <div class="button-list">
                            <button type="button" class="btn btn-warning btn-lg pull-right"
                                    onclick="window.location.href='{{url('master-data/target-coc')}}';"><i
                                        class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i>
                                Update
                            </button>

                        </div>

                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('javascript')

@stop