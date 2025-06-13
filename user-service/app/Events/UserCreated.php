<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserCreated implements ShouldBroadcast {
    public $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function broadcastOn() {
        return new Channel('user-channel');
    }

    public function broadcastAs() {
        return 'user.created';
    }
}
