<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ApiUserController extends Controller
{
     /**
     * handle user registration request
     */
    public function registerUser(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);
        $user= User::create([
            'name' =>$request->name,
            'avater_url'=>$request->has('avater_url') ? $request->avater_url : 'default/avater.jpg',
            'cover_url'=>$request->has('cover_url') ? $request->cover_url : 'default/cover.jpg',
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        $access_token_example = $user->createToken('PassportExample@Section.io')->access_token;
        //return the access token we generated in the above step
        return response()->json(['token'=>$access_token_example],200);
    }

    /**
     * login user to our application
     */
    public function loginUser(Request $request){
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(auth()->attempt($login_credentials)){
            //generate the token for the user
            $user_login_token= auth()->user()->createToken('PassportExample@Section.io')->accessToken;
            //now return this token on success login attempt
            return response()->json(['token' => $user_login_token], 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }

    /**
     * This method returns authenticated user details
     */
    public function authenticatedUserDetails(){
        //returns details
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }
}
