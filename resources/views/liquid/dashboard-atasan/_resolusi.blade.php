<div class="row dashboard-atasan-top">
    <div class="col-sm-3">
        <div class="card radius-10"
             style="background: linear-gradient(0deg, rgba(0, 153, 250, 0.7), rgba(0, 153, 250, 0.7)), url('{{ asset('assets/images/resolusi.jpg') }}');">
            <div class="card-body card-resolusi">
                Resolusi Tahun Ini
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="card card-bg-blue">
            <div class="card-body">
                <div class="row">
                    <!--(old)new: Update Show Resolusi Liquid yang published yang terbaru 
                        @php($activeLiquid = \App\Models\Liquid\Liquid::query()->published()->forAtasan(auth()->user())->first()) {{-- currentYear() --}} -->
                    @php($activeLiquid = \App\Models\Liquid\Liquid::query()->orderBy('ID', 'desc')->published()->forAtasan(auth()->user())->first()) 
                    @if($activeLiquid)
                        @php $no = 1;  @endphp
                        @forelse(\App\Models\Liquid\Penyelarasan::getResolusiAsArray($activeLiquid, auth()->user()->strukturJabatan->pernr) as $index => $item)
                            <div class="col-md-4">
                            <span class="title">
                                {{ $no++ }}. {{ $item }}
                            </span>
                            </div>
                        @empty
                            <div class="col-md-12 display-flex" style="justify-content: center">
                                <h1 class="font-italic text-white">Tidak ada resolusi yang sedang aktif</h1>
                            </div>
                        @endforelse
                    @else
                        <div class="col-md-12 display-flex" style="justify-content: center">
                            <h1 class="font-italic text-white">Tidak ada liquid aktif</h1>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
