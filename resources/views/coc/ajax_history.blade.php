<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success" id="btn_clear" onclick="clearHistory()"><i class="fa fa-trash"></i> Clear History</button>
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-6">
        <div class="form-group">
            <label for="org_history" class="form-control-label">Admin CoC</label>

                                            <span>
                                                {{Auth::user()->name}} ({{Auth::user()->nip}})
                                            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="org_history" class="form-control-label">Organisasi</label>

                                            <span id="org_history">
                                                {{$organisasi->stext}}
                                            </span>
        </div>
    </div>
</div>
<table class="table table-bordered">
    <?php $x=1;?>
    @foreach($pedoman_list as $pedoman)
        <tr>
            <th width="10" rowspan="3">
                {{$x++}}.
            </th>
            <th colspan="2">
                {{--{{$pedoman->pedoman_perilaku}}--}}
                {{$pedoman->judul}}<br>
                <small>{{$pedoman->deskripsi}}</small>
            </th>
        </tr>
        <tr>
            <th class="text-success">
                <i class="fa fa-thumbs-up"></i> Do
            </th>
            <th class="text-danger">
                <i class="fa fa-thumbs-down"></i> Don't
            </th>
        </tr>
        <tr>
            <td>
                <ul>
                    @foreach($pedoman->perilaku()->where('jenis','1')->orderBy('nomor_urut', 'asc')->get() as $perilaku)
                        @if(in_array($perilaku->id, $arr_perilaku))
                            <li class="text-muted"><del>{!!$perilaku->perilaku!!}</del></li>
                        @else
                            <li>{!!$perilaku->perilaku!!}</li>
                        @endif
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach($pedoman->perilaku()->where('jenis','2')->orderBy('nomor_urut', 'asc')->get() as $perilaku)
                        @if(in_array($perilaku->id, $arr_perilaku))
                            <li class="text-muted"><del>{!!$perilaku->perilaku!!}</del></li>
                        @else
                            <li>{!!$perilaku->perilaku!!}</li>
                        @endif
                    @endforeach
                </ul>
            </td>
        </tr>
    @endforeach
</table>