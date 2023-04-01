<?php

namespace App\Events;

use App\Models\DepartmentItem;
use App\Models\Transfer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $afterCommit = true;

    public $transfer;
    public $transferFromDepartmentItem;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Transfer $transfer, DepartmentItem $transferFromDepartmentItem)
    {
        $this->transfer = $transfer;
        $this->transferFromDepartmentItem = $transferFromDepartmentItem;
    }

}
