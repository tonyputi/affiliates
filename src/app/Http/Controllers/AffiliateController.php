<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Affiliate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AffiliateController extends Controller
{
    /**
     * offices latitude and longitude
     *
     * @var array
     */
    protected static array $offices = [
        'malta' => [35.89972, 14.51472],
        'dublin' => [53.3340285, -6.2535495]
    ];

    /**
     * Return the available affiliates that are within a range of 100 Km from Dublin
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $request->validate([
            'range' => ['sometimes', 'numeric'],
            'city' => ['sometimes', 'in:dublin,malta']
        ]);

        $range = $request->input('range', 100000);
        $city = $request->input('city', 'dublin');

        list($lat, $lng) = static::$offices[$city];

        $affiliates = Affiliate::orderBy('affiliate_id', 'asc')->get();
        $affiliates = $affiliates->filter(fn ($affiliate) => $affiliate->distance($lat, $lng) <= $range);

        return Inertia::render('Affiliates/Index', [
            'affiliates' => $affiliates,
            'meta' => [
                'range' => $range,
                'city' => $city
            ]
        ]);
    }

    /**
     * Import the data
     *
     * @param Request $request
     * @return void
     */
    public function import(Request $request)
    {
        $request->validate([
            'attachment' => ['required', 'file']
        ]);

        try {
            $data = $request->attachment->parseJson();
            
            // trucante the table affiliates
            Affiliate::truncate();

            // insert affiliates from mapped file
            Affiliate::insert($data);

            return redirect()
                ->route('affiliates.index')
                ->banner("Affiliates succesful imported!");
        } catch (Exception $e) {
            throw ValidationException::withMessages(['file' => 'An error occurred parsing the give file']);
        }
    }
}
