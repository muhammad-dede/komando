<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidGatheringControllerTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->skipAuthorization();
    }

    /**
     * @test
     */
    public function tbd_can_see_liquid_gathering_edit_page()
    {
        $this->login();

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $this
            ->visit("liquid/{$liquid->getKey()}/gathering/edit")
            ->see("Edit Liquid #{$liquid->getKey()}")
            ->seeForm()
            ->assertResponseOk();
    }

    /**
     * @test
     */
    public function b21_can_update_liquid_gathering()
    {
        $this->login();

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $data = [
            'gathering_start_date' => '01-01-2020',
            'gathering_end_date' => '02-01-2020',
            'gathering_location' => 'Sragen',
        ];
        $this->put("liquid/{$liquid->getKey()}/gathering", $data)->assertResponseStatus(302);

        $this->seeInDatabase(
            'liquids',
            ['id' => $liquid->getKey(), 'gathering_location' => 'Sragen']
        );
    }

    /**
     * @test
     */
    public function b22_cannot_update_liquid_gathering_with_invalid_date()
    {
        $this->login();

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $data = [
            'gathering_start_date' => '02-01-2020',
            'gathering_end_date' => '01-01-2020',
            'gathering_location' => 'Sragen',
        ];
        $this->put("liquid/{$liquid->getKey()}/gathering", $data)->assertSessionHasErrors();

        $this->dontSeeInDatabase(
            'liquids',
            ['id' => $liquid->getKey(), 'gathering_location' => 'Sragen']
        );
    }

    protected function seeForm()
    {
        return $this
            ->seeElement('[name=gathering_start_date]')
            ->seeElement('[name=gathering_end_date]')
            ->seeElement('[name=gathering_location]');
    }
}
