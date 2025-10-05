<?php

namespace App\Http\Controllers;

use App\Services\UnitService;

class Unit extends Controller
{
    /**
     * @var \App\Services\UnitService
     */
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $assign = [
            'units' => $this->unitService->search(),
        ];
        return view('measurement.index', $assign);
    }

    /**
     * Search ajax
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAjax()
    {
        $units = $this->unitService->search();
        return response()->json(['results' => $units], 200);
    }
}
