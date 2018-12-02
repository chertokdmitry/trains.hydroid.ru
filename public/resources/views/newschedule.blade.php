@extends('layouts.app')

@section('content')
    <div>
        <div>
            <h3>Новый маршрут</h3>
        </div>
        <div>
            <form action="/newroute" method="post">
                @csrf
                <div class="form-group">
                    <label for="couriers">Курьеры</label>
                    <select id="couriers" name="couriers" class="form-control">
                        @foreach ($couriers as $courier)
                                <option value="{{ $courier['id'] }}">
                                    {{ $courier['last'] }}
                                    {{ $courier['first'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="defaultRegion">Отправление</label>
                    <input type="text" id="defaultRegion" class="form-control" placeholder="Москва" disabled>
                </div>
                <div class="form-group">
                    <label for="regions">Пункт назначения</label>
                    <select id="regions" name="regions" class="form-control">
                        @foreach ($regions as $region)
                            @if ($region['id'] != 0)
                            <option value="{{ $region['id'] }}">{{ $region['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Дата</label>
                    <input type="date"
                               class="form-control"
                               id="date"  name="date"
                               placeholder=" date">
                </div>
                <div class="form-group">
                    <label for="time">Время: </label>
                    <input id="time" type="time" name="time" class="form-control">
                </div>
                <input type="hidden"
                       name="hidden_id"
                       value=" ">
                <button type="submit" class="btn btn-lg btn-primary btn-block">Добавить</button>
            </form>
        </div>
    </div>
@endsection


