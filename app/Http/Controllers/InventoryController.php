<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon;
use App\Models\Inventory;
use App\Models\SalesInvoiceDetail;
use App\Models\Receipt;

class InventoryController extends Controller
{
    public function __construct()
    {
        set_time_limit(180);
    }

    public function getReceipts()
    {
        return Receipt::all();
    }

    public function getSalesInvoiceDetails()
    {
        return SalesInvoiceDetail::all();
    }

    public function getInventory()
    {
        return Inventory::all();
    }

    // Optimized to use `where` directly on the collection
    public function filterSalesReceipts($receipts)
    {
        // Use `where` instead of `filter` for better performance on collection
        return $receipts->where('DocType', 'SALES');
    }

    // Optimized the InvoiceNo and StoreCode matching logic using a hash map approach
    public function matchInvoiceDetails($receipts, $salesInvoiceDetails)
    {
        // Create an array of InvoiceNo and StoreCode pairs from the receipts
        $invoiceStorePairs = $receipts->map(function ($receipt) {
            return $receipt->InvoiceNo . '-' . $receipt->StoreCode;
        });

        // Grouping and filtering the sales invoice details for matching InvoiceNo and StoreCode
        return $salesInvoiceDetails->filter(function ($detail) use ($invoiceStorePairs) {
            $key = $detail->InvoiceNo . '-' . $detail->StoreCode;
            return $invoiceStorePairs->contains($key);
        });
    }

    public function getInternalCodes($salesInvoiceDetails, $inventory)
    {
        // Step 1: Create a lookup map of InventoryCode to InternalCode for faster lookups
        $inventoryMap = $inventory->pluck('InternalCode', 'InventoryCode')->toArray();
    
        // Step 2: Initialize the result collection
        $result = collect();
    
        // Step 3: Chunk the salesInvoiceDetails collection and process each chunk(this is to reduce execution time and memory usage)
        $salesInvoiceDetails->chunk(1000)->each(function ($chunk) use ($inventoryMap, &$result) {
            foreach ($chunk as $detail) {
                // Step 4: Check if InventoryCode exists in the lookup map
                if (isset($inventoryMap[$detail->InventoryCode])) {
                    $result->push([
                        'InternalCode' => $inventoryMap[$detail->InventoryCode],
                        // 'Qty' => $detail->Qty,
                    ]);
                }
            }
        });
    
        return $result;
    }

    public function calculateTopSales($internalCodes)
    {
        $topSales = [];

        foreach ($internalCodes as $code) {
            $internalCode = trim($code['InternalCode']);
            
            // If this is the first time encountering this InternalCode, initialize its data
            if (!isset($topSales[$internalCode])) {
                // Retrieve all items for the given InternalCode from TblInventory
                $totalItems = Inventory::where('InternalCode', $internalCode)->count();
                
                // Retrieve sold items for the given InternalCode where SalesDate is not null
                $soldItems = Inventory::where('InternalCode', $internalCode)
                                    ->whereNotNull('SalesDate')
                                    ->count();

                // Initialize the array to store total items, sold items, and quantity sold
                $topSales[$internalCode] = [
                    'InternalCode' => $internalCode,
                    'TotalItems' => $totalItems,
                    'SoldItems' => $soldItems,
                    // 'Qty' => 0,  // Initialize the quantity sold
                ];
            }

            // Add the quantity sold from the invoice details
            // $topSales[$internalCode]['Qty'] += $qty;
        }

        // Sort by quantity sold in descending order
        usort($topSales, function($a, $b) {
            return $b['SoldItems'] <=> $a['SoldItems'];
        });

        return $topSales;
    }

 
     // Main function to calculate top sales quantity by design code
     public function getTopSalesQtyByDesignCode()
     {  
         // Increase time limit for this process
         set_time_limit(180); // 3 minutes
 
         // Step 1: Retrieve all data
         $receipts = $this->getReceipts();
         $salesInvoiceDetails = $this->getSalesInvoiceDetails();
         $inventory = $this->getInventory();
 
         // Step 2: Filter receipts for 'SALES'
         $filteredReceipts = $this->filterSalesReceipts($receipts);
 
         // Step 3: Match sales invoice details
         $matchedDetails = $this->matchInvoiceDetails($filteredReceipts, $salesInvoiceDetails);
 
         // Step 4: Retrieve internal codes
         $internalCodes = $this->getInternalCodes($matchedDetails, $inventory);
 
         // Step 5: Calculate top sales quantity by design code
         $topSales = $this->calculateTopSales($internalCodes);
 
         return dd($topSales); // Display the results
     }

     public function generatePdfReport()
    {
        // Fetch top sales data
        $inventoryData = $this->getTopSalesQtyByDesignCode();

        $reportGeneratedAtStart = $inventoryData[0]['SalesDate'] ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $reportGeneratedAtEnd = $inventoryData[count($inventoryData) - 1]['SalesDate'] ?? Carbon::now()->endOfYear()->format('Y-m-d');
        $m9_logo = $this->generateImage(); // Assume this returns the image in base64

        $data = [
            'inventoryData' => $inventoryData,
            'report_generated_at_start' => $reportGeneratedAtStart,
            'report_generated_at_end' => $reportGeneratedAtEnd,
            'm9_logo' => $m9_logo,
        ];

        // Render the view to HTML
        $html = view('pdf_report', $data)->render();

        // Use Browsershot to convert the HTML into a PDF
        Browsershot::html($html)
            ->format('A4')
            ->setOption('no-sandbox', true)
            ->save(storage_path('app/public/inventory_report.pdf'));

        return response()->download(storage_path('app/public/inventory_report.pdf'));
    }

    // PDF Report Preview in HTML
    public function generatePdfReportPreview()
    {
        $inventoryData = $this->getTopSalesQtyByDesignCode();

        $reportGeneratedAtStart = $inventoryData[0]['SalesDate'] ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $reportGeneratedAtEnd = $inventoryData[count($inventoryData) - 1]['SalesDate'] ?? Carbon::now()->endOfYear()->format('Y-m-d');
        $m9_logo = $this->generateImage(); // Assume this returns the image in base64

        $data = [
            'inventoryData' => $inventoryData,
            'report_generated_at_start' => $reportGeneratedAtStart,
            'report_generated_at_end' => $reportGeneratedAtEnd,
            'm9_logo' => $m9_logo,
        ];

        return view('pdf_report', $data);
    }

    // Helper function to generate image (assumed as base64)
    private function generateImage()
    {
        // Assume this function converts an image URL to base64
        $path = 'https://merchant9.com/img/merchant9-logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }
}
