<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidUnitKerjaControllerTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->skipAuthorization();
    }

    public function testBisaMelihatHalamanEdit()
    {
        $this->login();

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $this
            ->visit("liquid/{$liquid->getKey()}/unit-kerja/edit")
            ->see("Edit Liquid #{$liquid->getKey()}")
            ->assertResponseOk();
    }

    public function testBisaMelakukanUpdateData()
    {
        $this->login();

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $data = ['business_area' => [7113, 7114]];
        $this->put("liquid/{$liquid->getKey()}/unit-kerja", $data)->assertResponseStatus(302);

        $this->seeInDatabase(
            'liquid_business_area',
            ['liquid_id' => $liquid->getKey(), 'business_area' => 7113]
        );
        $this->seeInDatabase(
            'liquid_business_area',
            ['liquid_id' => $liquid->getKey(), 'business_area' => 7114]
        );

        $this->assertEquals(2, $liquid->businessAreas->count());
    }

    public function testAdminPusatBisaMelihatSemuaUnitKerja()
    {
        \Artisan::call('db:seed', ['--class' => 'LiquidPermissionSeeder']);

        $role = new \App\Role();
        $role->name = 'test_admin_liquid_pusat';
        $role->display_name = '-';
        $role->description = '-';
        $role->save();

        $role->perms()->attach(\App\Permission::where('name', \App\Enum\LiquidPermission::VIEW_ALL_UNIT)->first());

        $adminAllUnit = \App\User::first();
        $adminAllUnit->roles()->attach($role);
        $this->login($adminAllUnit);

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $this
            ->visit("liquid/{$liquid->getKey()}/unit-kerja/edit")
            ->seeInElement('#unitKerja', 'Kantor Pusat')
            ->seeInElement('#unitKerja', 'UP3 Merauke')
            ->seeInElement('#unitKerja', 'UPT Surakarta')
            ->seeInElement('#unitKerja', 'UPT Banda Aceh');
    }

    public function testAdminKantorIndukBisaMelihatUnitKerjaDalamSatuCompany()
    {
        \Artisan::call('db:seed', ['--class' => 'LiquidPermissionSeeder']);

        $role = new \App\Role();
        $role->name = 'test_admin_liquid_kantor_induk';
        $role->display_name = '-';
        $role->description = '-';
        $role->save();

        $role->perms()->attach(\App\Permission::where('name', \App\Enum\LiquidPermission::VIEW_UNIT_INDUK)->first());

        $adminKantorInduk = \App\User::first();
        $adminKantorInduk->roles()->attach($role);
        $this->login($adminKantorInduk);

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $expectedBusinessAreas = \App\Models\Liquid\BusinessArea::query()
            ->where('company_code', $adminKantorInduk->company_code)
            ->get();
        $unexpectedBusinessAreas = \App\Models\Liquid\BusinessArea::query()
            ->where('company_code', '<>', $adminKantorInduk->company_code)
            ->take(2)
            ->get();

        $this->visit("liquid/{$liquid->getKey()}/unit-kerja/edit");
        foreach ($expectedBusinessAreas as $area) {
            $this->seeInElement('#unitKerja', $area->description);
        }
        foreach ($unexpectedBusinessAreas as $area) {
            $this->dontSeeInElement('#unitKerja', $area->description);
        }
    }

    public function testAdminUnitBisaMelihatUnitKerjanyaSaja()
    {
        \Artisan::call('db:seed', ['--class' => 'LiquidPermissionSeeder']);

        $role = new \App\Role();
        $role->name = 'test_admin_liquid_kantor_unit_pelaksana';
        $role->display_name = '-';
        $role->description = '-';
        $role->save();

        $role->perms()->attach(\App\Permission::where('name', \App\Enum\LiquidPermission::VIEW_UNIT_PELAKSANA)->first());

        $adminUnit = \App\User::first();
        $adminUnit->roles()->attach($role);
        $this->login($adminUnit);

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $expectedBusinessAreas = \App\Models\Liquid\BusinessArea::query()
            ->where('business_area', $adminUnit->business_area)
            ->get();
        $unexpectedBusinessAreas = \App\Models\Liquid\BusinessArea::query()
            ->where('business_area', '<>', $adminUnit->business_area)
            ->take(2)
            ->get();

        $this->visit("liquid/{$liquid->getKey()}/unit-kerja/edit");
        foreach ($expectedBusinessAreas as $area) {
            $this->seeInElement('#unitKerja', $area->description);
        }
        foreach ($unexpectedBusinessAreas as $area) {
            $this->dontSeeInElement('#unitKerja', $area->description);
        }
    }
}
