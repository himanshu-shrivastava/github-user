@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="background-color: #f5f8fa;">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default" style="padding-top: 3%;">
                    <div class="panel-heading">
                        <div style="display: inline-block;padding-left: 0%;" class="col-md-10 col-md-offset-2">
                            <h3>Find Github User</h3>
                        </div>
                        <span class="col-md-2 col-md-offset-2" style="">
                            <button type="button" id="clear-gitlab-search" class="btn btn-deltfau">Clear Search</button>
                        </span>
                        <div>
                            <form method="GET" action="#">
                                <!-- COMPONENT START -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" id="github_user" class="form-control col-md-4" placeholder="Enter Github User Name" autocomplete="on"/>
                                        <div class="input-group-btn" style="padding-left:10px;">
                                            <button type="submit" id="add-gitlab-user" class="btn btn-primary">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h1></h1>
        <div class="row user-detail" style="display: none;"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function()
        {
            if(typeof(Storage) !== "undefined" && localStorage.github_input_user)
            {
                getGitlabUserDetail(0);
            }

            $('.user-detail').hide();

            $("#add-gitlab-user").click(function()
            {
                var input_user  =   $("#github_user").val();
                if(input_user == null || input_user == 'undefined' || input_user == '')
                {
                    swal('Please insert github user name', '', 'error');
                    return false;
                }
                else
                {
                    getGitlabUserDetail(input_user);
                }
            });

            $("#clear-gitlab-search").click(function()
            {
                localStorage.github_input_user = '';
                $('.user-detail').html('');
            });

            function getGitlabUserDetail(input_user)
            {
                $.ajax({
                    url     : "{{url('api/users')}}/" + input_user,
                    type    : "get",
                    cache   : false,
                    async   : true,
                    data    : {'local_storage' : localStorage.github_input_user},
                    success : function(response, status, xhr)
                    {
                        if(response.status == 'error')
                        {
                            swal(response.message, '', 'error');
                        }
                        else
                        {
                            user_data = response.data;
                            $.each( user_data, function( index, value )
                            {
                                $('#'+value.login).remove();

                                var detail = '';
                                detail  =  '<div class="user-list" id="'+value.login+'">';
                                detail +=  '<h3 class="user-title"><a target="_blank" href="'+value.html_url+'">'+value.login+'</a></h3>';
                                detail +=  '<a target="_blank" href="/api/users/' + value.login + '?tab=card" class="user-card" style="text-decoration: none;color: black;">';
                                detail +=  '<ul class="list-unstyled">';
                                detail +=  '<li><span class="pull-right">Name :</span>'+value.name+'</li>';
                                detail +=  '<li><span class="pull-right">Company :</span>'+((value.company)?value.company:"-")+'</li>';
                                detail +=  '<li><span class="pull-right">Location :</span>'+((value.location)?value.location:"-")+'</li>';
                                detail +=  '<li><span class="pull-right">Email :</span>'+((value.email)?value.email:"-")+'</li>';
                                detail +=  '<div class="user-list-separator"></div>';
                                detail +=  '<li><span class="pull-right">Follewers :</span>'+value.followers+'</li>';
                                detail +=  '<li><span class="pull-right">Following :</span>'+value.following+'</li>';
                                detail +=  '<li><span class="pull-right">Member Since :</span>'+((value.created_at).split('T')[0])+'</li>';
                                detail +=  '<li><span class="pull-right">Public Repos :</span>'+value.public_repos+'</li>';
                                detail +=  '<li><span class="pull-right">Public Gists :</span>'+value.public_gists+'</li>';
                                detail +=  '</ul></a></div>';

                                $('.user-detail').prepend(detail).show();
                            });

                            $("#github_user").val('');

                            if(typeof(Storage) !== "undefined" && input_user != 0)
                            {
                                if(localStorage.github_input_user)
                                    localStorage.github_input_user  =   localStorage.github_input_user + ',' + input_user;
                                else
                                    localStorage.github_input_user  =   input_user;

                                var user_list                       =   localStorage.github_input_user;
                                var user_arr                        =   (user_list.split(',')).filter(function(item, pos, self){
                                                                            return self.indexOf(item) == pos;
                                                                        });

                                localStorage.github_input_user      =   user_arr.toString();
                            }
                            else
                            {
                                console.log("Sorry, your browser does not support web storage!!!");
                            }
                        }
                    },
                    error: function(data)
                    {
                        console.log(data);
                        swal('Something went wrong, please refresh the page!!!', '', 'error');
                    }
                });
            }
        });
    </script>
@endsection