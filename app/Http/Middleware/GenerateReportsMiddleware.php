<?php

namespace App\Http\Middleware;

use App\Models\GeneratedScheduledTasksTimeStamp;
use App\Models\GenerateReportsTimeStamp;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GenerateReportsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            GenerateReportsTimeStamp::day(Carbon::today()->format('Y-m-d'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Artisan::call('departments:dailyreport');
            GenerateReportsTimeStamp::create([
                'date' => Carbon::today()->format('Y-m-d'),
                'type' => 'departments daily reports',
            ]);
        }
        return $next($request);
    }
}
