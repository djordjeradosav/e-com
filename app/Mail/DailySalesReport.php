<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailySalesReport extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $orders;
    public string $date;
    public float $totalRevenue;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $orders, string $date)
    {
        $this->orders = $orders;
        $this->date = $date;
        $this->totalRevenue = $orders->sum('total_amount');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Sales Report - ' . $this->date,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-sales-report',
            with: [
                'orders' => $this->orders,
                'date' => $this->date,
                'totalRevenue' => number_format($this->totalRevenue, 2),
                'orderCount' => $this->orders->count(),
            ],
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