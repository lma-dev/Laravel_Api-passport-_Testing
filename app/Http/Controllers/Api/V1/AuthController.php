<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $user= new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->save();

        $token = $user->createToken('Api Testing')->accessToken;
        return response()->json([
            'result' => 1,
            'message' =>'success',
            'token' =>  $token
        ]);
    }

    public function login(Request $request){
            $data = $request->only('email','password');
            if(Auth::attempt($data)){
               $user =  Auth::user();

               $token = $user->createToken('Api Testing')->accessToken;
               return response()->json([
                'result' => 1,
                'data' =>  $user,
                'message' =>'success',
                'token' =>  $token
            ]);
            }else{
                return response()->json([
                    'result' => 0,
                    'message' =>'fail',

                ]);
            }
    }

        public function profile()
        {
                $user =Auth::user();
                $data = new UserResource($user);
                return response()->json([
                    'result' => 1,
                    'message' => 'success',
                    'data' => $data
                ]);
                    }

                    public function logout(){
                        Auth::user()->token()->revoke();
                        return response()->json([
                            'message' => 'log out successfully',
                            'result' => 1,

                        ]);
                    }
    }
