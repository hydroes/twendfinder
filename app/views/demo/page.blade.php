@extends('master')

@section('content')
<h1>Welcome!</h1>
<p>Welcome to Brian's web page!</p>

{{ Form::open(array('action' => 'HomeController@showSnapShot')) }}
{{ Form::text('search_term', 'laravel') }}

{{Form::submit('Rate me!')}}
{{Form::token()}}

{{ Form::close() }}

@endsection

