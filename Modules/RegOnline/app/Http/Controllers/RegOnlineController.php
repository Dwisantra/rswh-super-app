<?php

namespace Modules\RegOnline\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RegOnline\Services\RegOnlineService;

class RegOnlineController extends Controller
{
    protected $regonline;

    public function __construct(RegOnlineService $regonline)
    {
        $this->regonline = $regonline;
    }

    public function poli()
    {
        return response()->json(
            $this->regonline->getPoli()
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('regonline::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('regonline::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('regonline::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('regonline::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
