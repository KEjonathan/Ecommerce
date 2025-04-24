<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notification  $notification
     * @return bool
     */
    public function view(User $user, Notification $notification)
    {
        return $user->id === $notification->user_id;
    }

    /**
     * Determine whether the user can update the notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notification  $notification
     * @return bool
     */
    public function update(User $user, Notification $notification)
    {
        return $user->id === $notification->user_id;
    }

    /**
     * Determine whether the user can delete the notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notification  $notification
     * @return bool
     */
    public function delete(User $user, Notification $notification)
    {
        return $user->id === $notification->user_id;
    }
} 