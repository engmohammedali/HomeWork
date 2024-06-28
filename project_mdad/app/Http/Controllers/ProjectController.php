<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller

{
    //
    public function index()
    {
        $prodect = Project::all();
        return response()->json([
            'data' => $prodect,
            'msg' => 'Create user Successfully',
            'status' => 200
        ]);
    }



    public function get_project($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json([
                'data' => null,
                'msg' => 'Task not found',
                'status' => 404
            ]);
        }
        $project->task;
        $project->project_comment;
        return response()->json([
            'data' => $project,
            'msg' => ' Successfully',
            'status' => 200
        ]);
    }



    public function create(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "name"=> "string|required",
            "description"=> "string|min:8|required|unique:tasks",
        ]);

        if($validate->fails()){
            return response()->json([
                "error"=> $validate->errors(),
                "status" => 400
            ]);
        }
        if (Gate::allows('create', new User()))
         {
            $project = Project::create([
                "name"=> $request->name,
                "description"=> $request->description,
            ]);
            return response()->json([
                'data' => $project,
                'msg' => 'Create Prodect Successfully',
                'status'=> 200
            ]);
         }
        else{
            return response()->json([
                'error'=> $validate->errors(),
                'status'=> 400
                ]);
        }
    }




    public function addUser(Project $project, User $user)
    {
        $project = Project::find($project->id);
        $user = User::find($user->id);

        DB::table('user_project')->insert([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'status' => 'invited',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'User added successfully']);
    }





    public function destroy($id)
    {
        $prodect = Project::find($id);
        if ($prodect) {
            $prodect->delete();

            return  response()->json([
                'data' => $prodect,
                'msg' => 'Access',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'msg' => 'This a Prodect is Not Found',
                'status' => 404
            ]);
        }
    }





    public function update(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                "name" => "string|required",
                "description" => "string|min:8|required|unique:tasks",
            ],
        );
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
                "status" => 400
            ]);
        }

        $prodect = Project::find($id);
        if (!$prodect) {
            return response()->json([
                "error" => "Project not found",
                "status" => 404
            ]);
        }
        $prodect->name = $request->name;
        $prodect->description = $request->description;
        $prodect->save();

        return response()->json([
            'data' => $prodect,
            'msg' => 'Update Prodect Successfully',
            'status' => 200
        ]);
    }

}
