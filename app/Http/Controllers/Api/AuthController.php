<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StatusCodeRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;

/*
    Status Request
        200: success
        400: Validation error
        401: Unauthorized
 */

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails())
            return $this->apiResponse(null, $validator->errors(),StatusCodeRequest::INVALIDATION);

        $user = User::create(array_merge($validator->validated(),
            ['password' => bcrypt($request->password)],
        ));
        if (!$token = auth()->attempt($validator->validated()))
            return $this->apiResponse(null, 'Unauthorized',StatusCodeRequest::UNAUTHORISED);
        $user->token=$token;
        $user = ['user'=>$user];
        return $this->apiResponse($user,'success',StatusCodeRequest::CREATED);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
            return $this->apiResponse(null, $validator->errors(),StatusCodeRequest::INVALIDATION);

        if (! $token = auth()->attempt($validator->validated()))
            return $this->apiResponse(null, 'Unauthorized',StatusCodeRequest::UNAUTHORISED);
        User::where('id',auth()->user()->id)->update([
            'remember_token'=>$token
        ]);
        $user=auth()->user();
        return $this->apiResponse($user,'success',StatusCodeRequest::SUCCESS);
    }

    public function logout() {
        auth()->logout();
        return $this->apiResponse(null,'success',StatusCodeRequest::SUCCESS);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

}
