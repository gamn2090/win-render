<?php

namespace App\Http\Controllers;

use App\Services\SiteSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct(protected SiteSearchService $siteSearchService)
    {
    }

    public function results(Request $request)
    {
        $query = trim((string) $request->input('q'));

        if (Auth::guard('web')->check()) {
            $results = $query !== ''
                ? $this->siteSearchService->searchCouple(Auth::guard('web')->user(), $query)
                : collect();

            return view('couple.search_results', [
                'query' => $query,
                'results' => $results,
                'page' => 'search_results',
            ]);
        }

        if (Auth::guard('vendor')->check()) {
            $results = $query !== ''
                ? $this->siteSearchService->searchVendor(Auth::guard('vendor')->user(), $query)
                : collect();

            return view('vendor.search_results', [
                'query' => $query,
                'results' => $results,
                'page' => 'search_results',
            ]);
        }

        return redirect('/');
    }
}
