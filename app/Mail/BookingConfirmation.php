<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $booking;
    public $tour;
    public $user;

    public function __construct($booking, $tour, $user)
    {
        $this->booking = $booking;
        $this->tour = $tour;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Xác nhận đặt tour - ' . $this->tour['title'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'bookingId'       => $this->booking['bookingId'] ?? '',
                'tourTitle'       => $this->tour['title'] ?? '',
                'tourDestination' => $this->tour['destination'] ?? '',
                'tourStartDate'   => isset($this->tour['startDate']) ? date('d/m/Y', strtotime($this->tour['startDate'])) : '',
                'tourEndDate'     => isset($this->tour['endDate'])   ? date('d/m/Y', strtotime($this->tour['endDate']))   : '',
                'numAdults'       => $this->booking['numAdults'] ?? 0,
                'numChildren'     => $this->booking['numChildren'] ?? 0,
                'totalPrice'      => number_format($this->booking['totalPrice'] ?? 0, 0, ',', '.'),
                'fullName'        => $this->user['fullName'] ?? '',
                'bookingDate'     => isset($this->booking['bookingDate'])
                                     ? date('d/m/Y H:i', strtotime($this->booking['bookingDate']))
                                     : date('d/m/Y H:i'),
                'userEmail'       => $this->user['email'] ?? '',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
