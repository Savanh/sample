<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

class UserController extends Controller
{
	public function create(){
		return view('users.create');
	}

	public function show(User $user){
		//var_dump($user);

		return view('users.show',compact('user'));
	}

	public function store(Request $request){
	
		$this->validate($request,[
			'name' =>'required|max:50',
			'email' =>'required|email|unique:users|max:255',
			'password' =>'required|confirmed|min:6'
		]);
		return;
	}
}
