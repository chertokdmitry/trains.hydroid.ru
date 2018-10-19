@extends('layouts.app')

@section('content')
    <div class="content">
        <div>
            <h3>Расписание</h3>
        </div>

        <div>
            <table class="table">
                <tr>
                    <th>id</th>
                    <th>Дата</th>
                    <th>Курьер(id)</th>
                    <th>Город(id)</th>
                    <th>В пути(ч)</th>
                </tr>
                @foreach ($items as $item)
                    <tr>
                        <td>
                            {{ $item->id }}
                        </td>
                        <td>
                            {{ $item->date }}
                        </td>
                        <td>
                            {{ $item->courier_id }}
                        </td>
                        <td>
                            {{ $item->region_id }}
                        </td>
                        <td>
                            {{ $item->interval }}
                        </td>
                    </tr> </p>
                @endforeach
            </table>
        </div>
    </div>
@endsection