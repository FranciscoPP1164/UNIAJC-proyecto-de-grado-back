<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreatedAccountNotification extends Mailable
{
    use Queueable, SerializesModels;
    public string $route;

    /**
     * Create a new message instance.
     */
    public function __construct(private User $user)
    {
        $verificationToken = $user->renovateVerificationToken();
        $this->route = config('app.front_url') . '/register?user=' . $user->id . '&verificationToken=' . $verificationToken;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación cuenta creada',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.created-account-notification',
            with: [
                'user' => $this->user,
                'route' => $this->route,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
