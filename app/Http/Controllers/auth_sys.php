<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class auth_sys extends Controller
{
    
    public function login(){
        return view('auth.login');
    }

    public function customLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            //get the user data
            $user = Auth::user();
            //start regen the token
            $request->session()->regenerate();

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }
        return response()->json([
            'error' => 'invalid email or passwd',
        ]);
    }

    public function registeration()
    {
        return view('auth.registeration');
    }
      

    public function customRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
        
        return response()->json(['status' => 'success',]);
        //return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    

    public function dashboard()
    {

        $user = Auth::user();
        return response()->json(['status'=>'welcome','name'=>$user->name]);
    }
    

    public function signOut(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
  
        return Redirect('login');
    }
}
