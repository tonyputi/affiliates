<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use App\Http\Resources\AffiliateResource;

class AffiliateController extends Controller
{
    /**
     * Return the available affiliates that are within a range of 100 Km from Dublin
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $lat = $request->input('latitude', 53.3340285);
        $lng = $request->input('longitude', -6.2535495);
        $range = $request->input('range', 100000);

        $affiliates = Affiliate::orderBy('affiliate_id', 'asc')->get();
        // dump(Affiliate::count());

        // $affiliates = $affiliates->filter(fn ($affiliate) => $affiliate->distance(53.3340285, -6.2535495) < 100000);
        // dump($affiliates->count(), $affiliates->toArray());

        // return $affiliates->filter(fn ($affiliate) => $affiliate->distance(53.3340285, -6.2535495) < 100000);

        // $affiliates = $affiliates->filter(fn ($affiliate) => $affiliate->distance($lat, $lng) <= $range);
        // $props = AffiliateResource::collection($affiliates)
        //     ->toResponse($request)
        //     ->getData(true);

        // dd($props);

        // return Inertia::render('Affiliates/Index', $props);

        return Inertia::render('Affiliates/Index', [
            'affiliates' => $affiliates->filter(fn ($affiliate) => $affiliate->distance($lat, $lng) <= $range)
        ]);
    }
}
