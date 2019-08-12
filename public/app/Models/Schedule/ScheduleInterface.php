<?php


namespace App\Models\Schedule;


use Illuminate\Http\Request;


interface ScheduleInterface
{
    public function transport();

    public function region();

    public static function newRoute(Request $request);
}
