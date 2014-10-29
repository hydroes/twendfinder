@extends('master')

@section('content')
    <h1>Welcome!</h1>
    <h3>Below is a live feed of the worlds tweets about love, hate, and other emotions</h3>
    <p>Tweets counted so far: <span>@{{counter}}</span></p>

    <p ng-controller="StatsCtrl" ng-init="initialize()">
        Tweets counted in the last:
        <span class="label label-info active">Minute <span class="badge badge-important">@{{cur_min}}</span></span>
        <span class="label label-info active">Hour <span class="badge badge-important">@{{cur_hour}}</span></span>
        <span class="label label-info active">24 hours <span class="badge badge-important">@{{cur_day}}</span></span>
    </p>

    <div ng-controller="TweetsCtrl">
        <button ng-click="toggleStreamFlow()" ng-hide="streamFlowPaused" class="btn btn-primary">Pause feed</button>
        <button ng-click="toggleStreamFlow()" ng-show="streamFlowPaused" class="btn btn-success">Un-pause feed</button>
    </div>

    <table ng-controller="TweetsCtrl" id="tweets" class="table table-hover">
        <tr ng-repeat="status in statuses | limitTo:-10 track by $index">
            <td>
                <img src="@{{status.profile_pic}}" class="img-rounded" />
            </td>
            <td>
                <div>
                    <a href="https://twitter.com/@{{status.screen_name}}" target="_blank">@{{status.screen_name}}</a>
                </div>
                <div>@{{status.text}}</div>
            </td>
        </tr>
    </table>
@endsection

