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
	$user->password = md5($data['password']);
	if(App\User::count() <= 1){
    	$user->role = "ADMIN";
    }
    $user->generateToken();
	$user->save();
	$rs['token'] = $user->api_token;
	$rs['messages'] = 'success';
	return json_encode($rs);
});

Route::post('/login', function (Request $request) {
	$data = $request->all();
	$rs['messages'] = 'failed';
	$rs['user']	= null;
	if(!isset($data['email']) || !isset($data['password'])){
		return json_encode($rs);
	}
	$user = App\User::where('email', $data['email'])->where('password', md5($data['password']))->first();
	if(!$user){
		$rs['messages'] = 'wrong information';
	}else{
		$user->generateToken();
		$user->save();
		$rs['messages'] = 'success';	
		$rs['user']	= $user;
	}
	return json_encode($rs);
})->name('login');

Route::middleware('checkAuthToken')->group(function () { //required "token" parameter
    Route::get('/listUser', function (Request $request) {	
    	$auth = $request->instance()->query('auth');
    	if($auth['role'] == 'ADMIN'){
    		$users = App\User::all();	
    	}else{
    		$users = App\User::where('role', 'USER')->get()->limit(100);
    	}		

		if(!$users){
			$rs['messages'] = 'no user found';
			$rs['users'] = null;
		}else{
			$rs['messages'] = 'success';
			$rs['users'] = $users;
		}
		return json_encode($rs);
	}); //api get list Users

	Route::get('/user/{id}', function ($id = 0, Request $request) {	
		if(intval($id) > 0){
	    	$user = App\User::where('id', $id)->select('id','name','email')->first();	    	
			if(!$user){
				$rs['messages'] = 'no user found';
				$rs['user'] = null;
			}else{
				$rs['user'] = $user;
			}
			return json_encode($rs);
		}else{
			return response('No Id found', 404);
		}
    	
	})->middleware('checkRole'); //api get user by id for admin
});


Route::get('/test', function () {
	echo 'Hello User';    
});

