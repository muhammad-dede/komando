
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success" id="btn_clear_pelanggaran" onclick="clearHistoryPelanggaran()"><i class="fa fa-trash"></i> Clear History</button>
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
<table class="table m-t-10">
    <thead>
    <tr>
        <th>
            No
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
    <?php $x=1;?>
    @foreach($pelanggaran_history as $data)
        <tr>
            <td width="20">
                {{$x++}}
            </td>
            <td>
                {{$data->description}}
            </td>
            <td>
                {{$data->jenisPelanggaran->description}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>