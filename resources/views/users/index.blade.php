@extends('layouts.default')

@section('title', '所有用户')

@section('content')

<div class="col-md-offset-2 col-md-8">
  <h1>所有用户</h1>
  <ul class="users">
    @if (count($users) > 0)
        @foreach ($users as $user)
            @include('users._user')
        @endforeach
    @endif
  </ul>

  {{ $users->links() }}

</div>    

@endsection
