@if($pelanggaran_list->count()==0)
    <div class="row">
        <div class="col-md-12">
            Pelanggaran disiplin telah dipilih semua. Silakan klik tombol 'Clear History' di bawah ini, lalu pilih salah satu pelanggaran disiplin.
        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <button type="button" class="btn btn-success" id="btn_clear_pelanggaran" onclick="clearHistoryPelanggaran()"><i class="fa fa-trash"></i> Clear History</button>
        </div>
    </div>
@else
<table class="table m-t-10">
    <thead>
    <tr>
        <th>

        </th>
        <th>
            Pelanggaran Disiplin
        </th>
        <th>
            Klasifikasi
        </th>
    </tr>
    </thead>
    <tbody>
        @php
            $x=1;
        @endphp
    @foreach($pelanggaran_list as $data)
        <tr>
            <td width="20">
                <label class="c-input c-radio">
                    <input id="radio_pelanggaran_{{$data->id}}" name="pelanggaran" type="radio" value="{{$data->id}}" {{($x==1)?'checked':''}}>
                    <span class="c-indicator"></span>
                </label>
            </td>
            <td>
                {{$data->description}}
            </td>
            <td>
                {{$data->jenisPelanggaran->description}}
            </td>
        </tr>
        @php
            $x++;
        @endphp
    @endforeach
    </tbody>
</table>
@endif