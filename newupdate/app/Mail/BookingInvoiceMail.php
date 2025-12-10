<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class BookingInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        $pdf = PDF::loadView('email.invoice', ['booking' => $this->booking])
            ->setOption('javascript-delay', 1000)
            ->setOption('no-stop-slow-scripts', true)
            ->setOption('enable-local-file-access', true);

        return $this->subject('Your Booking Invoice - ' . basicControl()->site_title)
            ->view('email.invoice')
            ->with([
                'booking' => $this->booking,
            ])
            ->attachData(
                $pdf->output(),
                'invoice-' . $this->booking->uid . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
