<?php

namespace App\Services;

use App\Services\Concerns\BaseService;
use App\Repositories\AccessHistoryRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccessHistoryService extends BaseService
{
    /**
     * @param \App\Repositories\AccessHistoryRepository $repository
     */
    public function __construct(AccessHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Filter list by request
     *
     * @param array $conditions
     *
     * @return mixed
     */
    public function search($conditions = [])
    {
        $this->makeBuilder($conditions)->with(['user']);

        // user_id
        if ($this->filter->has('user_id')) {
            $this->builder->where('user_id', $this->filter->get('user_id'));
            $this->cleanFilterBuilder('user_id');
        }
        
        // from_login_at
        if ($this->filter->has('from_login_at')) {
            $fromLoginAt = Carbon::parse($this->filter->get('from_login_at'));
            $fromLoginAt->startOfDay();
            $this->builder->where('login_at', '>=', $fromLoginAt->format('Y/m/d H:s:i'));
            $this->cleanFilterBuilder('from_login_at');
        }

        // to_login_at
        if ($this->filter->has('to_login_at')) {
            $toLoginAt = Carbon::parse($this->filter->get('to_login_at'));
            $toLoginAt->endOfDay();
            $this->builder->where('login_at', '<', $toLoginAt->format('Y/m/d H:s:i'));
            $this->cleanFilterBuilder('to_login_at');
        }

        // order_by
        if ($this->filter->has('order_by')) {
            $direction =
                $this->filter->has('order_direction') ?
                $this->filter->get('order_direction') : 'desc';
            $this->builder->orderBy($this->filter->get('order_by'), $direction);
            $this->cleanFilterBuilder('order_by');
        }

        return $this->endFilter();
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function store($data)
    {
        $result = $this->repository->create($data);

        return $result;
    }

    /**
     * set login log
     *
     * @return void
     */
    public function setLoginLog()
    {
        $this->repository->create(
            [
                'user_id' => Auth::user()->id,
                'login_at' => Carbon::now(),
                'client_ip' => request()->getClientIp(),
                'session_id' => request()->session()->getId()
            ]
        );
    }

    /**
     * set logout log
     *
     * @return void
     */
    public function setLogoutLog()
    {
        $this->repository->where('session_id', request()->session()->getId())->update([
            'logout_at' => Carbon::now(),
        ]);
    }

    /**
     * ajust history for session timeout event
     * warning: you should increase session lifetime to get user logout event properly
     *
     * @return void
     */
    public function adjustSessionTimeout()
    {
        $sessionLifetime = config('session.lifetime');
        $accessHistories = $this->repository
            ->where('user_id', Auth::user()->id)
            ->whereNull('logout_at')
            ->get();

        foreach ($accessHistories as $accessHistory) {
            $logoutAt = Carbon::parse($accessHistory->login_at)
                ->copy()->addMinutes($sessionLifetime);
            $accessHistory->update([
                'logout_at' =>  $logoutAt,
            ]);
        }
    }
}
