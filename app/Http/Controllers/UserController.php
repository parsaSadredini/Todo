<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\WebFramework\ApiResult;
use App\WebFramework\ApiResultWithData;
use App\WebFramework\Common\StandardApiResultStatusCode;
use App\WebFramework\Exception\NotFoundException;
use Dotenv\Exception\ValidationException;
use http\Env\Response;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserController extends Controller
{
    public function create(Request $request){
        $this->performValidation($request);
        $user = new User([
            "name"=>"$request->name",
            "email"=>"$request->username",
            "email_verified_at"=>now(),
            "password"=>Hash::make($request->password)
        ]);
        $user->save();
        return Response()->json(new ApiResult(true,StandardApiResultStatusCode::Succees));
    }

    public function login(Request $request){
         $user = User::where("email","=","$request->username")->first();
         if($user != null ){
             if(Hash::check($request->password,$user->password)){
                 $http = new \GuzzleHttp\Client;

                 $response = $http->post(env("token_server")."oauth/token", [
                     'form_params' => [
                         'grant_type' => 'password',
                         'client_id' => env("client_id"),
                         'client_secret' => env('client_secret'),
                         'password'=>"$request->password",
                         'username'=>"$request->username",
                         'scope' => '',
                     ],
                 ]);
                 return $response;
             }
             throw new BadRequestException();
         }
        throw new NotFoundException();

    }

    public function refresh_token(Request $request){
        //laravel recommended this one
        $response = Http::asForm()->post(env("token_server")."oauth/token", [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env("client_id"),
            'client_secret' => env('client_secret'),
            'scope' => ''
        ]);

        return $response->json();
    }
    private function performValidation($request)
    {
        $validator = Validator::make($request->toArray(), User::$validationRules);
        if ($validator->fails())
            throw new ValidationException($validator);
    }

}
