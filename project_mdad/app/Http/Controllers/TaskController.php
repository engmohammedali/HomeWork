<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\TaskComment;




class TaskController extends Controller
{
    // Get All Tasks
    public function index()
    {
        $tasks = Task::with(['project', 'user', 'task_comment'])->get();

    return response()->json([
        'data' => $tasks,
        'msg' => 'Tasks retrieved successfully',
        'status' => 200
    ]);
    }
    // Get Task Using id
    public function index2($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'data' => null,
                'msg' => 'Task not found',
                'status' => 404
            ], 404);
        }
        $task->project;
        $task->user;
        $task-> task_comment;
        return response()->json([
            'data' => $task,
            'msg' => 'Create user Successfully',
            'status'=> 200
        ]);
    }
    // singin
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "user_id"=> "integer|max:10|required:tasks",
            "project_id"=> "integer|max:10|required:tasks",
            "title"=> "string|required|unique:tasks",
            "description"=> "string|min:8|required|unique:tasks",
            'status' => 'required',
            'due_date' => 'required|date|date_format:Y-m-d',
            
        ],
        );
        if($validate->fails())
        {
            return response()->json([
                "error"=> $validate->errors(),
                "status" => 400
            ]);
        }
        if($request->status <> "queue" and $request->status <> "inprogress" and $request->status <> "done")
        {
            return response()->json([
                "error"=> "status != queue or inprogress or done",
                "status"=> 400
            ]);
        }

        $userExists = User::where('id', $request->user_id)->exists();
        $projectExists = Project::where('id', $request->project_id)->exists();

        if (!$userExists || !$projectExists) {
            return response()->json([
                "error" => "User or Project not found",
                "status" => 404
            ]);
        }

            $task = Task::create([
            "user_id"=> $request->user_id,
            "project_id"=> $request->project_id,
            "title"=> $request->title,
            "description"=> $request->description,
            "status"=> $request->status,
            "due_date"=> $request->project_id,
            ]);
        return response()->json([
            'data' => $task,
            'msg' => 'Create user Successfully',
            'status'=> 200
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task)
        {
            // $userExists = TaskComment::where('id', $id)->exists();
            // if($userExists)
            // {
            //     return response()->json([
            //         "error" => "This table cannot be deleted because it is related to a variety",
            //         "status" => 404
            //     ]);
            // }
            $task->delete();

            return  response()->json([
                'data' => $task,
                'msg'=> 'Access',
                'status'=>200
            ]);

        }
        else{
            return response()->json([
                'msg' => 'This a Task is Not Found',
                'status'=> 404
            ]);
        }
    }

    public function update(Request $request, $id)
    {
    // Validate the incoming request data
    $validate = Validator::make($request->all(), [
        "user_id"=> "integer|max:10|required",
        "project_id"=> "integer|max:10|required",
        "title"=> "string|required:tasks",
        "description"=> "string|min:8|required:tasks",
        'due_date' => 'required|date|date_format:Y-m-d',
    ]);

    if ($validate->fails()) {
        return response()->json([
            "error" => $validate->errors(),
            "status" => 400
        ]);
    }

    // Check if the task exists
    $task = Task::find($id);
    if (!$task) {
        return response()->json([
            "error" => "Task not found",
            "status" => 404
        ]);
    }
    if($request->status <> "queue" and $request->status <> "inprogress" and $request->status <> "done")
        {
            return response()->json([
                "error"=> "status != queue or inprogress or done",
                "status"=> 400
            ]);
        }
    // Check if user_id and project_id exist in their respective tables
    $userExists = User::where('id', $request->user_id)->exists();
    $projectExists = Project::where('id', $request->project_id)->exists();
    if (!$userExists || !$projectExists) {
        return response()->json([
            "error" => "User or Project not found",
            "status" => 404
        ]);
    }

    // Update the task
    $task->user_id = $request->user_id;
    $task->project_id = $request->project_id;
    $task->title = $request->title;
    $task->description = $request->description;
    $task->status = $request->status;
    $task->due_date = $request->due_date;
    $task->save();

    return response()->json([
        'data' => $task,
        'msg' => 'Task updated successfully',
        'status'=> 200
    ]);
    }


}
