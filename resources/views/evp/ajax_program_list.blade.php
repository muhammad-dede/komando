@foreach($evp_list as $evp)
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xs-12">

            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-xs-10">
                            <h4 class="card-title">{{$evp->nama_kegiatan}}</h4>
                            <h6 class="card-subtitle text-muted">
                                Periode: {{$evp->waktu_awal->format('d M Y')}}
                                - {{$evp->waktu_akhir->format('d M Y')}}, Lokasi: {{$evp->tempat}}</h6>
                        </div>
                        <div class="col-xs-2">
                            @if(Auth::user()->can('evp_create'))
                                <div class="btn-group pull-right">
                                    <button type="button"
                                            class="btn btn-secondary dropdown-toggle waves-effect"
                                            data-toggle="dropdown" aria-expanded="true"><i
                                                class="fa fa-ellipsis-v"></i>
                                        <span class="m-l-5"></span></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                           href="{{asset('assets/doc/evp/'.$evp->dokumen)}}">Edit</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <img class="img-fluid" src="{{asset('assets/images/evp/'.$evp->foto)}}"
                     alt="{{$evp->nama_kegiatan}}">

                <div class="card-block">
                    <p class="card-text">
                        {!! $evp->deskripsi !!}
                    </p>

                    <p class="card-text"><b>Kriteria:</b></p>
                    {!! $evp->kriteria_peserta !!}
                    <div class="btn-group">

                        <button type="button"
                                class="btn btn-primary dropdown-toggle waves-effect waves-light btn-block"
                                data-toggle="dropdown" aria-expanded="true"><i class="fa fa-download"></i>
                            Download<span class="m-l-5"></span></button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" target="_blank" href="{{asset('assets/doc/evp/'.$evp->dokumen)}}">Dokumen</a>
                            <a class="dropdown-item" target="_blank" href="{{asset('assets/doc/evp/'.$evp->data_diri)}}">Form
                                Data Diri</a>
                            <a class="dropdown-item" target="_blank"
                               href="{{asset('assets/doc/evp/'.$evp->surat_pernyataan)}}">Surat
                                Pernyataan</a>
                        </div>
                    </div>
                    <a href="{{url('evp/detail/'.$evp->id)}}" class="btn btn-info"><i
                                class="fa fa-info-circle"></i> More Info</a>
                    @if(Auth::user()->checkVolunteer($evp->id)==false)
                        <a href="{{url('evp/register/'.$evp->id)}}" class="btn btn-success"><i
                                    class="fa fa-edit"></i> Registrasi</a>
                    @endif
                </div>
            </div>

        </div>
        <!-- end col -->

    </div>
@endforeach