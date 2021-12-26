<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function edit(){
        return view('admin.users.edit');
    }

    public function update(Request $request){

        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);

        $users = User::find($request->id);
        $users->name = $request->name;
        $users->email = $request->email;
        $users->save();
        Auth::logout();

        return redirect()->route('login')->with('massage','User Info successfull and Please Login again.');
    }

    public function change_password_form(){
        return view('admin.users.change-password');
    }

    public function change_password(Request $request){

        $this->validate($request,[

            'password'     => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',


        ]);

        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();
        Auth::logout();

        return redirect()->route('login')->with('massage','Password Change successfull and Please Login again.');

    }

    public function user_list(){

        $users = User::paginate(10);
        return view('admin.users.user-list',compact('users'));

    }

    public function add_new_user_form(){
        return view('admin.users.add-new-user');
    }

    public function add_new_user(Request $request){

        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = Auth::user()->company_id;
        $user->save();

        return redirect('users/list')->with('massage','User Added Successfull !');

    }

    public function edit_user_form($id){

        $user = User::find($id);
        return view('admin.users.edit-user',compact('user'));
    }

    public function edit_user(Request $request){

        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->company_id = Auth::user()->company_id;
        $user->save();

        return redirect('users/list')->with('massage','User Update Successfull !');


    }


    public function show_user($id){
        $user = User::find($id);
        return view('admin.users.user',compact('user'));
    }

    public function delete_user(Request $request){
        $user = User::find($request->id);
        $user->delete();
        return redirect('users/list')->with('massage','User Delete Successfull !');
    }

}
