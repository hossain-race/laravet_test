<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventListenerLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
//        \Log::info('sddddddddddddddd' );
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if (!auth()->user()->roles->pluck('name')->toArray() and !auth()->user()->permissions->pluck('name')->toArray()){
            $userId = $event->user['id'];
            \DB::table('role_users')->insert(
                ['user_id' => $userId, 'role_id' => 3]
            );
        }


    }
}
