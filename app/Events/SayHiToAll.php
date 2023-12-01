<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;

class SayHiToAll implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;


    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
        $this->broadcastVia('pusher');
        $this->message = "Hello to All";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('notify-all-present');
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->user->name. ' says ' . $this->message,
            'time' => Carbon::now()->format('Y-m-d H:i:s')
        ];
    }
}
