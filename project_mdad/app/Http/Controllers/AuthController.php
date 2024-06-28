<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    public function index(){
        $users = User::all();
        return response()->json([
            'data' => $users,
            'msg' => 'Successfully',
            'status'=> 200
        ]);
    }
    public function get_user($id)
    {
        $task = User::find($id);
        if (!$task) {
            return response()->json([
                'data' => null,
                'msg' => 'Task not found',
                'status' => 404
            ]);
        }
        $task->task;
        $task-> task_comment;
        $task->project_comment;
        return response()->json([
            'data' => $task,
            'msg' => ' Successfully',
            'status'=> 200
        ]);
    }
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "name"=> "string|required",
            "email"=> "required|email|unique:users",
            "password"=> "min:3|required:users",
        ],
        [
            // الرسائل التي ستعرض للمستخدم اذا شلناها بيعرض لحاله رسائل افتراضية
            'name.required' => 'name is required',
            'email.email'=> 'email is contain @',
        ]
    );
        if($validate->fails()){
            return response()->json([
                "error"=> $validate->errors(),
                "status" => 400
            ]);
        }

        $user = User::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "password"=> Hash::make($request->password),
            "role"=> $request->role,
            // 'remember_token' => Str::random(80), // Generate token
        ]);
        $token = $user->createToken("register_token")->plainTextToken;

        return response()->json([
            'data' => $user,
            'token' => $token,
            'msg' => 'Create user Successfully',
            'status'=> 200
        ]);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'email' => 'required|email:users',
            'password' => 'min:3|required:users',
        ]);
        // fails = false  or true
        if($validate->fails())
        {
            return response()->json([
                'erorr' => $validate->errors(),
                'status'=> 400
            ]);
        }
        // check email
        $user = User::where('email' , $request->email)->first();
        // check password
        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken("login_token")->plainTextToken;
            // respose is true
            return response()->json([
                'data'=> 'go to home page',
                'token' => $token,
                'msg' => 'login user successfully',
                'status' => 200,
            ]);
        }
        // response is false
        return response()->json([
            'msg' => 'login user faild',
            'status' => 403
        ]);
    }

    public function destroy($id)
    {
        $task = User::find($id);
        if ($task)
        {
            $task->delete();

            return  response()->json([
                'data' => $task,
                'msg'=>'Access',
                'status'=> 200
            ]);
        }
        else{
            return response()->json([
                'msg' => 'This a User is Not Found',
                'status'=> 404
            ]);
        }
    }

    public function update(Request $request,$id)
    {
        $validate = Validator::make($request->all(),[
            "name"=> "string|required",
            "email"=> "required|email|unique:users",
            "password"=> "min:3|required:users",
        ],
    );
        if($validate->fails()){
            return response()->json([
                "error"=> $validate->errors(),
                "status" => 400
            ]);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "error" => "Task not found",
                "status" => 404
            ]);
        }

            $user->name = $request->name;
            $user->email =  $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

        $token = $user->createToken("register_token")->plainTextToken;

        return response()->json([
            'data' => $user,
            'token' => $token,
            'msg' => 'Update user Successfully',
            'status'=> 200
        ]);
    }
}

