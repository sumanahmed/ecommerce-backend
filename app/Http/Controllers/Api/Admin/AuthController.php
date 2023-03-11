<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\{ Auth, Validator };

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $data['token']   =  $user->createToken('MyApp')->accessToken;
        $data['name']    =  $user->name;
        $data['token_type'] =  'Bearer';
     
        return response([
            'success'  => true,
            'data'     => $data
        ]);
    }

    public function login(Request $request)
    {  
        $credentials = request(['email','password']);
        $authenticate = Auth::attempt($credentials);

        if($authenticate) { 
            $user = Auth::user(); 
            
            $data['token'] =  $user->createToken('MyAppToken')->accessToken; 
            $data['name'] =  $user->name;
            $data['token_type'] =  'Bearer';
            
            return response([
                'success'   => true,
                'data'      => $data
            ]);

        }
        
        return response([
            'success'   => false,
            'message'   => 'Unauthorised!!'
        ]);
        
    }
}
