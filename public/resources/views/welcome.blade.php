@extends('layouts.app')

@section('content')
    <div>
        <div>
            <h3>Показать расписание</h3>
        </div>
        <div>
            <form action="/" method="post">
                @csrf
                <div class="form-group">
                    <label for="date1">С </label>
                    <input type="date"
                           class="form-control"
                           id="date1"
                           name="date1"
                           placeholder=" date1">
                    <br>
                    <br>
                </div>
                <div class="form-group">
                    <label for="date2">по </label>
                    <input type="date"
                           class="form-control"
                           id="date2"
                           name="date2"
                           placeholder=" date2">
                    <br>
                    <br>
                </div>
                <input type="hidden"
                       name="hidden_id"
                       value="0">
                <button type="submit"
                        class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
