<?php

namespace App\Events;

use App\Models\Liquid\Liquid;
use Illuminate\Queue\SerializesModels;

class LiquidPublished extends Event
{
    use SerializesModels;

    public $liquid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Liquid $liquid)
    {
        $this->liquid = $liquid;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
