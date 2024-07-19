@inject('basicUtil', 'App\Utils\BasicUtil')

@php
    $config = $basicUtil->getConfig();
    $user = auth()->user();
    $accessRekapAll = $user->hasRole('root') || $user->hasRole('admin_pusat') || $user->hasRole('admin_liquid_pusat');
@endphp

<ul class="nav nav-tabs comp-tab" role="tablist">
    <li class="nav-item">
        <a href="{{ url('dashboard-admin/download-report-liquid') }}" class="nav-link  {{ $nav == 'rekap-liquid' ? 'active' : '' }}">Rekap Liquid</a>
    </li>
    <li class="nav-item">
        <a href="{{ url('dashboard-admin/rekap-kelebihan') }}" class="nav-link {{ $nav == 'kelebihan' ? 'active' : '' }}">Top 5 {{ $config->lebihShort }}</a>
    </li>
    <li class="nav-item">
        <a href="{{ url('dashboard-admin/rekap-kekurangan') }}" class="nav-link {{ $nav == 'kekurangan' ? 'active' : '' }}">Top 5 {{ $config->kurangShort }}</a>
    </li>
    <li class="nav-item">
        <a href="{{ url('dashboard-admin/rekap-feedback') }}" class="nav-link {{ $nav == 'feedback' ? 'active' : '' }}">Feedback Lainnya</a>
    </li>
    <li class="nav-item">
        <a href="{{ url('dashboard-admin/rekap-partisipan') }}" class="nav-link {{ $nav == 'partisipan' ? 'active' : '' }}">Partisipan</a>
    </li>

    @if ($accessRekapAll)
        <li class="nav-item">
            <a href="{{ url('dashboard-admin/rekap-all-kelebihan') }}" class="nav-link {{ $nav == 'all-kelebihan' ? 'active' : '' }}">Semua {{ $config->lebihShort }}</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('dashboard-admin/rekap-all-kekurangan') }}" class="nav-link {{ $nav == 'all-kekurangan' ? 'active' : '' }}">Semua {{ $config->kurangShort }}</a>
        </li>
    @endif

    <li class="nav-item">
        <a href="{{ url('dashboard-admin/rekap-progress-liquid') }}" class="nav-link {{ $nav == 'all-rekap-progress-liquid' ? 'active' : '' }}">Rekap Progress liquid</a>
    </li>
</ul><!-- Tab panes -->
