<?php

namespace App\Events;

use App\Models\DepartmentItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OpeningBalanceCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $departmentItem;
    public $type;
    public $openingBalanceId;
    public $weight;
    public $department;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DepartmentItem $departmentItem, $type, $weight, $openingBalanceId, $department)
    {
        $this->departmentItem = $departmentItem;
        $this->type = $type;
        $this->openingBalanceId = $openingBalanceId;
        $this->weight = $weight;
        $this->department = $department;
    }
}
