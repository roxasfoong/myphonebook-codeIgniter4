<?php

namespace App\Controllers;

class Login extends BaseController{


	public function index()
	{
		if (session()->has('user_id')) {
			return redirect()->to('dashboard');
		} else {
			return view('login_view');
		}
	}

	public function info(){
		return view('info_view');
	}


}