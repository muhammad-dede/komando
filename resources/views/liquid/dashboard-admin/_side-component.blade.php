<div class="">
    <div class="">
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
                            @if(auth()->user()->can(\App\Enum\LiquidPermission::VIEW_ALL_UNIT))
                                <a href="{{ url($setting->getSetting()['manual-book-admin-root']) }}" target="_blank" rel="noopener" class="btn bg-success m-b-10 width-full">
                                    Manual Book
                                </a>
                            @else
                                <a href="{{ url($setting->getSetting()['manual-book-admin-unit-pelaksana']) }}" target="_blank" rel="noopener" class="btn bg-success m-b-10 width-full">
                                    Manual Book
                                </a>
                            @endif
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
                {{--  <h4 class="col-md-12 card-title">DIT STI</h4>  --}}
                <a href="{{ url('liquid/create') }}" class="btn bg-yellow color-white colorpicker btn-block m-b-10">Create LIQUID</a>
                <a href="{{ route('dashboard-admin.liquid-status.index', ['unit_code' => request('unit_code')]) }}" class="btn
                    {{ $btnActive == 'status-liquid' ? 'bg-blue color-white' : 'bg-light' }}
                    btn-block m-b-10">
                    Status LIQUID
                </a>
                <a href="{{ route('dashboard-admin.liquid-jadwal.index', ['unit_code' => request('unit_code')]) }}"
                   class="btn {{ $btnActive == 'kalendar-liquid' ? 'bg-blue color-white' : 'bg-light' }} btn-block m-b-10">Kalendar
                    LIQUID</a>
                <a href="{{ url('dashboard-admin/liquid-all') }}" class="btn bg-light btn-block m-b-10">View All LIQUID</a>

                <a href="{{ route('dashboard-admin.liquid.index') }}" class="btn btn-success btn-block m-b-10">
                    <em class="fa fa-file-text"></em>
                    Report LIQUID
                </a>
            </div>
        </div>
    </div>
</div>