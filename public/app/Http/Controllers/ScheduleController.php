<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Schedule\Schedule;
use App\Models\Region;
use App\Models\Transport\Bus;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Cache;

class ScheduleController extends Controller
{
    public $data;

    public function index()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $key = 'schedule-page-' . $currentPage;

        $data = Cache::remember($key, 1, function () {
            return Schedule::paginate(10);
        });

        $view = view('schedule', ['items' => $data])->render();
        return (new Response($view));
    }
    public function add()
    {
        $buses = Bus::all();
        $regions = Region::all();

        $view = view('newschedule', ['buses' => $buses,
            'regions' => $regions])->render();
        return (new Response($view));
    }

    public function reverse()
    {
        $this->data['date'] = $this->shiftTime($this->data['date'], $this->data['interval']);
        $this->data['regions'] = 0;
        return $this->addSchedule();
    }
    public function store(Request $request)
    {
        $post = $request->all();
        $region = Region::find($post['regions']);
        $this->data['interval'] = $region->distance;
        $this->data['regions'] = $post['regions'];
        $this->data['couriers'] = $post['couriers'];
        $this->data['date'] =  $post['date'] . ' ' . $post['time'];
        $toDb = array_merge($this->addSchedule(), $this->reverse());
        foreach ($toDb as $route) {
            if ($route[0] == 'false') {
                $html = 'Транспорт занят!';
                $view = view('result', ['html' => $html])->render();

                return (new Response($view));
            }
        }
        foreach ($toDb as $route) {
            $this->addRoute($route[1], $route[2], $route[3]);
        }
        $html = 'Данные записаны!';
        $view = view('result', ['html' => $html])->render();

        return (new Response($view));
    }
    public function addRoute($date, $interval, $region)
    {
//        $item = new Schedule;
//        $item->date = $date;
//        $item->interval = $interval;
//        $item->region_id = $region;
//        $item->courier_id =  $this->data['couriers'];
//        $item->save();
    }
    public function addSchedule()
    {
        $toDb = [];
        $hours = $this->getHours($this->data['date']);
        if ($this->data['interval'] > (24 - $hours)) {
            $todayInterval = 24 - $hours;
            $toDb[] = $this->checkRoute($this->data['date'],
                $todayInterval,
                $this->data['regions']);
            $toDb[] = $this->addNightRoute(
                $this->data['interval']
            );
        } else {
            $toDb[] = $this->checkRoute(
                $this->data['date'],
                $this->data['interval'],
                $this->data['regions']
            );
        }
        return $toDb;
    }
    private function getHours($dateTime)
    {
        $date = new DateTime($dateTime);
        return $date->format('H');
    }
    private function checkRoute($date, $interval, $region)
    {
        $newDate = new DateTime($date);
        $day = $newDate->format('d');
        $month = $newDate->format('m');
        $year = $newDate->format('Y');
        $query = Schedule::whereMonth('date', $month)
            ->whereDay('date', $day)
            ->whereYear('date', $year)
            ->where('courier_id', '=', $this->data['couriers'])
            ->get();
        if (count($query) != 0) {
            foreach($query as $item){
                $queryDate = $item->date;
            }
            $hoursToAdd = $this->getHours($date);
            $hoursFromQuery = $this->getHours($queryDate);
            if (($hoursToAdd + $interval) >= $hoursFromQuery) {
                return ['false'];
            } else {
                return ['true', $date, $interval, $region];
            }
        } else {
            return ['true',$date, $interval, $region];
        }
    }
    private function addNightRoute($interval)
    {
        $hours = $this->getHours($this->data['date']);
        $todayInterval = 24 - $hours;
        $tommorrowInterval = $interval - $todayInterval;
        $tomorrow = $this->shiftTime($this->data['date'],
            $todayInterval);
        $tommorrowDatetime = $tomorrow;
        return $this->checkRoute($tommorrowDatetime,
            $tommorrowInterval,
            $this->data['regions']);
    }
    protected function shiftTime($date, $shift)
    {
        $shiftTime = new DateTime($date);
        $shiftTime->add(new DateInterval("PT{$shift}H"));
        return $shiftTime->format('Y-m-d H:i');
    }
}

