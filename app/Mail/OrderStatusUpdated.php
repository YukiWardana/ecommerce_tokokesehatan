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
 * Order Status Updated Mail Class
 * 
 * Sends email notification to customers when seller updates order status
 * Includes PDF invoice for processing, shipped, and delivered statuses
 * 
 * Triggered by: Seller updating order status from seller dashboard
 * 
 * Email contains:
 * - Previous status → New status
 * - Personalized message based on new status
 * - Order details
 * - PDF invoice (for processing/shipped/delivered)
 */
class OrderStatusUpdated extends Mailable
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
     * Previous order status
     * 
     * Used to show status transition in email
     * 
     * @var string
     */
    public $oldStatus;

    /**
     * Create a new message instance
     * 
     * @param  \App\Models\Order  $order      The order being updated
     * @param  string             $oldStatus  Previous status before update
     * @return void
     */
    public function __construct(Order $order, $oldStatus)
    {
        // Store order and old status for use in email view
        $this->order = $order;
        $this->oldStatus = $oldStatus;
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
            subject: 'Order Status Updated - ' . $this->order->order_number,
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
            // Email body view (resources/views/emails/order-status-updated.blade.php)
            view: 'emails.order-status-updated',
        );
    }

    /**
     * Get the attachments for the message
     * 
     * Conditionally attaches PDF invoice based on order status
     * 
     * Invoice attached for: processing, shipped, delivered
     * No invoice for: pending, cancelled
     * 
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Only attach invoice PDF for certain statuses
        if (in_array($this->order->status, ['processing', 'shipped', 'delivered'])) {
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

        // No attachments for pending or cancelled orders
        return [];
    }
}
