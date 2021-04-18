@extends('feedback::layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <h3>Dashboard de Clientes</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <table class="table table-freezed table-bordered">
                <thead>
                    <th>Nome</th>
                    <th>Link da Dashboard</th>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{$client->user_name}}</td>
                            <td><a href="{{_route('feedback.client.campaigns', $client->user_id)}}">{{_route('feedback.client.campaigns', $client->user_id)}}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$clients->links()}}
        </div>
    </div>
</div>

@endsection