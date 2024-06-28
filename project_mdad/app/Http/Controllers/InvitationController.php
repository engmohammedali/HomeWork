<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Invitation;

use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function Send_invitations(Request $request)
    {
        $project = Project::findOrFail($request->project_id);

        foreach ($request->emails as $email) {
            $invitation = Invitation::create([
                'project_id' => $project->id,
                'email' => $email,
                'status' => 'pending'
            ]);

            Mail::to($email)->send(new InvitationMail($project, $invitation));
        }

        return response()->json([
            'message' => 'Invitations sent successfully',
            'status' => 200
        ]);
    }

}
