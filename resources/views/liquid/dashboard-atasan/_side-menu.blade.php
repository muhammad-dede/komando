<div class="card-box">
    <div class="row">
        <div class="col-md-12">
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
                    <a href="{{ url($setting->getSetting()['manual-book-dashboard-atasan']) }}" target="_blank" rel="noopener" class="btn bg-success m-b-10 width-full">
                        Manual Book
                    </a>
                </div>
                <div class="col-md-5 col-xs-12">
                    <a href="{{ url($setting->getSetting()['faq']) }}" target="_blank" rel="noopener" class="btn bg-blue color-white m-b-10 width-full">
                        FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url('dashboard-atasan/liquid-status') }}" class="btn {{ $btnActive == 'status-liquid' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">
                Status LIQUID
            </a>
            <a href="{{ url('dashboard-atasan/liquid-jadwal') }}"
               class="btn {{ $btnActive == 'kalendar-liquid' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">
               Kalendar LIQUID
            </a>
            <a href="{{ url('dashboard-atasan/feedback') }}"
               class="btn {{ $btnActive == 'feedback' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">
               {!! $kelebihan !!} dan {!! $kekurangan !!}
            </a>
            <a href="{{ url('dashboard-atasan/saran-harapan') }}"
               class="btn {{ $btnActive == 'saran-harapan' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">
               {!! $saran !!} dan Harapan
            </a>
            <a href="{{ url('dashboard-atasan/history-penilaian') }}"
               class="btn {{ $btnActive == 'history-penilaian' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">
               Data History Penilaian
            </a>
        </div>
    </div>
</div>
