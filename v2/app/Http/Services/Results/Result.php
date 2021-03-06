<?php

namespace App\Http\Services\Results;

use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class Result
{
    /**
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * Set default date range to 7 days
     * @var int
     */
    protected $range = 7;

    /**
     *
     */
    public function __construct(Request $request)
    {
        // $from_date = Carbon::now()->subDays($this->range - 1)->format('d/m/Y');
        // $to_date = Carbon::now()->format('d/m/Y');
        $from_date = Carbon::now()->startOfMonth()->format('d/m/Y');
        $to_date = Carbon::now()->endOfMonth()->format('d/m/Y');

        // Create a new Request object to not modify the original $request
        $this->request = new Request($request->all());
        $this->request->merge([
            'from_date' => $this->request->get('from_date', $from_date),
            'to_date' => $this->request->get('to_date', $to_date)
        ]);
    }

    /**
     * @param Request $request
     */
    public abstract function get();
}
