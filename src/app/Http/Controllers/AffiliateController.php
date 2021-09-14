<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Illuminate\Http\Request;

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
        $affiliates = Affiliate::orderBy('affiliate_id', 'asc')->get();
        // dump(Affiliate::count());

        $affiliates = $affiliates->filter(fn ($affiliate) => $affiliate->distance(53.3340285, -6.2535495) < 100000);
        // dump($affiliates->count(), $affiliates->toArray());

        // return $affiliates->filter(fn ($affiliate) => $affiliate->distance(53.3340285, -6.2535495) < 100000);

        return view('welcome');
    }
}
