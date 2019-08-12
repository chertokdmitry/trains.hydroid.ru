<?php


namespace App\Models\Schedule;

use App\Models\Region;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected static function store($post, $className)
    {
        $data = [];
        $region = Region::find($post['region_id']);
        $data['interval'] = $region->distance;
        $data['region_id'] = $post['region_id'];
        $data['transport_id'] = $post['transport_id'];
        $data['date'] =  $post['date'] . ' ' . $post['time'];

        $toDb = array_merge(self::addSchedule($data), self::reverse($data));
        foreach ($toDb as $route) {
            if ($route[0] == 'false') {
                $html = 'Транспорт занят!';

                return $html;
            }
        }
        foreach ($toDb as $route) {
            self::addRoute($route[1], $route[2], $route[3], $data, $className);
        }
        $html = 'Данные записаны!';

        return $html;
    }

    private static function addRoute($date, $interval, $region, $data, $classname)
    {
        $item = new $classname;
        $item->date = $date;
        $item->interval = $interval;
        $item->region_id = $region;
        $item->transport_id =  $data['transport_id'];
        $item->save();
    }

    private static function addSchedule($data)
    {
        $toDb = [];
        $hours = self::getHours($data['date']);

        if ($data['interval'] > (24 - $hours)) {
            $todayInterval = 24 - $hours;
            $toDb[] = self::checkRoute($data['date'], $todayInterval, $data['region_id'], $data);
            $toDb[] = self::addNightRoute($data['interval'], $data);
        } else {
            $toDb[] = self::checkRoute($data['date'], $data['interval'], $data['region_id'], $data);
        }

        return $toDb;
    }

    private static function getHours($dateTime)
    {
        $date = new DateTime($dateTime);

        return $date->format('H');
    }

    private static function checkRoute($date, $interval, $region, $data)
    {
        $newDate = new DateTime($date);
        $day = $newDate->format('d');
        $month = $newDate->format('m');
        $year = $newDate->format('Y');
        $query = BusSchedule::whereMonth('date', $month)
            ->whereDay('date', $day)
            ->whereYear('date', $year)
            ->where('transport_id', '=', $data['transport_id'])
            ->get();

        if (count($query) != 0) {
            foreach ($query as $item) {
                $queryDate = $item->date;
            }
            $hoursToAdd = self::getHours($date);
            $hoursFromQuery = self::getHours($queryDate);

            if (($hoursToAdd + $interval) >= $hoursFromQuery) {
                return ['false'];
            } else {
                return ['true', $date, $interval, $region];
            }
        } else {
            return ['true', $date, $interval, $region];
        }
    }

    private static function addNightRoute($interval, $data)
    {
        $hours = self::getHours($data['date']);
        $todayInterval = 24 - $hours;
        $tommorrowInterval = $interval - $todayInterval;
        $tomorrow = self::shiftTime($data['date'], $todayInterval);
        $tommorrowDatetime = $tomorrow;

        return self::checkRoute($tommorrowDatetime, $tommorrowInterval, $data['region_id'], $data);
    }

    private static function shiftTime($date, $shift)
    {
        $shiftTime = new DateTime($date);
        $shiftTime->add(new DateInterval("PT{$shift}H"));

        return $shiftTime->format('Y-m-d H:i');
    }

    private static function reverse($data)
    {
        $data['date'] = self::shiftTime($data['date'], $data['interval']);
        $data['region_id'] = 0;

        return self::addSchedule($data);
    }
}
