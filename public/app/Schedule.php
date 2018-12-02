<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public function courier() {

        return $this->hasOne('App\Courier', 'id', 'courier_id');
    }

    public function region() {

        return $this->hasOne('App\Region', 'id', 'region_id');
    }
}
