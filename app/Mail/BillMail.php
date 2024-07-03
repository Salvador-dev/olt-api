<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class BillMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public array $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('rodavlasxp@gmail.com', 'Salvador Villa'),
            subject: $this->data['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'bill.billMail',
            with: [
                'name' => $this->data['name'],
                'pages' => $this->data['pages']
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

        $pdf = PDF::loadView('bill.billMail', $this->data);


        if(!empty($this->data['file'])){
            return [
                Attachment::fromData(fn () => $pdf->output(), 'Report.pdf')
                // ->withMime('application/pdf'),            
            ];
        }
        return [];
    }
}
