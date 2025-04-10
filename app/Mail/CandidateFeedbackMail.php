<?php

namespace App\Mail;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateFeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;

    /**
     * Create a new message instance.
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function build()
    {
        return $this->subject('Feedback do Processo Seletivo - ' . $this->feedback->candidate->process->title)
            ->markdown('emails.candidate-feedback')
            ->with([
                'candidate' => $this->feedback->candidate,
                'feedback' => $this->feedback,
            ]);
    }
}
