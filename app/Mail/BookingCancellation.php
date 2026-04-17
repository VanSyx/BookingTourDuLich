<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancellation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $tour;
    public $user;
    public $cancelledBy; // 'user' hoặc 'admin'

    public function __construct($booking, $tour, $user, $cancelledBy = 'admin')
    {
        $this->booking     = $booking;
        $this->tour        = $tour;
        $this->user        = $user;
        $this->cancelledBy = $cancelledBy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Thông báo huỷ tour - ' . ($this->tour['title'] ?? 'Tour du lịch'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-cancellation',
            with: [
                'bookingId'     => $this->booking['bookingId'] ?? '',
                'tourTitle'     => $this->tour['title'] ?? '',
                'tourStartDate' => isset($this->tour['startDate'])
                                   ? date('d/m/Y', strtotime($this->tour['startDate']))
                                   : '',
                'tourEndDate'   => isset($this->tour['endDate'])
                                   ? date('d/m/Y', strtotime($this->tour['endDate']))
                                   : '',
                'fullName'      => $this->user['fullName'] ?? '',
                'userEmail'     => $this->user['email'] ?? '',
                'cancelledBy'   => $this->cancelledBy,
                'cancelDate'    => date('d/m/Y H:i'),
                'numAdults'     => $this->booking['numAdults'] ?? 0,
                'numChildren'   => $this->booking['numChildren'] ?? 0,
                'totalPrice'    => number_format($this->booking['totalPrice'] ?? 0, 0, ',', '.'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
