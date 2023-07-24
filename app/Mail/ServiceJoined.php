<?php

// app/Mail/ServiceJoined.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServiceJoined extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $service;

    public function __construct($user, $service)
    {
        $this->user = $user;
        $this->service = $service;
    }

    public function build()
    {
        return $this->view('emails.serviceJoined')
            ->with([
                'userName' => $this->user->name,
                'serviceName' => $this->service->name,
            ]);
    }
}
