<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    /**
     * Display a listing of stores.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $city = $request->input('city');

        // Get all stores with filters applied
        $stores = Store::active()
            ->search($search)
            ->city($city)
            ->orderBy('city')
            ->orderBy('name')
            ->get();

        // Get unique cities for dropdown
        $cities = Store::active()
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        return view('stores.index', compact('stores', 'cities', 'search', 'city'));
    }
}
