@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Code of Conduct</h4>
@stop

@section('content')
    <?php $check_in = true;?>

    @if (count($errors) > 0)
        <div class="form-group m-t-0 m-b-0">
            <div class="alert alert-dismissable alert-danger">
                <strong>Failed!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <br>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
                    @if($coc->materi!=null)
                    <li class="nav-item">
                        <a class="nav-link active" id="materi-tab" data-toggle="tab" href="#materi"
                           role="tab" aria-controls="materi"><h5>Materi CoC</h5></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="general-tab" data-toggle="tab" href="#general"
                           role="tab" aria-controls="general" aria-expanded="true"><h5>CoC Info</h5></a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general"
                           role="tab" aria-controls="general" aria-expanded="true"><h5>CoC Info</h5></a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" id="absensi-tab" data-toggle="tab" href="#absensi"
                           role="tab" aria-controls="absensi"><h5>Peserta</h5></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="gallery-tab" data-toggle="tab" href="#gallery"
                           role="tab" aria-controls="gallery"><h5>Gallery</h5></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="forum-tab" data-toggle="tab" href="#forum"
                           role="tab" aria-controls="forum"><h5>Forum</h5></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    @if($coc->materi!=null)
                    <div role="tabpanel" class="tab-pane fade in p-20" id="general" aria-labelledby="general-tab">
                    @else
                    <div role="tabpanel" class="tab-pane fade in active p-20" id="general" aria-labelledby="general-tab">
                    @endif

                        <div class="row">
                            <div class="col-md-6 col-xs-12">

                                <div class="form-group">
                                    <label for="tema" class="form-control-label">Tema</label>

                                    <div>
                                        {!! Form::text('tema_id_unit',@$coc->temaUnit->tema, ['class'=>'form-control', 'id'=>'tema', 'readonly']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="judul" class="form-control-label">Judul CoC</label>

                                    <div>
                                        {!! Form::text('judul',$coc->judul, ['class'=>'form-control', 'id'=>'judul', 'readonly']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="leader_id" class="form-control-label">CoC Leader</label>

                                    <div>
                                        @if($coc->realisasi!=null)
                                            {!! Form::text('leader_id',$coc->realisasi->leader->nip.' - '.$coc->realisasi->leader->name, ['class'=>'form-control', 'id'=>'leader_id', 'readonly']) !!}
                                        @else
                                            {!! Form::text('leader_id',$coc->leader->nip.' - '.$coc->leader->name, ['class'=>'form-control', 'id'=>'leader_id', 'readonly']) !!}
                                        @endif
                                        @if( Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('admin_ki') || Auth::user()->hasRole('admin_unit') )
                                        <a class="btn btn-primary btn-sm m-t-10" href="{{url('user-management/nip/'.@$coc->leader->nip)}}" target="blank">
                                            <i class="fa fa-external-link"></i> Profile Leader
                                        </a>
                                        @endif

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lokasi" class="form-control-label">Lokasi</label>

                                    <div>
                                        {!! Form::text('lokasi',$coc->lokasi, ['class'=>'form-control', 'id'=>'lokasi', 'placeholder'=>'Lokasi', 'readonly']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal" class="form-control-label">Tanggal</label>

                                    <div>
                                        @if($coc->realisasi!=null)
                                            {!! Form::text('tanggal',$coc->realisasi->realisasi->format('d-m-Y H:i'), ['class'=>'form-control', 'id'=>'tanggal', 'placeholder'=>'Lokasi', 'readonly']) !!}
                                        @else
                                            {!! Form::text('tanggal',$coc->tanggal_jam->format('d-m-Y H:i'), ['class'=>'form-control', 'id'=>'tanggal', 'placeholder'=>'Lokasi', 'readonly']) !!}
                                        @endif

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_code" class="form-control-label">Company Code</label>

                                    <div>
                                        {!! Form::text('company_code',@$coc->company_code.' - '.@$coc->companyCode->description, ['class'=>'form-control', 'id'=>'company_code', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="business_area" class="form-control-label">Business Area</label>

                                    <div>
                                        {!! Form::text('business_area',@$coc->business_area.' - '.@$coc->businessArea->description, ['class'=>'form-control', 'id'=>'business_area', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="organisasi" class="form-control-label">Organisasi</label>

                                    <div>
                                        {!! Form::text('organisasi',@$coc->orgeh.' - '.@$coc->organisasi->stext, ['class'=>'form-control', 'id'=>'organisasi', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="admin" class="form-control-label">Admin CoC</label>

                                    <div>
                                        {!! Form::text('admin',@$coc->admin->nip.' - '.@$coc->admin->name, ['class'=>'form-control', 'id'=>'admin', 'readonly']) !!}
                                        @if(Auth::user()->hasRole('root'))
                                        <button class="btn btn-primary btn-sm m-t-10" onclick="window.location.href='{{url('impersonation/'.@$coc->admin->id)}}'"><i class="fa fa-external-link"></i> Impersonate</button>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="organisasi" class="form-control-label">Status</label>

                                    <div>
                                        {!! Form::text('status',@$coc->status, ['class'=>'form-control', 'id'=>'status', 'readonly']) !!}
                                        @if(( Auth::user()->hasRole('root')  || Auth::user()->hasRole('admin_pusat')   || Auth::user()->hasRole('admin_ki') )&& @$coc->status=='OPEN')
                                            <button class="btn btn-success btn-sm m-t-10"
                                                    id="post" type="submit"
                                                    data-toggle="modal"
                                                    data-target="#completeModal" title="Complete"
                                                    onclick="javascript:ajaxComplete('{{$coc->id}}')">
                                                <i class="fa fa-flag-checkered"></i> Complete</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    @if($coc->materi!=null)
                    <div role="tabpanel" class="tab-pane fade in active" id="materi"
                         aria-labelledby="materi-tab">

{{-- DESKTOP VIEW --}}

                        <div class="row p-20 hidden-xs-down">
                            <div class="col-md-3 col-xs-12">
                                
                                <table>
                                    <tr>
                                        <td>
                                            <span class="display-4">{{$coc->tanggal_jam->format('d')}}</span>
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <span style="font-size: 18px">{{$coc->tanggal_jam->format('F')}}</span><br>
                                            <span style="font-size: 18px"
                                                  class="text-muted">{{$coc->tanggal_jam->format('l')}}</span>

                                        </td>
                                    </tr>
                                </table>

                                <hr>

                                <div style="margin-top:10px;"><label>Rating Materi</label></div>
                                <div class="hidden-xs-down">
                                    <table style="margin-top:5px; margin-bottom:30px;" border="0" width="100%">
                                        <tr>
                                            <td align="center" width="100">
                                                @php
                                                    $rate = $coc->materi->getArrJmlRate();
                                                    $avg = $rate['avg'];
                                                    $total_review = $rate['total'];
                                                    $str_total_review = $rate['str_total'];
                                                    $arr_rate = $rate['arr_rate'];
                                                    $arr_str_rate = $rate['arr_str_rate'];
                                                @endphp
                                                <h1>{{$avg}}</h1>

                                                <div id="rating-materi"></div>
                                                <div style="margin-top:5px;">{{$str_total_review}} Reviews</div>
                                            </td>
                                            <td style="padding-left: 10px;" valign="top">
                                                <table border="0" width="100%">
                                                    @for($x=5;$x>0;$x--)
                                                    <tr>
                                                        <td width="40" style="padding-left:5px;padding-right:5px;">{{$x}} <i class="fa fa-star"></td>
                                                        <td style="padding-top:10px;">
                                                            <progress class="progress progress-striped progress-success progress-sm" value="{{($total_review!=0)?($arr_rate[$x]/$total_review)*100:0}}" max="100">
                                                            </progress>
                                                        </td>
                                                        <td width="40" style="padding-left:5px;padding-right:5px;">{{$arr_str_rate[$x]}}</td>
                                                    </tr>
                                                    @endfor
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <hr>
                                
                                @if(@$coc->materi->attachments->count()>0)
                                    <fieldset class="form-group bg-">
                                        <label>Attachments</label>

                                        @foreach(@$coc->materi->attachments as $data)
                                            <p>
                                                <a href="{{url('coc/atch/'.$data->id)}}">
                                                    <i class="fa fa-paperclip"></i> {{$data->filename}}
                                                </a>
                                            </p>
                                        @endforeach
                                    </fieldset>
                                @endif

                                
                            </div>
                            <div class="col-md-9">
                                <div style="background: linear-gradient(to top, #b5e1e8 , #ffffff);">
                                    <div class="m-b-20">
                                        <img src="{{asset('assets/images/header_materi02.png')}}" alt="header_materi" width="100%">
                                    </div>
                                    
                                    <div style="margin: 40px;">
                                        @if(@$coc->materi->energize_day=='1')
                                            <h3 style="margin-bottom:20px;">Energize Day</h3>  
                                        @elseif(@$coc->materi->rubrik_transformasi=='1')
                                            <div>
                                                <img src="{{asset('assets/images/header_transformasi_pln2.png')}}" alt="header_transformasi" width="300" style="margin-top: -100px;margin-bottom: 20px;">
                                            </div>
                                            <h3 style="margin-bottom:20px;">Rubrik Transformasi</h3>
                                        @else
                                            
                                        @endif
                                        <h2 class="card-title" style="color: #0db0ec; ">{{@$coc->materi->judul}}</h2>

                                        <small class="text-muted">
                                            @if(@$coc->materi->energize_day=='1')
                                                
                                            @else
                                            Posted by {{@$coc->materi->penulis->cname}}
                                            - {{@$coc->materi->penulis->strukturPosisi->stext}}
                                            @endif
                                        </small>

                                        <div class="m-t-20">
                                            <div style="float: right; width: 200px; margin-right:30px;margin-top:30px;margin-left:60px;margin-bottom:30px;">
                                                <div>
                                                    @if(@$coc->materi->energize_day=='1')
                                                        <img src="{{asset('assets/images/PLN.png')}}" alt="Energize Day"  class="img-fluid img-thumbnail" width="200">
                                                    @elseif(@$coc->materi->penulis->user->foto!='')
                                                        <img src="{{asset('assets/images/users/foto/'.@$coc->materi->penulis->user->foto)}}" alt="user"  class="img-fluid img-thumbnail" width="200">
                                                    @else
                                                        <img src="{{asset('assets/images/user.jpg')}}" alt="user"  class="img-fluid img-thumbnail" width="200">
                                                    @endif
                                                </div>
                                                <div style="margin-top:10px; text-align:center">
                                                    <h5 style="text-align:center; color: #055058;">{{@$coc->materi->penulis->cname}}</h5>
                                                    <span style="text-align:center; color: #055058;font-weight:bold;">{{@$coc->materi->penulis->strukturPosisi->stext}}</span>
                                                </div>
                                            </div>

                                            {!! @$coc->materi->deskripsi !!}

                                        </div>
                                    </div>

                                    <div class="m-t-10">
                                        <img src="{{asset('assets/images/footer_materi02.png')}}" alt="footer_materi" width="100%">
                                    </div>
                                </div>
                                <hr>
                                @if($coc->checkAtendant(Auth::user()->id))
                                    @if(Auth::user()->hasReadMateriCoc(@$coc->materi->id, $coc->id) == false)
                                    {!! Form::open(['url'=>'coc/read-materi/'.$coc->id,'id'=>'f_read']) !!}
                                    {!! Form::hidden('materi_id', @$coc->materi->id) !!}
                                    <div class="checkbox checkbox-primary m-t-30">
                                        <input id="rate" name="rate" type="hidden" value="">
                                        <input id="checkbox_read" name="checkbox_read" type="checkbox" value="1">
                                        <label for="checkbox_read">
                                            Saya sudah membaca materi di atas
                                        </label>
                                    </div>

                                    <div>
                                        <button id="btn_submit" type="button" class="btn btn-success disabled" disabled><i
                                                    class="fa fa-send"></i> Submit</button>
                                    </div>

                                    {!! Form::close() !!}
                                    @else
                                        <div class="checkbox checkbox-primary m-t-30">
                                            <input id="checkbox_read" name="checkbox_read" type="checkbox" value="1" checked disabled>
                                            <label for="checkbox_read text-muted">
                                                Saya sudah membaca materi di atas
                                            </label>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

{{-- MOBILE VIEW --}}
                        <div class="row hidden-sm-up">
                            <div class="col-xs-12">
                                <div style="background: linear-gradient(to top, #b5e1e8 , #ffffff);">
                                    <div class="m-b-20">
                                        <img src="{{asset('assets/images/header_materi_mobile01.png')}}" alt="header_materi" width="100%">
                                    </div>

                                    <div style="margin: 15px;">
                                        
                                        @if(@$coc->materi->energize_day=='1')
                                            <h6 style="color: #0db0ec; margin-bottom:10px;">Energize Day</h6>  
                                        @elseif(@$coc->materi->rubrik_transformasi=='1')
                                            <div>
                                                <img src="{{asset('assets/images/header_transformasi_pln2.png')}}" alt="header_transformasi" width="200" style="margin-top: -10px;margin-bottom: 10px;">
                                            </div>
                                            <h6 style="color: #0db0ec; margin-bottom:10px;">Rubrik Transformasi</h6>
                                        @else
                                            
                                        @endif
                                        <h5 class="card-title" style="">{{@$coc->materi->judul}}</h5>

                                        <small class="text-muted">
                                            @if(@$coc->materi->energize_day=='1')
                                                
                                            @else
                                            Posted by {{@$coc->materi->penulis->cname}}
                                            - {{@$coc->materi->penulis->strukturPosisi->stext}}
                                            @endif
                                        </small>

                                        <div class="m-t-20">

                                            <div style="float: right; width: 100px; margin:15px;">
                                                <div>
                                                    @if(@$coc->materi->energize_day=='1')
                                                        <img src="{{asset('assets/images/PLN.png')}}" alt="Energize Day"  class="img-fluid img-thumbnail" width="120">
                                                    @elseif(@$coc->materi->penulis->user->foto!='')
                                                        <img src="{{asset('assets/images/users/foto-thumb/'.@$coc->materi->penulis->user->foto)}}" alt="user"  class="img-fluid img-thumbnail" width="120">
                                                    @else
                                                        <img src="{{asset('assets/images/user.jpg')}}" alt="user"  class="img-fluid img-thumbnail" width="120">
                                                    @endif
                                                </div>
                                                <div style="margin-top:10px; text-align:center">
                                                    <h6 style="text-align:center; color: #055058;">{{@$coc->materi->penulis->cname}}</h6>
                                                    <small style="text-align:center; color: #055058;font-weight:bold;">{{@$coc->materi->penulis->strukturPosisi->stext}}</small>
                                                </div>
                                            </div>

                                            {!! @$coc->materi->deskripsi !!}

                                        </div>
                                    </div>

                                    <div class="m-t-10">
                                        <img src="{{asset('assets/images/footer_materi_mobile01.png')}}" alt="footer_materi" width="100%">
                                    </div>
                                </div>
                                
                                
                                <hr>

                                @if($coc->checkAtendant(Auth::user()->id))
                                    @if(Auth::user()->hasReadMateriCoc(@$coc->materi->id, $coc->id) == false)
                                    {!! Form::open(['url'=>'coc/read-materi/'.$coc->id,'id'=>'f_read_mobile']) !!}
                                    {!! Form::hidden('materi_id', @$coc->materi->id) !!}
                                    <div class="checkbox checkbox-primary m-t-30 m-b-30">
                                        <input id="rate_mobile" name="rate" type="hidden" value="">
                                        <input id="checkbox_read_mobile" name="checkbox_read" type="checkbox" value="1">
                                        <label for="checkbox_read_mobile">
                                            Saya sudah membaca materi di atas
                                        </label>
                                    </div>

                                    <div>
                                        <button id="btn_submit_mobile" type="button" class="btn btn-success disabled" disabled><i
                                                    class="fa fa-send"></i> Submit</button>
                                    </div>

                                    {!! Form::close() !!}
                                    @else
                                        <div class="checkbox checkbox-primary m-t-30">
                                            <input id="checkbox_read" name="checkbox_read" type="checkbox" value="1" checked disabled>
                                            <label for="checkbox_read text-muted">
                                                Saya sudah membaca materi di atas
                                            </label>
                                        </div>
                                    @endif
                                @endif

                                <hr>

                                <div class="col-xs-12">
                                
                                    <div style="margin-top:10px;"><label>Rating Materi</label></div>
                                    
                                    <div class="hidden-sm-up">
                                        <table style="margin-top:5px; margin-bottom:30px;" border="0" width="100%">
                                            <tr>
                                                <td align="center">
                                                    @php
                                                        $rate = $coc->materi->getArrJmlRate();
                                                        $avg = $rate['avg'];
                                                        $total_review = $rate['total'];
                                                        $str_total_review = $rate['str_total'];
                                                        $arr_rate = $rate['arr_rate'];
                                                        $arr_str_rate = $rate['arr_str_rate'];
                                                    @endphp
                                                    <h1>{{$avg}}</h1>
    
                                                    <div id="rating-materi-sm"></div>
                                                    <div style="margin-top:5px;">{{$str_total_review}} Reviews</div>
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td style="padding-left: 10px;" valign="top">
                                                    <table border="0" width="100%">
                                                        @for($x=5;$x>0;$x--)
                                                        <tr>
                                                            <td width="40" style="padding-left:5px;padding-right:5px;">{{$x}} <i class="fa fa-star"></td>
                                                            <td style="padding-top:10px;">
                                                                <progress class="progress progress-striped progress-success progress-sm" value="{{($total_review!=0)?($arr_rate[$x]/$total_review)*100:0}}" max="100">
                                                                </progress>
                                                            </td>
                                                            <td width="40" style="padding-left:5px;padding-right:5px;">{{$arr_str_rate[$x]}}</td>
                                                        </tr>
                                                        @endfor
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    @if(@$coc->materi->attachments->count()>0)
                                        <fieldset class="form-group bg-">
                                            <label>Attachments</label>
    
                                            @foreach(@$coc->materi->attachments as $data)
                                                <p>
                                                    <a href="{{url('coc/atch/'.$data->id)}}">
                                                        <i class="fa fa-paperclip"></i> {{$data->filename}}
                                                    </a>
                                                </p>
                                            @endforeach
                                        </fieldset>
                                    @endif
    
                                </div>

                            </div>
                        </div>

                    </div>
                    @endif
                    <div role="tabpanel" class="tab-pane fade p-20" id="absensi"
                         aria-labelledby="absensi-tab">

                            <div class="row">
                                @if($coc->scope!='nasional')
                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                        <div class="card-box tilebox-two">
                                            <i class="icon-user-following pull-xs-right text-muted"></i>
                                            <h6 class="text-muted text-uppercase m-b-15">Pegawai Check-In</h6>
                                            <?php
                                            $jml_peserta = $coc->attendants->unique('user_id')->count();
                                            $jml_dispensasi = $coc->jml_peserta_dispensasi;
                                            ?>
                                            <h2 class="m-b-20">
                                                @if($jml_pegawai!=0)
                                                <span data-plugin="counterup">{{$jml_peserta}}/{{$jml_pegawai-$jml_dispensasi}} <small>({{number_format(($jml_peserta/($jml_pegawai-$jml_dispensasi))*100, 2)}}%)</small></span>
                                                @else
                                                    <span data-plugin="counterup">0</span>
                                                @endif
                                            </h2>
                                            @if($jml_pegawai!=0)
                                            <progress class="progress progress-striped progress-xs progress-info m-b-0" value="{{($jml_peserta/($jml_pegawai-$jml_dispensasi))*100}}" max="100"></progress>
                                            @else
                                                <progress class="progress progress-striped progress-xs progress-info m-b-0" value="0" max="100"></progress>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                        <div class="card-box tilebox-two">
                                            <i class="icon-user-unfollow pull-xs-right text-muted"></i>
                                            <h6 class="text-muted text-uppercase m-b-15">Pegawai Sakit/Ijin/Cuti</h6>
                                            <h2 class="m-b-20">
                                                @if($jml_pegawai!=0)
                                                    <span data-plugin="counterup">{{$jml_dispensasi}}/{{$jml_pegawai}} <small>({{number_format(($jml_dispensasi/$jml_pegawai)*100, 2)}}%)</small></span>
                                                @else
                                                    <span data-plugin="counterup">0</span>
                                                @endif
                                            </h2>
                                            @if($jml_pegawai!=0)
                                                <progress class="progress progress-striped progress-xs progress-info m-b-0" value="{{($jml_dispensasi/$jml_pegawai)*100}}" max="100"></progress>
                                            @else
                                                <progress class="progress progress-striped progress-xs progress-info m-b-0" value="0" max="100"></progress>
                                            @endif
                                        </div>
                                    </div>
<?php /*
                                    @if($coc->materi!=null)
                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                        <div class="card-box tilebox-two">
                                            <i class="icon-eyeglass pull-xs-right text-muted"></i>
                                            <h6 class="text-muted text-uppercase m-b-15">Pegawai Baca Materi</h6>
                                            <?php
                                            $jml_baca = $coc->materi->getReaderFromCoC($coc->id)->unique('nip')->count();
                                            ?>
                                            <h2 class="m-b-20">
                                                @if($jml_pegawai!=0)
                                                    <span data-plugin="counterup">{{$jml_baca}}/{{$jml_pegawai}} <small>({{number_format(($jml_baca/$jml_pegawai)*100, 2)}}%)</small></span>
                                                @else
                                                    <span data-plugin="counterup">0</span>
                                                @endif
                                            </h2>
                                            @if($jml_pegawai!=0)
                                                <progress class="progress progress-striped progress-xs progress-info m-b-0" value="{{($jml_baca/$jml_pegawai)*100}}" max="100"></progress>
                                            @else
                                                <progress class="progress progress-striped progress-xs progress-info m-b-0" value="0" max="100"></progress>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
*/ ?>
                                @endif

                            </div>

                            @if(Auth::user()->can('input_coc_local'))
                            <div class="col-md-12 align-right" align="right">
                                <a href="{{url('coc/event/'.$coc->id.'/export')}}" id="export_peserta" 
                                    class="btn btn-success waves-effect waves-light align-right">
                                        <i class="fa fa-file-excel-o"></i> &nbsp;Export Peserta
                                </a>
                            </div>
                            @endif

                            <div class="col-md-12 table-responsive m-t-10">
                                {{-- <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="center"></th>
                                        <th class="center hidden-xs" style="text-align: center">NIP</th>
                                        <th class="center" style="text-align: center">Name</th>
                                        <th class="center hidden-xs" style="text-align: center">Business Area</th>
                                        <th class="center hidden-xs" style="text-align: center">Bidang</th>
                                        <th class="center hidden-xs" style="text-align: center">Jabatan</th>
                                        <th class="center hidden-xs" style="text-align: center">Status</th>
                                        <th class="center hidden-xs" style="text-align: center">Check-In</th>
                                        @if($coc->materi!=null)
                                        <th class="center hidden-xs" style="text-align: center">Baca Materi</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coc->attendants as $user)
                                        <tr>
                                            <td style="text-align: center">
                                                @if(Auth::user()->hasRole('root'))
                                                <a href = "{{url('user-management/user/'.@$user->user->id)}}">
                                                @endif
                                                @if(@$user->user->foto!='')
                                                    <img src="{{asset('assets/images/users/foto-thumb/'.@$user->user->foto)}}" alt="user" class="img-fluid img-thumbnail" width="64">
                                                @else
                                                    <img src="{{asset('assets/images/user.jpg')}}" alt="user" class="img-fluid img-thumbnail" width="64">
                                                @endif
                                                @if(Auth::user()->hasRole('root'))
                                                </a>
                                                @endif
                                            </td>
                                            <td class="hidden-xs">{{@$user->user->nip}}</td>
                                            <td>{{@$user->user->name}}</td>
                                            <td class="hidden-xs">{{@$user->user->business_area}}
                                                - {{@$user->user->businessArea->description}}</td>
                                            <td class="hidden-xs">{{@$user->user->bidang}}</td>
                                            <td class="hidden-xs">{{@$user->user->jabatan}}</td>
                                            <td class="hidden-xs">
                                                @if(@$user->status_checkin_id=='1')
                                                    <span class="label label-success"><b>{{@$user->statusCheckin->status}}</b></span>
                                                @else
                                                    <span class="label label-danger"><b>{{@$user->statusCheckin->status}}</b></span>
                                                @endif
                                            </td>
                                            <td class="hidden-xs">
                                                {{@$user->check_in->format('Y-m-d H:i')}}<br>
                                                <small class="text-muted">{{@$user->check_in->diffForHumans()}}</small>
                                            </td>
                                            @if($coc->materi!=null)
                                            <td class="hidden-xs">
                                                {{(@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)!=null)?@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)->created_at->format('Y-m-d H:i'):''}}<br>
                                                <small class="text-muted">{{(@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)!=null)?@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)->created_at->diffForHumans():''}}</small>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @foreach($absence_list as $user)
                                        <tr>
                                            <td style="text-align: center">
                                                @if(Auth::user()->hasRole('root'))
                                                    <a href = "{{url('user-management/user/'.@$user->id)}}">
                                                        @endif
                                                        @if(@$user->foto!='')
                                                            <img src="{{asset('assets/images/users/foto-thumb/'.@$user->foto)}}" alt="user" class="img-fluid img-thumbnail" width="64">
                                                        @else
                                                            <img src="{{asset('assets/images/user.jpg')}}" alt="user" class="img-fluid img-thumbnail" width="64">
                                                        @endif
                                                        @if(Auth::user()->hasRole('root'))
                                                    </a>
                                                @endif

                                            </td>
                                            <td class="hidden-xs">{{@$user->strukturJabatan->nip}}</td>
                                            <td>{{@$user->name}}</td>
                                            <td class="hidden-xs">{{@$user->business_area}}
                                                - {{@$user->businessArea->description}}</td>
                                            <td class="hidden-xs">{{@$user->strukturPosisi()->stxt2}}</td>
                                            <td class="hidden-xs">{{@$user->strukturPosisi()->stext}}</td>
                                            <td class="hidden-xs">
                                                <span class="label label-danger"><b>Belum Check-in</b></span>
                                            </td>
                                            <td class="hidden-xs">
                                            </td>
                                            @if($coc->materi!=null)
                                                <td class="hidden-xs">
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table> --}}

                                <table id="datatable_user" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th class="center"></th>
                                            <th class="center">NIP</th>
                                            <th class="center ">Name</th>
                                            <th class="center hidden-xs">Business Area</th>
                                            <th class="center hidden-xs">Bidang</th>
                                            <th class="center hidden-xs">Jabatan</th>
                                            <th class="center ">Status</th>
                                            <th class="center hidden-xs">Check-In</th>
                                            @if($coc->materi!=null)
                                            <th class="center hidden-xs">Baca Materi</th>
                                            @endif
                                        </tr>
                                    </thead>
            
                                    <tbody></tbody>
                                </table>

                            </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade p-20" id="gallery"
                         aria-labelledby="gallery-tab">
                        @if(Auth::user()->can('upload_foto'))
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="javascript:" id="post"
                                       @if($coc->gallery()->where('status','ACTV')->count()==3)
                                       class="btn btn-primary w-lg waves-effect waves-light disabled" disabled
                                       @else
                                       class="btn btn-primary w-lg waves-effect waves-light"
                                       @endif
                                       data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-image"></i> &nbsp;Upload Foto</a>
                                </div>
                            </div>
                        @endif
                        @if($coc->gallery->count()==0)
                            <div align="center">
                                <h1 class="fa fa-image img-responsive" style="font-size: 250px;color: #dadada;"></h1>
                            </div>
                        @else
                            <div class="row m-t-20 m-b-20">
                                <div class="col-xs-12">
                                    <div class="card-deck-wrapper">
                                        <div class="card-deck">
                                            @foreach($coc->gallery()->where('status','ACTV')->get() as $foto)
                                                <div class="card">
                                                    <img class="card-img-top img-fluid"
                                                         src="{{url('coc/foto/'.$foto->id)}}" alt="{{$foto->judul}}">

                                                    <div class="card-block">
                                                        <h4 class="card-title">{{$foto->judul}}</h4>

                                                        <p class="card-text">
                                                            {{$foto->deskripsi}}
                                                        </p>

                                                        <p class="card-text">
                                                            <small class="text-muted">Last
                                                                updated {{$foto->created_at->diffForHumans()}}</small>
                                                        </p>
                                                        @if(Auth::user()->id == $coc->admin_id || Auth::user()->hasRole('root'))
                                                        <div>
                                                            <a href="javascript:" type="submit"
                                                               class="btn btn-danger btn-xs waves-effect waves-light"
                                                               title="Delete Foto"
                                                               onclick="javascript:deleteFoto('{{$foto->id}}')"
                                                                    >
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div role="tabpanel" class="tab-pane fade p-20" id="forum"
                         aria-labelledby="forum-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="javascript:" id="post" type="submit"
                                   class="btn btn-primary w-lg waves-effect waves-light" data-toggle="modal"
                                   data-target="#postingModal"><i class="fa fa-commenting"></i> &nbsp;New Posting</a>
                            </div>
                        </div>
                        @foreach($coc->comments()->orderBy('id', 'desc')->get() as $comment)
                            <div class="row m-t-20">
                                <div class="card card-block col-md-12">
                                    <div class="row">
                                        <div class="col-md-1 col-xs-4">
                                            <img
                                                    src="{{(@$comment->user->foto!='') ? url('user/foto-thumb/'.$comment->user->id) : asset('assets/images/user.jpg')}}"
                                                    alt="User"
                                                    class="img-thumbnail">
                                        </div>
                                        <div class="col-md-11 col-xs-8">
                                            <p class="card-text">
                                                {!! $comment->comment !!}
                                            </p>

                                            <p class="card-text">
                                                <small class="text-muted">Posted by
                                                    {{$comment->user->name}}
                                                    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                                    {{$comment->created_at->diffForHumans()}}</small>
                                            </p>
                                            <hr>
                                            <div class="button-list">
                                                <button class="btn waves-effect waves-light btn-pink-outline btn-sm"><i
                                                            class="fa fa-thumbs-up"></i></button>
                                                <button class="btn waves-effect waves-light btn-success-outline btn-sm">
                                                    <i
                                                            class="fa fa-comment"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        @if($coc->comments->count()==0)
                            <div align="center">
                                <h1 class="fa fa-comments-o" style="font-size: 250px;color: #dadada;"></h1>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-md-6">
                        <a href="{{url('coc')}}" type="button" class="btn btn-primary btn-lg pull-left"><i
                                    class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- sample modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        {!! Form::open(['url'=>'coc/upload-foto/'.$coc->id, 'files'=>true]) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Foto Kegiatan</h4>
                </div>
                <div class="modal-body">

                    <div class="m-l-20">
                        <h6 class="m-b-20 text-muted">File Foto (*.jpg, *.jpeg, *.png)</h6>
                        {!! Form::file('foto', ['class'=>'form-control', 'id'=>'foto']) !!}
                        <small class="text-muted">Maksimal 3 foto. Ukuran file maksimal 3MB.</small>
                    </div>

                    <fieldset class="form-group m-t-10">
                        {!! Form::text('judul',null, ['class'=>'form-control', 'placeholder'=>'Judul']) !!}
                    </fieldset>

                    <fieldset class="form-group">
                        {!! Form::textarea('deskripsi',null, ['class'=>'form-control', 'placeholder'=>'Deskripsi']) !!}
                    </fieldset>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Save
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

    <!-- sample modal content -->
    <div id="postingModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="postingModalLabel"
         aria-hidden="true">
        {!! Form::open(['url'=>'coc/forum/'.$coc->id]) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">New Posting</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::textarea('comment', null, ['class'=>'form-control', 'id'=>'comment',
                                                                    'placeholder'=>'Diskusikan CoC di sini', 'rows'=>'5']) !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-send"></i>
                        Post
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

    <!-- sample modal content -->
    <div id="completeModal" class="modal fade" role="dialog" aria-labelledby="completeModalLabel"
         aria-hidden="true">
        {!! Form::open(['url'=>'coc/complete', 'id'=>'form_complete']) !!}
        {!! Form::hidden('coc_id', '', ['id'=>'coc_id']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Realisasi CoC</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pemateri_id" class="form-control-label">Judul CoC</label>

                        <div>
                            <input type="text" class="form-control" placeholder="Judul CoC" id="judul_coc" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pemateri_id" class="form-control-label">Pembawa Materi / CoC Leader <span class="text-danger">*</span></label>

                        <div>
                            <select class="itemName form-control" name="nip_leader"
                                    style="width: 100% !important; padding: 0; z-index:10000;"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coc_date" class="form-control-label">Realisasi <span class="text-danger">*</span></label>

                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="complete_date"
                                   name="tanggal_coc" value="{{date('d-m-Y')}}">
                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jam_coc" class="form-control-label">Jam <span class="text-danger">*</span></label>
                        <div class="input-group clockpicker" data-placement="top" data-align="top"
                             data-autoclose="true">
                            <input type="text" class="form-control form-control-danger" placeholder="Jam" id="jam_coc" name="jam_coc"
                                   value="{{old('jam')}}">
                            <span class="input-group-addon"> <span class="zmdi zmdi-time"></span> </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jam_coc" class="form-control-label">Jumlah Dispensasi Peserta CoC (Sakit/Ijin/Cuti) <span class="text-danger">*</span></label>
                        <div>
                            <input type="text" class="form-control" placeholder="Jumlah" id="jml_peserta_dispensasi" name="jml_peserta_dispensasi" value="0">
                            <small class="text-muted">Jumlah ini akan mengurangi jumlah pegawai untuk perhitungan persentase kehadiran. <br>Persentase Kehadiran CoC = Jumlah Check-in / (Jumlah Pegawai - Jumlah Dispensasi)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
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
    <!-- Raty-fa -->
    <script src="{{asset('assets/plugins/raty-fa/jquery.raty-fa.js')}}"></script>
    <!-- Init -->
    <script src="{{asset('assets/pages/jquery.rating.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#tema_id").select2();
            $("#jenjang_jabatan").select2();
            $("#busa").select2();
            $('#datatable').DataTable();
            $('#datatable_user').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: {!! json_encode($datatable->getColumns()) !!},
                order: [[1, "asc"]]
            });

            @if($coc->materi!=null)
            $('#rating-materi').raty({
                readOnly: true,
                half: true,
                score: {{$avg}},
                starHalf: 'fa fa-star-half-empty text-warning',
                starOff: 'fa fa-star-o text-muted',
                starOn: 'fa fa-star text-warning',
                //hints: ['Tidak Bagus', 'Kurang Bagus', 'Rata-rata', 'Bagus', 'Sangat Bagus']
            });

            $('#rating-materi-sm').raty({
                readOnly: true,
                half: true,
                score: {{$avg}},
                starHalf: 'fa fa-star-half-empty text-warning',
                starOff: 'fa fa-star-o text-muted',
                starOn: 'fa fa-star text-warning',
                //hints: ['Tidak Bagus', 'Kurang Bagus', 'Rata-rata', 'Bagus', 'Sangat Bagus']
            });
            @endif

            $('#checkbox_read').click(function(){
                if ($('#checkbox_read').is(':checked')){
                    $('#btn_submit').removeClass('disabled');
                    $('#btn_submit').prop("disabled", false);
                    $('#btn_submit').click(function(){
                        swal({
                            type: "info",
                            allowOutsideClick: false,
                            title: '<strong>Penilaian Materi</strong>',
                            html:
                                '<div style="margin-bottom:40px;">' +
                                '<div style="margin-bottom:20px;">Silakan beri nilai materi ini </div>'+
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateri(\'1\')">1 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateri(\'2\')">2 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateri(\'3\')">3 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateri(\'4\')">4 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateri(\'5\')">5 <i class="fa fa-star"></i></a> ' +
                                '</div>',
                            focusConfirm: false,
                            showConfirmButton: false,
                          });
                    });
                }
                else{
                    $('#btn_submit').addClass('disabled');
                    $('#btn_submit').prop("disabled", true);
                }
            });

            $('#checkbox_read_mobile').click(function(){
                if ($('#checkbox_read_mobile').is(':checked')){
                    $('#btn_submit_mobile').removeClass('disabled');
                    $('#btn_submit_mobile').prop("disabled", false);
                    $('#btn_submit_mobile').click(function(){
                        swal({
                            type: "info",
                            allowOutsideClick: false,
                            title: '<strong>Penilaian Materi</strong>',
                            html:
                                '<div style="margin-bottom:40px;">' +
                                '<div style="margin-bottom:20px;">Silakan beri nilai materi ini </div>'+
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateriMobile(\'1\')">1 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateriMobile(\'2\')">2 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateriMobile(\'3\')">3 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateriMobile(\'4\')">4 <i class="fa fa-star"></i></a> ' +
                                '<a href="#" class="btn btn-primary" onClick="submitRatingMateriMobile(\'5\')">5 <i class="fa fa-star"></i></a> ' +
                                '</div>',
                            focusConfirm: false,
                            showConfirmButton: false,
                          });
                    });
                }
                else{
                    $('#btn_submit_mobile').addClass('disabled');
                    $('#btn_submit_mobile').prop("disabled", true);
                }
            });

            jQuery('#coc_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            $('.clockpicker').clockpicker({
                donetext: 'Done'
            });

            tinymce.init({
                selector: '#comment', height: 150,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                // content_css: [
                //     '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                //     '//www.tinymce.com/css/codepen.min.css']
            });

            $('.itemName').select2({
                placeholder: 'Select Pegawai',
                ajax: {
                    url: '/coc/ajax-leader',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nip+' - '+item.name,
                                    id: item.nip
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

        });

        function submitRatingMateri(rate){
            $('#rate').val(rate);
            $('#f_read').submit();
        }

        function submitRatingMateriMobile(rate){
            $('#rate_mobile').val(rate);
            $('#f_read_mobile').submit();
        }

        //Warning Message
        function deleteFoto(foto_id) {
//            var coc = coc_id;
            {{--swal({--}}
                {{--title: "Are you sure?",--}}
                {{--text: "Foto akan dihapus dan tidak dapat digunakan kembali.",--}}
                {{--type: "warning",--}}
                {{--showCancelButton: true,--}}
                {{--cancelButtonClass: 'btn-secondary waves-effect',--}}
                {{--confirmButtonClass: 'btn-warning',--}}
                {{--confirmButtonText: "Yes, delete it!",--}}
                {{--closeOnConfirm: false--}}
            {{--}, function () {--}}
{{--//                alert('id: '+coc);--}}
                {{--window.location.href = '{{url('/coc/delete-foto')}}/'+foto_id;--}}
{{--//                swal("Canceled!", "Jadwal CoC berhasil dibatalkan.", "success");--}}
            {{--});--}}

            swal({
                title: "Are you sure?",
                text: "Foto akan dihapus dan tidak dapat digunakan kembali.",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }).then(result => {
                    if (result.value) {
                    // handle Confirm button click
                    // result.value will contain `true` or the input value
                    window.location.href = '{{url('/coc/delete-foto')}}/'+foto_id;
                }
            });
        }

        function ajaxComplete(coc_id){
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-coc/')}}'+'/'+coc_id,
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
//                    console.log(data.judul);
                    $('#coc_id').val(coc_id);
//                    $('#judul_coc').val(coc_id+' - '+data.judul);
                    $('#judul_coc').val(data.judul);
                    var arr = data.tanggal_jam.split(' ');
                    var tgl = arr[0].split('-');
                    var jam = arr[1].split(':');
                    $('#complete_date').val(tgl[2]+'-'+tgl[1]+'-'+tgl[0]);
                    $('#jam_coc').val(jam[0]+':'+jam[1]);
                    $('#jam_coc').val(jam[0]+':'+jam[1]);
//                    $("#do_list").html(data);
                }
            });
        }

    </script>

@stop
