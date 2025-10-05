<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class User extends Controller
{
    /**
     * @var \App\Services\UserService
     */
    protected $userService;

    /**
     * @param \App\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Filter and show list user
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $assign = [];

        $assign['users'] = $this->userService->search();

        return view('user.index', $assign);
    }

    /**
     * Form create user
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $record = $this->userService->find($id);

        // Save detail to session
        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession($record);
        }

        return view('user.edit', ['user' => $record]);
    }

    /**
     * handle form
     *
     * @param \App\Http\Requests\UserStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        $this->userService->store($request);

        return redirect()->route(USER_ROUTE);
    }

    /**
     * Delete a user
     *
     * @param \Illuminate\Http\Request $request
     * @param id $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $this->userService->delete($id);

        return redirect()->route(USER_ROUTE);
    }
}
