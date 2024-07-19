<?php


class ParseDateTest extends TestCase
{
    /**
     * Test valid dates
     *
     * @return void
     * @dataProvider validDatesDataProvider
     * @test
     */
    public function it_can_handle_valid_date($input, $format, $output)
    {
        $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $output, 'Asia/Jakarta');
        $this->assertEquals(app_parse_date($input, $format)->toDateString(), $carbon->toDateString());
    }

    /**
     * Test invalid dates
     *
     * @return void
     * @dataProvider invalidDatesDataProvider
     * @test
     */
    public function in_return_null_for_invalid_date($input, $format)
    {
        $this->assertNull(app_parse_date($input, $format));
    }

    public function validDatesDataProvider()
    {
        return [
            ['01-01-2020', null, '01-01-2020'],
            ['01-01-2020', 'd-m-Y', '01-01-2020'],
            ['2020-01-01', 'Y-m-d', '01-01-2020'],
        ];
    }

    public function invalidDatesDataProvider()
    {
        return [
            ['9999-99-99', null],
            ['2000-07-31', 'd-m-Y'],
            ['foo', null],
            ['foo', 'd-m-Y'],
            ['foo', 'Y-m-d'],
        ];
    }
}
