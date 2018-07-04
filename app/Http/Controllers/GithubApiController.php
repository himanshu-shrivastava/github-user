<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class GithubApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('github.user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $user_name)
    {
        if($request->ajax())
        {
            $response               =   [];
            if($user_name)
            {
                $gitlab_user_data   =   $this->doGithubApiCall($user_name);

                if(empty($gitlab_user_data) || (isset($gitlab_user_data->message) && $gitlab_user_data->message == 'Not Found'))
                {
                    $response['status'] =    'error';
                    $response['message']=    'Requested user is not found!!!';
                }
                else
                {
                    $response['status'] =    'success';
                    $response['data'][] =    $gitlab_user_data;
                }
            }
            else if($request->get('local_storage'))
            {
                $storage_array      =   [];
                $storage_values     =   explode(',', $request->get('local_storage'));
                foreach ($storage_values as $storage_value) {
                    $result         =   $this->doGithubApiCall($storage_value);
                    if(!empty($result) && (isset($result->login)))
                    {
                        $storage_array[]    =   $result;
                    }
                }
                if(count($storage_array)){
                    $response           =   [
                                                'status'    =>  'success',
                                                'data'      =>  $storage_array
                                            ];
                }
            }

            return $response;
        }
        else if(!empty($request->get('tab')) && $request->get('tab') == 'card')
        {
            $gitlab_user_data       =   $this->doGithubApiCall($user_name);

            if(!isset($gitlab_user_data->message))
            {
                $followers          =   $this->doGithubApiCall($user_name.'/followers');
                $repos              =   $this->doGithubApiCall($user_name.'/repos');

                $data               =   [
                                            'user_login'    =>  $gitlab_user_data->login,
                                            'user_followers'=>  $followers,
                                            'user_repos'    =>  $repos
                                        ];
                
                return view('github.user_card', $data);
            }
        }
            
        return redirect('api/users');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function doGithubApiCall($action)
    {
        return  Curl::to(env('GITHUB_API') . $action)
                    ->withHeader('Authorization: token ' . env('GITHUB_TOKEN'))
                    ->withHeader('Accept: application/vnd.github.v3+json')
                    ->withHeader('User-Agent: ' . env('GITHUB_USER_AGENT'))
                    ->asJsonResponse()
                    // ->withOption('TIMEOUT', 60000)
                    ->get();
    }
}