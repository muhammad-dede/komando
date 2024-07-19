@inject('liquidService', 'App\Services\LiquidService')

@if (isset($liquid))
    @php
        $jadwalStatus = $liquidService->getJadwalProgressStatus($liquid);
    @endphp

    <ul class="comp-progress" style="margin-top: 0px">
        <li class="progress__item progress__item--{{ $jadwalStatus['feedback'] }}">
            <p class="progress__title">Pelaksanaan Feedback</p>

            <p class="progress__info">
                {{ date_id($liquid->feedback_start_date) }} -
                {{ date_id($liquid->feedback_end_date) }}
            </p>
        </li>

        <li class="progress__item progress__item--{{ $jadwalStatus['penyelarasan'] }}">
            <p class="progress__title">Pelaksanaan Penyelarasan</p>

            <p class="progress__info">
                {{ date_id($liquid->penyelarasan_start_date) }} -
                {{ date_id($liquid->penyelarasan_end_date) }}
            </p>
        </li>

        <li class="progress__item progress__item--{{ $jadwalStatus['pengukuran_pertama'] }}">
            <p class="progress__title">Pengukuran Pertama</p>

            <p class="progress__info">
                {{ date_id($liquid->pengukuran_pertama_start_date) }} -
                {{ date_id($liquid->pengukuran_pertama_end_date) }}
            </p>
        </li>

        <li class="progress__item progress__item--{{ $jadwalStatus['pengukuran_kedua'] }}">
            <p class="progress__title">Pengukuran Kedua</p>

            <p class="progress__info">
                {{ date_id($liquid->pengukuran_kedua_start_date) }} -
                {{ date_id($liquid->pengukuran_kedua_end_date) }}
            </p>
        </li>
    </ul>
@endif