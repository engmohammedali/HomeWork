Hello,

You have been invited to join the project "{{ $project->name }}".

Please click the link below to accept the invitation:

{{ route('accept_invitation', $invitation->id) }}

Regards,
Task Manager
