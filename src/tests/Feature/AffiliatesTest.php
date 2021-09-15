<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Affiliate;
use Inertia\Testing\Assert;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test import affiliates upload success
     *
     * @return void
     */
    public function test_import_affiliates_success()
    {
        $filename = 'affiliates.txt';

        $this->actingAs($user = User::factory()->create());

        $this->assertTrue(!Affiliate::count());

        $file = UploadedFile::fake()->create($filename, file_get_contents(public_path('affiliates.txt')), 'txt');

        $response = $this->post(route('affiliates.import'), [
            'attachment' => $file
        ]);

        $this->assertTrue(Affiliate::exists());
        $this->assertTrue(Affiliate::count() == 32);
        $this->assertIsArray(Affiliate::first()->toArray());
    }

    /**
     * Test import affiliates upload missed fail
     *
     * @return void
     * @group affiliates
     */
    public function test_import_affiliates_validation_error_missed_file()
    {
        $this->actingAs($user = User::factory()->create());

        $this->assertTrue(!Affiliate::count());

        $response = $this->post(route('affiliates.import'), [
            'attachment' => null
        ]);

        $this->assertTrue(!Affiliate::exists());
        $this->assertTrue(!Affiliate::count());
    }

    /**
     * Test import affiliates upload wrong file
     *
     * @return void
     * @group affiliates
     */
    public function test_import_affiliates_validation_error_invalid_format()
    {

        $this->actingAs($user = User::factory()->create());

        $this->assertTrue(!Affiliate::count());

        $file = UploadedFile::fake()->create('affiliates.txt', 120, 'txt');

        $response = $this->post(route('affiliates.import'), [
            'attachment' => $file
        ]);

        $this->assertTrue(!Affiliate::exists());
        $this->assertTrue(!Affiliate::count());
    }

    /**
     * Test inertia index page range 100 km from dublin
     *
     * @return void
     * @group affiliates
     */
    public function test_affiliates_index_range_100_km_from_dublin_default()
    {
        $this->artisan('affiliates:import', ['file' => 'public/affiliates.txt', '--force']);
        $this->actingAs($user = User::factory()->create());

        $this->get(route('affiliates.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Affiliates/Index')
                ->has('affiliates', 16)
            );
    }

    /**
     * Test inertia index page with range 200 km from dublin
     *
     * @return void
     * @group affiliates
     */
    public function test_affiliates_index_range_200_km_from_dublin()
    {
        $this->artisan('affiliates:import', ['file' => 'public/affiliates.txt', '--force']);
        $this->actingAs($user = User::factory()->create());

        $this->get(route('affiliates.index', ['range' => 200000]))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Affiliates/Index')
                ->has('affiliates', 25)
            );
    }

    /**
     * Test inertia index page with range 500 km from dublin
     *
     * @return void
     * @group affiliates
     */
    public function test_affiliates_index_range_500_km_from_dublin()
    {
        $this->artisan('affiliates:import', ['file' => 'public/affiliates.txt', '--force']);
        $this->actingAs($user = User::factory()->create());

        $this->get(route('affiliates.index', ['range' => 500000]))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Affiliates/Index')
                ->has('affiliates', 32)
            );
    }

    /**
     * Test inertia index page with range 500 km from malta
     *
     * @return void
     * @group affiliates
     */
    public function test_affiliates_index_range_500_km_from_malta()
    {
        $this->artisan('affiliates:import', ['file' => 'public/affiliates.txt', '--force']);
        $this->actingAs($user = User::factory()->create());

        $this->get(route('affiliates.index', [
                'range' => 500000,
                'city' => 'malta'
            ]))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Affiliates/Index')
                ->has('affiliates', 0)
            );
    }
}
