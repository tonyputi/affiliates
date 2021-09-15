<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use WithFaker;

    /**
     * Parse affiliates file without errors
     *
     * @return void
     * @group affiliates
     */
    public function test_parse_file_as_json_success()
    {
        $data = parse_file_as_json(public_path('affiliates.txt'));

        $this->assertIsArray($data);
        $this->assertCount(32, $data);
        $this->assertArrayHasKey(1, $data);
        $this->assertArrayHasKey('affiliate_id', $data[1]);
    }

    /**
     * Parse not existing file and throw an exception
     *
     * @return void
     * @group affiliates
     */
    public function test_parse_file_as_json_error()
    {
        $this->expectException('ErrorException');

        parse_file_as_json(public_path('non-existing-file.txt'));    
    }

    /**
     * Parse invalid format/encoding file
     *
     * @return void
     * @group affiliates
     */
    public function test_parse_file_as_json_validation()
    {
        $this->expectException('Exception');

        parse_file_as_json(public_path('robots.txt'));
    }


    /**
     * Test great circle distance success
     *
     * @return void
     * @group affiliates
     */
    public function test_great_circle_distance()
    {
        // generating fake latitude closed to dublin
        $latFrom = $this->faker->latitude(53, 54);
        // generating fake longitude closed to dublin
        $lngFrom = $this->faker->longitude(-6, -5);

        // dublin latitude
        $latTo = 53.3340285;

        // dublin longitude
        $lngTo = -6.2535495;

        // calculating the distance between two points
        $distance = great_circle_distance($latFrom, $lngFrom, $latTo, $lngTo);

        // distance should be always less than 100 km
        $this->assertTrue(($distance) <= 100000);
    }

    /**
     * Test affiliate:filter CLI command
     *
     * @return void
     * @group affiliates
     */
    public function test_affiliates_filter_command()
    {
        $this->artisan('affiliates:filter', ['file' => 'public/affiliates.txt', '-r' => 100])
            ->expectsTable(['ID', 'Name'], [
                [4, 'Inez Blair'],
                [5, 'Sharna Marriott'],
                [6, 'Jez Greene'],
                [8, 'Addison Lister'],
                [11, 'Isla-Rose Hubbard'],
                [12, 'Yosef Giles'],
                [13, 'Terence Wall'],     
                [15, 'Veronica Haines'],
                [17, 'Gino Partridge'],
                [23, 'Ciara Bannister'],
                [24, 'Ellena Olson'],
                [26, 'Moesha Bateman'],
                [29, 'Alvin Stamp'],
                [30, 'Kingsley Vang'],
                [31, 'Maisha Mccarty'],
                [39, 'Kirandeep Browning']
            ]);

    }
}
