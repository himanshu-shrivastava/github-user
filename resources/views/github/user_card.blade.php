@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="background-color: #f5f8fa;">
            <div class="col-md-10 col-md-offset-2">
                <div class="panel panel-default" style="padding-top: 5%;">
                    <div class="panel-heading">
                        <h3>{{$user_login}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default" style="padding-top: 30%;float: right;">
                    <div class="panel-heading">
                        <a href="{{url('api/users')}}/">Home</a>
                    </div>
                </div>
            </div>
        </div>
        <h1></h1>
        <div class="row user-detail" style="display: block;">
            <div class="row" style="padding: 15px;">
                <div class="col-md-5 col-md-offset-5" style="background-color: #f5f8fa;padding-top: 10px;padding-bottom:5px;">
                    <h4 class="user-title" style="text-decoration:underline;">Followers List</h4>
                    <ul class="{{ (count($user_followers)) ? '' : 'list-unstyled' }}">
                        @forelse($user_followers as $user_follower)
                            <li><a target="_blank" href="{{$user_follower->html_url}}">{{$user_follower->login}}</a></li>
                        @empty
                            <li>No follower found</li>
                        @endforelse
                    </ul>
                </div>
                <div class="col-md-2 col-md-offset-2" style="background-color: white;"></div>
                <div class="col-md-5 col-md-offset-5" style="background-color: #f5f8fa;padding-top: 10px;padding-bottom:5px;">
                    <h4 class="user-title" style="text-decoration:underline;">Repository List</h4>
                    <ul class="{{ (count($user_repos)) ? '' : 'list-unstyled' }}">
                        @forelse($user_repos as $user_repo)
                            <li><a target="_blank" href="{{$user_repo->html_url}}">{{$user_repo->name}}</a></li>
                        @empty
                            <li>No repository found</li>
                        @endforelse
                    </ul>
                </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            //
        });
    </script>
@endsection