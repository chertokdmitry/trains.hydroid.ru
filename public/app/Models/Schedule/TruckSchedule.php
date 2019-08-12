<?php


namespace App\Models\Schedule;


use Illuminate\Http\Request;


class TruckSchedule extends Schedule implements ScheduleInterface
{
    public function transport()
    {
        return $this->hasOne('App\Models\Transport\Truck', 'id', 'transport_id');
    }

    public function region()
    {
        return $this->hasOne('App\Models\Region', 'id', 'region_id');
    }

    public static function newRoute(Request $request)
    {
        $post = $request->all();
        return self::store($post, self::class);
    }
}
