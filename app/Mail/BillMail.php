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
            from: new Address('soporte@olt.management', 'Fibez Olt'),
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
                'subject' => $this->data['subject'],
                'name' => $this->data['name'],
                'address' => $this->data['address'],
                'city' => $this->data['city'],
                'companyCode' => $this->data['companyCode'],
                'companyName' => $this->data['companyName'],
                'country' => $this->data['country'],
                'email' => $this->data['email'],
                'state' => $this->data['state'],
                'telephone' => $this->data['telephone'],
                'zipCode' => $this->data['zipCode'],
                'transaction_id' => $this->data['transaction_id'],
                'order_date' => $this->data['order_date'],
                'items' => $this->data['items'],
                'total_amount'=> $this->data['total_amount']
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
