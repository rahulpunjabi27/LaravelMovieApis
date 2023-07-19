<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function index(){
        return response()->json(User::latest()->get());
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required' ,'string', Password::min(8)
                                            ->mixedCase()
                                            ->letters()
                                            ->numbers()
                                            ->symbols()
            ]
        ]);
        if($validator->fails()){
            $response = [
                'code' => 401,
                'error' => true,
                'data' => $validator->messages()
            ];
            return response()->json($response);
        }
        
       $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password)
            ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'code' => 200,
            'success' => true,
            'data' => array($user,$token)
        ];
        return response()->json($response);

        
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required' ,'string', Password::min(8)
                                            ->mixedCase()
                                            ->letters()
                                            ->numbers()
                                            ->symbols()
            ]
        ]);
        if($validator->fails()){
            $response = [
                'code' => 401,
                'error' => true,
                'data' => $validator->messages()
            ];
            return response()->json($response);
        }

        $user = User::where('email' , $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            $response = [
                'code' => 401,
                'error' => true,
                'data' => array('message' => 'Email And Password Incorrect...!')
            ];
            return response()->json($response);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        
        $response = [
            'code' => 200,
            'success' => true,
            'data' => array($user,$token)
        ];
        return response()->json($response);

    }
}
