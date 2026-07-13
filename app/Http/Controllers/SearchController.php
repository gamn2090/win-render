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
        $wantsSuggestions = $request->ajax() || $request->wantsJson();

        if (Auth::guard('web')->check()) {
            $results = $query !== ''
                ? $this->siteSearchService->searchCouple(Auth::guard('web')->user(), $query)
                : collect();

            if ($wantsSuggestions) {
                return response()->json(['results' => $results->take(8)->values()]);
            }

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

            if ($wantsSuggestions) {
                return response()->json(['results' => $results->take(8)->values()]);
            }

            return view('vendor.search_results', [
                'query' => $query,
                'results' => $results,
                'page' => 'search_results',
            ]);
        }

        if ($wantsSuggestions) {
            return response()->json(['results' => []]);
        }

        return redirect('/');
    }
}
