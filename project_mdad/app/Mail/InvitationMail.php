<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    use Queueable, SerializesModels;

    public $project;
    public $invitation;

    public function __construct($project, $invitation)
    {
        $this->project = $project;
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this->subject('Invitation to Join Project')
                    ->view('emails.invitation_plain');
    }
}
