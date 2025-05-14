<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Response;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    public function print(PurchaseOrder $purchaseOrder)
    {
        try {
            // Default empty values for prepared by and approved by
            $preparedBy = '';
            $approvedBy = '';
            
            // Create MPDF instance
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'tempDir' => storage_path('app/pdf-temp'),
            ]);

            // Load purchase order with relationships
            $purchaseOrder->load(['supplier', 'items.product']);
            
            // Render view
            $html = view('purchase-orders.print', [
                'purchaseOrder' => $purchaseOrder,
                'preparedBy' => $preparedBy,
                'approvedBy' => $approvedBy,
            ])->render();

            // Generate PDF
            $mpdf->WriteHTML($html);
            
            // Return PDF
            return response($mpdf->Output('', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="PO-' . $purchaseOrder->po_number . '.pdf"');
        } catch (\Exception $e) {
            // Log the error for administrators
            Log::error('PDF generation failed: ' . $e->getMessage());
            
            // Redirect back with a user-friendly error message
            return back()->with('error', 'Unable to generate PDF. Please try again later.');
        }
    }
}