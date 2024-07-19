@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Dashboard EVP</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-2" align="center">
                        @if(Auth::user()->foto!='')
                            <img class="img-fluid img-thumbnail" src="{{url('user/foto')}}" alt="user" width="150">
                        @else
                            <img class="img-fluid img-thumbnail" src="{{asset('assets/images/user.jpg')}}" alt="user"
                                 width="150">
                        @endif
                    </div>
                    <div class="col-md-4">
                        <h3>{{Auth::user()->name}}</h3>

                        <div class="m-t-10">{{Auth::user()->nip}}</div>
                        <div class="m-t-10">{{@Auth::user()->strukturPosisi()->stext}}</div>
                        <div class="m-t-10">{{@Auth::user()->strukturPosisi()->stxt2}}</div>
                        <div class="m-t-10">{{@Auth::user()->business_area.' - '.@Auth::user()->businessArea->description}}</div>
                        <div class="m-t-10">{{@Auth::user()->company_code.' - '.@Auth::user()->companyCode->description}}</div>

                    </div>

                    <div class="col-md-6">
                        @if(!Auth::user()->isGM())
                            <div class="card card-block">
                                <h5 class="card-title">Approver:</h5>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-3 form-control-label">Atasan Langsung *</label>

                                    <div class="col-sm-9">
                                        {{--@if(@$atasan->user->foto!='')--}}
                                        {{--<img src="{{asset('assets/images/users/foto-thumb/'.$atasan->user->foto)}}" alt="user"  class="img-fluid img-thumbnail" width="64">--}}
                                        {{--@else--}}
                                        {{--<img src="{{asset('assets/images/user.jpg')}}" alt="user"  class="img-fluid img-thumbnail" width="64">--}}
                                        {{--@endif--}}
                                        {{$atasan->cname}} ({{$atasan->nip}}) <br>{{$atasan->strukturPosisi->stext}}
                                        {{--<input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">--}}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-3 form-control-label">General Manager *</label>

                                    <div class="col-sm-9">
                                        {{$gm->cname}} ({{$gm->nip}})
                                        {{--<input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">--}}
                                    </div>
                                </div>
                                <small class="text-muted">* Jika ada kesalahan atasan langsung atau General Manager,
                                    harap
                                    menghubungi Administrator.
                                </small>
                            </div>
                        @endif

                        {{--<h5>Approval</h5>--}}
                        {{----}}

                        {{--<div>--}}
                        {{--<small class="text-muted"></small>--}}
                        {{--</div>--}}

                        {{--<div class="form-group row">--}}
                        {{--<label for="jml_hadir"--}}
                        {{--class="col-sm-5 form-control-label">Jumlah EVP yang diikuti</label>--}}
                        {{--<div class="col-sm-5">--}}
                        {{--{{Auth::user()->getJumlahEVP()}}--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group row">--}}
                        {{--<label for="jml_hadir"--}}
                        {{--class="col-sm-5 form-control-label">Jumlah CoC yang diikuti</label>--}}
                        {{--<div class="col-sm-5">--}}
                        {{--{{Auth::user()->getJumlahCoC()}}--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group row">--}}
                        {{--<label for="jml_hadir"--}}
                        {{--class="col-sm-5 form-control-label">Jumlah materi CoC yang dibaca</label>--}}
                        {{--<div class="col-sm-5">--}}
                        {{--{{Auth::user()->getJumlahMateriDibaca()}}--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group row">--}}
                        {{--<label for="jml_hadir"--}}
                        {{--class="col-sm-5 form-control-label">Jumlah menjadi leader CoC</label>--}}
                        {{--<div class="col-sm-5">--}}
                        {{--{{Auth::user()->getJumlahLeaderCoC()}}--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                        <h4 class="header-title m-t-0">Daftar Kegiatan</h4>
                        {{--<p class="text-muted font-13 m-b-10">--}}
                        {{--Daftar kegiatan dan status terakhir--}}
                        {{--</p>--}}

                        <div class="">
                            <table class="table">
                                <thead>
                                <tr>
                                    {{--<th>#</th>--}}
                                    <th>Kegiatan</th>
                                    <th class="hidden-md-down">Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $x = 1;
                                ?>
                                @foreach(Auth::user()->volunteer()->orderBy('id', 'desc')->get() as $volunteer)
                                    <tr>
                                        {{--<th scope="row">{{$x++}}</th>--}}
                                        <td>
                                            <a href="{{url('evp/detail/'.@$volunteer->evp->id)}}">{{@$volunteer->evp->nama_kegiatan}}</a>
                                            <br>
                                            <small>
                                                <i class="fa fa-calendar"></i> {{@$volunteer->evp->waktu_awal->format('d M Y')}}
                                                - {{@$volunteer->evp->waktu_akhir->format('d M Y')}}</small>
                                            <br>
                                            <small>
                                                <i class="fa fa-globe"></i> {{@$volunteer->evp->jenisEVP->description}}
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <i class="fa fa-map-marker"></i> {{@$volunteer->evp->tempat}}
                                            </small>

                                        </td>
                                        <td class="hidden-md-down">
                                            <div class="row" style="">
                                                <div class="col-md-1" align="center">
                                                    <button class="btn waves-effect {{(@$volunteer->registrasi!=null)?'btn-success':'btn-secondary'}}">
                                                        <i class="fa fa-edit"></i></button>
                                                    <div class="m-t-10">Registrasi</div>
                                                    {{--<small>2018-09-01 19:35:52</small>--}}
                                                </div>
                                                <div class="col-md-1" align="center">
                                                    <hr style="border: 2px dashed #BBBBBB; ">
                                                </div>
                                                @if($volunteer->status=='REJ-AT' || $volunteer->status=='REJ-ADM' || $volunteer->status=='REJ-GM' || $volunteer->status=='REJ-PST')
                                                    <div class="col-md-1" align="center">
                                                        <button class="btn waves-effect btn-danger" title="Rejected">
                                                            <i class="fa fa-times"></i></button>
                                                        <div class="m-t-10">Approved</div>
                                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                                    </div>
                                                    <div class="col-md-1" align="center">
                                                        <hr style="border: 2px dashed #BBBBBB; ">
                                                    </div>
                                                    @if($volunteer->evp->briefing=='1')
                                                        <div class="col-md-1" align="center">
                                                            <button class="btn waves-effect {{(@$volunteer->briefing!=null)?'btn-success':'btn-secondary'}}">
                                                                <i class="fa fa-institution"></i></button>
                                                            <div class="m-t-10">Briefing</div>
                                                            {{--<small>2018-09-01 19:35:52</small>--}}
                                                        </div>
                                                        <div class="col-md-1" align="center">
                                                            <hr style="border: 2px dashed #BBBBBB; ">
                                                        </div>
                                                    @endif
                                                    <div class="col-md-1" align="center">
                                                        <button class="btn waves-effect {{(@$volunteer->aktif!=null)?'btn-success':'btn-secondary'}}">
                                                            <i class="fa fa-male"></i></button>
                                                        <div class="m-t-10">Aktif</div>
                                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                                    </div>
                                                    <div class="col-md-1" align="center">
                                                        <hr style="border: 2px dashed #BBBBBB; ">
                                                    </div>
                                                    <div class="col-md-1" align="center">
                                                        <button class="btn waves-effect {{(@$volunteer->finish!=null)?'btn-success':'btn-secondary'}}">
                                                            <i class="fa fa-flag-checkered"></i></button>
                                                        <div class="m-t-10">Selesai</div>
                                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                                    </div>
                                                @else
                                                    <div class="col-md-1" align="center">
                                                        <button class="btn waves-effect {{((@$volunteer->approval_pusat!=null && @$volunteer->evp->jenis_evp_id=='1') || (@$volunteer->approval_admin!=null && @$volunteer->evp->jenis_evp_id=='2'))?'btn-success':'btn-secondary'}}">
                                                            <i class="fa fa-check"></i></button>
                                                        <div class="m-t-10">Approved</div>
                                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                                    </div>
                                                    <div class="col-md-1" align="center">
                                                        <hr style="border: 2px dashed #BBBBBB; ">
                                                    </div>
                                                    @if($volunteer->evp->briefing=='1')
                                                        <div class="col-md-1" align="center">
                                                            <button class="btn waves-effect {{(@$volunteer->briefing!=null)?'btn-success':'btn-secondary'}}">
                                                                <i class="fa fa-institution"></i></button>
                                                            <div class="m-t-10">Briefing</div>
                                                            {{--<small>2018-09-01 19:35:52</small>--}}
                                                        </div>
                                                        <div class="col-md-1" align="center">
                                                            <hr style="border: 2px dashed #BBBBBB; ">
                                                        </div>
                                                    @endif
                                                    <div class="col-md-1" align="center">
                                                        <button class="btn waves-effect {{(@$volunteer->aktif!=null)?'btn-success':'btn-secondary'}}">
                                                            <i class="fa fa-male"></i></button>
                                                        <div class="m-t-10">Aktif</div>
                                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                                    </div>
                                                    <div class="col-md-1" align="center">
                                                        <hr style="border: 2px dashed #BBBBBB; ">
                                                    </div>
                                                    <div class="col-md-1" align="center">
                                                        <button class="btn waves-effect {{(@$volunteer->finish!=null)?'btn-success':'btn-secondary'}}">
                                                            <i class="fa fa-flag-checkered"></i></button>
                                                        <div class="m-t-10">Selesai</div>
                                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                                    </div>
                                                @endif

                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{url('evp/volunteer/'.$volunteer->id)}}"
                                               class="btn btn-info btn-xs waves-effect waves-light"
                                               title="More Detail">
                                                <i class="fa fa-info-circle" style="font-size: 18px;"></i>
                                            </a>
                                            @if($volunteer->status=='REG')
                                                <a href="{{url('evp/volunteer/'.$volunteer->id.'/edit')}}"
                                                   class="btn btn-warning btn-xs waves-effect waves-light"
                                                   title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            @endif

                                            @if($volunteer->status=='ACTV' || $volunteer->status=='COMP')
                                                <a href="{{url('evp/log/list/'.$volunteer->id)}}"
                                                   class="btn btn-pink btn-xs waves-effect waves-light"
                                                   title="Activity Log">
                                                    <i class="fa fa-clock-o" style="font-size: 18px;"></i>
                                                </a>
                                            @endif

                                            @if($volunteer->status=='ACTV' && Carbon\Carbon::now()->format('Ymd')>=@$volunteer->evp->waktu_akhir->format('Ymd'))
                                                <a href="javascript:" data-toggle="modal" data-target="#testimoniModal"
                                                   class="btn btn-purple btn-xs waves-effect waves-light"
                                                   title="Testimoni"
                                                   onclick="javascript:$('#volunteer_id').val('{{$volunteer->id}}')">
                                                    <i class="fa fa-comment-o" style="font-size: 18px;"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- sample modal content -->
    <div id="testimoniModal" class="modal fade" role="dialog" aria-labelledby="testimoniModalLabel"
         aria-hidden="true">
        {!! Form::open(['url'=>'evp/testimoni/create']) !!}
        {!! Form::hidden('volunteer_id', null, ['id'=>'volunteer_id']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-comment-o"></i> Testimoni</h4>
                </div>
                <div class="modal-body">
                    <div class="m-l-20">
                        <div class="form-group">
                            <label>Tuliskan kesan-kesan selama menjadi relawan <span class="text-danger">*</span></label>

                            <div>
                                {!! Form::textArea('testimoni', null, ['class'=>'form-control select2', 'id'=>'testimoni', 'width'=>'100%', 'style'=>'width: 100% !important; padding: 0; z-index:10000;']) !!}
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-send"></i>
                        Submit
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        {!! Form::close() !!}
    </div><!-- /.modal -->
@stop

@section('javascript')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: '#testimoni', height: 250,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });

        });


    </script>
@stop