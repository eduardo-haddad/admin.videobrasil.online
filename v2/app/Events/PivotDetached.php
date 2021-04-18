<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class PivotDetached
{
    use SerializesModels;

    public $pivot_a;
    public $pivot_b;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $pivot_a, Model $pivot_b)
    {
        $this->pivot_a = $pivot_a;
        $this->pivot_b = $pivot_b;
    }
}
