<div class="card-box">
    <div class="row">
        <div class="col-lg-12">
            <table>
                <tbody>
                <tr>
                    <td>
                        <span class="display-1">{{Carbon\Carbon::now()->format('d')}}</span>
                    </td>
                    <td style="padding-left: 10px;">
                        <span style="font-size: 24px">{{Carbon\Carbon::now()->format('M')}}</span><br>
                        <span style="font-size: 24px" class="text-muted">{{Carbon\Carbon::now()->format('D')}}</span>

                    </td>
                </tr>
                </tbody>
            </table>
            <div class="row">
                @inject('setting', 'App\Services\SettingService')
                <div class="col-md-7 col-xs-12">
                    <a href="{{ url($setting->getSetting()['manual-book-dashboard-bawahan']) }}" target="_blank" class="btn bg-success m-b-10 width-full">
                        Manual Book
                    </a>
                </div>
                <div class="col-md-5 col-xs-12">
                    <a href="{{ url($setting->getSetting()['faq']) }}" target="_blank" class="btn bg-blue color-white m-b-10 width-full">
                        FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        {{--  <h4 class="col-md-12 card-title">DIT STI</h4>  --}}
    </div>
    <a href="{{ url('dashboard-bawahan/liquid-status') }}" class="btn {{ $btnActive == 'status-liquid' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">Status
        LIQUID</a>
    <a href="{{ url('dashboard-bawahan/liquid-jadwal') }}"
       class="btn {{ $btnActive == 'kalendar-liquid' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">Kalendar
        LIQUID</a>
    <a href="{{ url('dashboard-bawahan/add-atasan') }}" 
        class="btn {{ $btnActive == 'add-atasan' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">{{ empty($usulan_atasan) ? 'Daftar Usulan Atasan' : $usulan_atasan }}</button>
    <a href="{{ url('dashboard-bawahan/penilaian-atasan') }}"
       class="btn {{ $btnActive == 'penilaian-atasan' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">Penilaian Atasan</a>
    <a href="{{ url('dashboard-bawahan/resolusi-atasan') }}"
       class="btn {{ $btnActive == 'resolusi-atasan' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">Resolusi Atasan</a>
</div>