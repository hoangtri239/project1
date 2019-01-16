<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', function (Request $request) {
	$rules = [
        'name' => 'required|max:255',
        'email' => 'required|unique:users|max:255|email',
        'password' => 'required|max:255'
    ];
    $messages = [
	    'required' => 'The :attribute field is required.',
	];
	$data = $request->all();
	$validator = Validator::make($data, $rules, $messages);
    if($validator->fails()){
    	$rs = $validator->errors();
    	return json_encode($rs);
    }

	$user = new App\User;
	$user->name = $data['name'];
	$user->email = $data['email'];
	$user->password = $data['password'];
	$user->generateToken();
	$user->save();
	$rs['token'] = $user->api_token;
	$rs['messages'] = 'success';
	return json_encode($rs);
});

Route::post('/login', function (Request $request) {
	$data = $request->all();
	$rs['messages'] = 'failed';
	if(!isset($data['email']) || !isset($data['password'])){
		return json_encode($rs);
	}
	$user = App\User::where('email', $data['email'])->where('password', $data['password'])->first();
	if(!$user){
		$rs['messages'] = 'wrong information';
	}else{
		$user->generateToken();
		$user->save();
		$rs['messages'] = 'success';	
		$rs['user']	= $user;
	}
	return json_encode($rs);
});

Route::middleware('role')->group(function () {
    Route::get('/listUser', function () {	
		$user = App\User::all();
		if(!$user){
			$rs['messages'] = 'no user found';
		}else{
			$rs['list'] = $user;
		}
		return json_encode($rs);
	});
});


Route::get('/test', function () {
	echo 'Hello User';    
});

