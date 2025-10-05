<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccessHistoryService;
use App\Services\UserService;
use App\General\Functions;

class AccessHistory extends Controller
{
    /**
     * @var \App\Services\AccessHistoryService
     */
    protected $accessHistoryService;

    /**
     * @var \App\Services\UserService
     */
    protected $userService;


    /**
     * @param \App\Services\AccessHistoryService
     */
    public function __construct(AccessHistoryService $accessHistoryService, UserService $userService)
    {
        $this->accessHistoryService = $accessHistoryService;
        $this->userService = $userService;
    }

    /**
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $userOptions = Functions::makeOptionsByList($this->userService->search(), 'id', 'name');
        $userOptions = array("" => trans('app.all')) + $userOptions;
        $defaultUserOption = array_key_first($userOptions);

        $assign = [
            'accessHistories' => $this->accessHistoryService->search([
                'order_by' => 'login_at',
                'order_direction' => 'desc',
            ]),
            'userOptions' => $userOptions,
            'defaultUserOption' => $defaultUserOption,
        ];

        return view('access-history.index', $assign);
    }
}
