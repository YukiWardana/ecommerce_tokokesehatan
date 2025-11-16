<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

/**
 * Order Invoice Mail Class
 * 
 * Sends order invoice email to customers when order status changes to "processing"
 * Includes PDF invoice attachment with complete order details
 * 
 * Triggered by: Admin updating order status to "processing"
 * 
 * Email contains:
 * - Order confirmation message
 * - Order details (number, date, items, total)
 * - PDF invoice attachment
 */
class OrderInvoice extends Mailable
{
    // Use Laravel traits for queue support and model serialization
    use Queueable, SerializesModels;

    /**
     * Order instance
     * 
     * Public property accessible in email view
     * 
     * @var \App\Models\Order
     */
    public $order;

    /**
     * Create a new message instance
     * 
     * @param  \App\Models\Order  $order  The order to send invoice for
     * @return void
     */
    public function __construct(Order $order)
    {
        // Store order instance for use in email view and PDF
        $this->order = $order;
    }

    /**
     * Get the message envelope
     * 
     * Defines email subject line
     * 
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Dynamic subject with order number
            subject: 'Order Invoice - ' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition
     * 
     * Specifies which Blade view to use for email body
     * 
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            // Email body view (resources/views/emails/order-invoice.blade.php)
            view: 'emails.order-invoice',
        );
    }

    /**
     * Get the attachments for the message
     * 
     * Generates PDF invoice and attaches it to email
     * 
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF from invoice view using DomPDF library
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $this->order]);
        
        return [
            // Create attachment from PDF data
            Attachment::fromData(
                fn () => $pdf->output(),  // PDF binary data
                'Invoice-' . $this->order->order_number . '.pdf'  // Filename
            )->withMime('application/pdf'),  // Set MIME type
        ];
    }
}
