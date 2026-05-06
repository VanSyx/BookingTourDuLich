<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $booking;
    public $checkout;
    public $tour;
    public $user;

    public function __construct($booking, $checkout, $tour, $user)
    {
        $this->booking = $booking;
        $this->checkout = $checkout;
        $this->tour = $tour;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💳 Xác nhận thanh toán - ' . $this->tour['title'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-confirmation',
            with: [
                'bookingId' => $this->booking['bookingId'],
                'tourTitle' => $this->tour['title'],
                'tourDestination' => $this->tour['destination'],
                'tourStartDate' => date('d/m/Y', strtotime($this->tour['startDate'])),
                'paymentMethod' => $this->checkout['paymentMethod'],
                'amount' => number_format($this->checkout['amount'], 0, ',', '.'),
                'transactionId' => $this->checkout['transactionId'],
                'paymentStatus' => $this->checkout['paymentStatus'] === 'y' ? 'Đã thanh toán' : 'Chưa thanh toán',
                'paymentDate' => date('d/m/Y H:i', strtotime($this->checkout['created_at'] ?? now())),
                'fullName' => $this->user['fullName'],
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
