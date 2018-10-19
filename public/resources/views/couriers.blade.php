@extends('layouts.app')

@section('content')
    <div>
        <div>
            <h3>Курьеры</h3>
        </div>
        <div>
            <table class="table">
                <tr>
                    <th>id</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                </tr>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            {{ $item->first }}
                        </td>
                        <td>
                            {{ $item->last }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection