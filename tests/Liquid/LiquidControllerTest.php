<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithPages;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidControllerTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use InteractsWithPages;
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->skipAuthorization();
    }

    public function testCreateNotOkTanpaKelebihanKekuranganAktif()
    {
        $this->emptyLiquidTables()->login();

        $this->visit('liquid/create')
            ->dontSeeElement('#formCreateLiquid')
            ->see('Create Liquid');
    }

    public function testCreateOkJikaAdaKelebihanKekuranganAktif()
    {
        $this->emptyLiquidTables()->login();
        factory(\App\Models\Liquid\KelebihanKekurangan::class)->create(['status' => 'AKTIF']);

        $this->visit('liquid/create')
            ->seeElement('#formCreateLiquid')
            ->see('Create Liquid');
    }

    /**
     * @test
     */
    public function b1_can_create_liquid_with_normal_input()
    {
        $this->emptyLiquidTables()->login();
        $kelebihanKekurangan = factory(\App\Models\Liquid\KelebihanKekurangan::class)->create(['status' => 'AKTIF']);
        $dates = $this->liquidDates();
        $data = $this->liquidFormData($dates, \App\Enum\ReminderAksiResolusi::MINGGUAN);

        $this->post('liquid', $data)->assertResponseStatus(302);

        $this->seeInDatabase(
            'liquids',
            [
                'feedback_start_date' => $dates[0]->toDateTimeString(),
                'feedback_end_date' => $dates[1]->toDateTimeString(),
                'penyelarasan_start_date' => $dates[2]->toDateTimeString(),
                'penyelarasan_end_date' => $dates[3]->toDateTimeString(),
                'pengukuran_pertama_start_date' => $dates[4]->toDateTimeString(),
                'pengukuran_pertama_end_date' => $dates[5]->toDateTimeString(),
                'pengukuran_kedua_start_date' => $dates[6]->toDateTimeString(),
                'pengukuran_kedua_end_date' => $dates[7]->toDateTimeString(),
                'reminder_aksi_resolusi' => \App\Enum\ReminderAksiResolusi::MINGGUAN,
                'kelebihan_kekurangan_id' => $kelebihanKekurangan->getKey(),
            ]
        );
    }

    /**
     * @test
     */
    public function b2_cannot_create_liquid_with_invalid_date()
    {
        config()->set(['liquid.disable_date_validation' => false]);
        $this->emptyLiquidTables()->login();
        factory(\App\Models\Liquid\KelebihanKekurangan::class)->create(['status' => 'AKTIF']);
        $dates = $this->liquidDatesInvalid();
        $data = $this->liquidFormData($dates, \App\Enum\ReminderAksiResolusi::MINGGUAN);

        $this->post('liquid', $data)->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function b3_cannot_create_liquid_with_invalid_date()
    {
        config()->set(['liquid.disable_date_validation' => false]);
        $this->emptyLiquidTables()->login();
        factory(\App\Models\Liquid\KelebihanKekurangan::class)->create(['status' => 'AKTIF']);
        $dates = $this->liquidDates();

        // tanggal mulai penyelarasan = tanggal mulai feedback
        $dates[2] = $dates[0];
        $data = $this->liquidFormData($dates, \App\Enum\ReminderAksiResolusi::MINGGUAN);

        $this->post('liquid', $data)->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function b4_cannot_create_liquid_with_invalid_date()
    {
        config()->set(['liquid.disable_date_validation' => false]);
        $this->emptyLiquidTables()->login();
        factory(\App\Models\Liquid\KelebihanKekurangan::class)->create(['status' => 'AKTIF']);
        $dates = $this->liquidDates();

        // tanggal mulai pengukuran pertama = tanggal mulai penyelarasan
        $dates[4] = $dates[2];
        $data = $this->liquidFormData($dates, \App\Enum\ReminderAksiResolusi::MINGGUAN);

        $this->post('liquid', $data)->assertSessionHasErrors();
    }

    public function testTidakBisaStoreJikaTidakAdaKelebihanKekuranganAktif()
    {
        $this->emptyLiquidTables()->login();
        $data = [
            'feedback_start_date' => '01-01-2020',
            'feedback_end_date' => '02-01-2020',
            'penyelarasan_start_date' => '03-01-2020',
            'penyelarasan_end_date' => '04-01-2020',
            'pengukuran_pertama_start_date' => '05-01-2020',
            'pengukuran_pertama_end_date' => '06-01-2020',
            'pengukuran_kedua_start_date' => '07-01-2020',
            'pengukuran_kedua_end_date' => '08-01-2020',
            'reminder_aksi_resolusi' => \App\Enum\ReminderAksiResolusi::MINGGUAN,
        ];

        $this->post('liquid', $data)->assertResponseStatus(302);

        $this->assertSessionHasErrors(['kelebihan_kekurangan_id']);

        $this->dontSeeInDatabase(
            'liquids',
            [
                'feedback_start_date' => '2020-01-01 00:00:00',
                'feedback_end_date' => '2020-01-02 00:00:00',
                'penyelarasan_start_date' => '2020-01-03 00:00:00',
                'penyelarasan_end_date' => '2020-01-04 00:00:00',
                'pengukuran_pertama_start_date' => '2020-01-05 00:00:00',
                'pengukuran_pertama_end_date' => '2020-01-06 00:00:00',
                'pengukuran_kedua_start_date' => '2020-01-07 00:00:00',
                'pengukuran_kedua_end_date' => '2020-01-08 00:00:00',
                'reminder_aksi_resolusi' => \App\Enum\ReminderAksiResolusi::MINGGUAN,
            ]
        );
    }

    public function testEditOk()
    {
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $this
            ->login()
            ->visit("liquid/{$liquid->getKey()}/edit")
            ->see("Edit Liquid #{$liquid->getKey()}")
            ->seeForm()
            ->assertResponseOk();
    }

    public function testUpdateOk()
    {
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $dates = $this->liquidDates();
        $data = $this->liquidFormData($dates, \App\Enum\ReminderAksiResolusi::BULANAN);

        $this->login()
            ->put("liquid/{$liquid->getKey()}", $data)
            ->assertResponseStatus(302);

        $this->seeInDatabase(
            'liquids',
            [
                'feedback_start_date' => $dates[0]->toDateTimeString(),
                'feedback_end_date' => $dates[1]->toDateTimeString(),
                'penyelarasan_start_date' => $dates[2]->toDateTimeString(),
                'penyelarasan_end_date' => $dates[3]->toDateTimeString(),
                'pengukuran_pertama_start_date' => $dates[4]->toDateTimeString(),
                'pengukuran_pertama_end_date' => $dates[5]->toDateTimeString(),
                'pengukuran_kedua_start_date' => $dates[6]->toDateTimeString(),
                'pengukuran_kedua_end_date' => $dates[7]->toDateTimeString(),
                'reminder_aksi_resolusi' => \App\Enum\ReminderAksiResolusi::BULANAN,
            ]
        );
    }

    protected function seeForm()
    {
        return $this->seeElement('[name=feedback_start_date]')
            ->seeElement('[name=feedback_end_date]')
            ->seeElement('[name=penyelarasan_start_date]')
            ->seeElement('[name=penyelarasan_end_date]')
            ->seeElement('[name=pengukuran_pertama_start_date]')
            ->seeElement('[name=pengukuran_pertama_end_date]')
            ->seeElement('[name=pengukuran_kedua_start_date]')
            ->seeElement('[name=pengukuran_kedua_end_date]')
            ->seeElement('[name=reminder_aksi_resolusi]');
    }

    protected function liquidDates()
    {
        return [
            \Carbon\Carbon::today()->addDays(1),
            \Carbon\Carbon::today()->addDays(2),
            \Carbon\Carbon::today()->addDays(3),
            \Carbon\Carbon::today()->addDays(4),
            \Carbon\Carbon::today()->addDays(5),
            \Carbon\Carbon::today()->addDays(6),
            \Carbon\Carbon::today()->addDays(7),
            \Carbon\Carbon::today()->addDays(8),
        ];
    }

    protected function liquidDatesInvalid()
    {
        return [
            \Carbon\Carbon::today()->addDays(2),
            \Carbon\Carbon::today()->addDays(1),
            \Carbon\Carbon::today()->addDays(4),
            \Carbon\Carbon::today()->addDays(3),
            \Carbon\Carbon::today()->addDays(6),
            \Carbon\Carbon::today()->addDays(5),
            \Carbon\Carbon::today()->addDays(8),
            \Carbon\Carbon::today()->addDays(7),
        ];
    }

    protected function liquidFormData($dates, $reminder)
    {
        return [
            'feedback_start_date' => $dates[0]->format('d-m-Y'),
            'feedback_end_date' => $dates[1]->format('d-m-Y'),
            'penyelarasan_start_date' => $dates[2]->format('d-m-Y'),
            'penyelarasan_end_date' => $dates[3]->format('d-m-Y'),
            'pengukuran_pertama_start_date' => $dates[4]->format('d-m-Y'),
            'pengukuran_pertama_end_date' => $dates[5]->format('d-m-Y'),
            'pengukuran_kedua_start_date' => $dates[6]->format('d-m-Y'),
            'pengukuran_kedua_end_date' => $dates[7]->format('d-m-Y'),
            'reminder_aksi_resolusi' => $reminder,
        ];
    }
}
