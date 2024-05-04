<?php

namespace App\Controllers;

class Register extends BaseController{

	public function index()
	{
        
		if (session()->has('user_id')) {
			return redirect()->to('dashboard');
		} else {
			return view('register_view');
		}
		
	}

}