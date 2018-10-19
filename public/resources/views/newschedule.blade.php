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
                    <label for="couriers">Курьер</label>
                    <select class="form-control"
                                name="couriers"
                                id="couriers">
                        <option value="1">Андрей Петров </option>
                        <option value="10">Владимир Волков </option>
                    </select>
                </div>
                <br>
                <br>
                <div class="form-group">
                    <label for="regions">Регион</label>
                    <select class="form-control"
                                name="regions"
                                id="regions">
                        <option value="1">Санкт-Петербург </option>
                        <option value="10">Астрахань </option>
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
                    <input id="time" type="time" name="time">
                </div>
                <input type="hidden"
                       name="hidden_id"
                       value=" ">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection


