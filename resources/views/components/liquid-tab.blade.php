<ul class="nav nav-tabs comp-tab">
    <li class="nav-item">
        <a href="{{ route('liquid.edit', $liquid) }}" class="nav-link {{ $active == 'liquid' ? 'active' : '' }}">1. Tanggal LIQUID</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('liquid.unit-kerja.edit', $liquid) }}" class="nav-link {{ $active == 'unit-kerja' ? 'active' : '' }}">2. Unit Kerja</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('liquid.peserta.edit', $liquid) }}" class="nav-link {{ $active == 'peserta' ? 'active' : '' }}">3. Peserta Penilaian</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('liquid.dokumen.edit', $liquid) }}" class="nav-link {{ $active == 'dokumen' ? 'active' : '' }}">4. Dokumen</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('liquid.gathering.edit', $liquid) }}" class="nav-link {{ $active == 'gathering' ? 'active' : '' }}">5. Info Gathering (Opsional)</a>
    </li>
</ul>
