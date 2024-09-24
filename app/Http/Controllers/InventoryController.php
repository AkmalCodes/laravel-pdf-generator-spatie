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
    //     public function getInventoryData()
    // {
    //     $result = [];

    //     $descriptions = Inventory::select('Description')
    //         ->distinct()
    //         ->pluck('Description')
    //         ->toArray();

    //     $overallEarliestDate = null;
    //     $overallLatestDate = null;

    //     foreach ($descriptions as $description) {
    //         // Fetch all items for the current description where SalesDate is not null
    //         $items = Inventory::where('Description', $description)
    //             ->whereNotNull('SalesDate') // Only items that are sold
    //             ->get(['InventoryCode', 'SalesAmount', 'CategoryCode', 'SalesDate', 'storecode', 'ClassCode', 'goldweight']); // Include goldweight column

    //         if ($items->isEmpty()) {
    //             continue;
    //         }

    //         $storeCounts = $this->countItemsByStorecode($items);
    //         $inventoryCodes = $items->pluck('InventoryCode')->toArray();
    //         $categoryCode = $items->pluck('CategoryCode')->first();
    //         $salesAmount = $items->sum('SalesAmount');
    //         $classCode = $items->pluck('ClassCode')->first();
    //         $itemsSoldCount = $items->count();
    //         $earliestDate = Carbon::parse($items->min('SalesDate'))->format('Y-m-d');
    //         $latestDate = Carbon::parse($items->max('SalesDate'))->format('Y-m-d');

    //         if (is_null($overallEarliestDate) || $earliestDate < $overallEarliestDate) {
    //             $overallEarliestDate = $earliestDate;
    //         }
    //         if (is_null($overallLatestDate) || $latestDate > $overallLatestDate) {
    //             $overallLatestDate = $latestDate;
    //         }

    //         // Filter the inventory codes
    //         $filteredInventoryCodes = $this->filterInventoryCode($inventoryCodes, $categoryCode);

    //         // Categorize items by gold weight
    //         $goldWeightCategories = $this->categorizeItemsByGoldWeight($items);

    //         // Add data to the result array
    //         $result[$description] = [
    //             'inventory_codes' => $filteredInventoryCodes,
    //             'category_code' => $categoryCode,
    //             'sales_amount' => $salesAmount . " RM",
    //             'items_sold_count' => $itemsSoldCount . " items",
    //             'earliest_date' => $earliestDate,
    //             'latest_date' => $latestDate,
    //             'store_counts' => $storeCounts,
    //             'gold_weight_categories' => $goldWeightCategories,  // New field for gold weight categories
    //         ];
    //     }

    //     return [
    //         'data' => $result,
    //         'overallEarliestDate' => $overallEarliestDate,
    //         'overallLatestDate' => $overallLatestDate,
    //     ];
    // }

    // public function categorizeItemsByGoldWeight($items)
    // {
    //     // Initialize categories for different gold weight ranges
    //     $categories = [
    //         '0-5' => 0,
    //         '5-10' => 0,
    //         '10-15' => 0,
    //         '15+' => 0,
    //         '20+' => 0, // For items with no goldweight data
    //     ];

    //     foreach ($items as $item) {
    //         $goldWeight = $item->goldweight;

    //         if (is_null($goldWeight)) {
    //             $categories['unknown']++; // If no goldweight, increment the unknown category
    //         } elseif ($goldWeight <= 5 && $goldWeight > 0) {
    //             $categories['0-5']++;
    //         } elseif ($goldWeight <= 10 && $goldWeight > 5) {
    //             $categories['5-10']++;
    //         } elseif ($goldWeight <= 15 && $goldWeight > 10) {
    //             $categories['10-15']++;
    //         } elseif ($goldWeight <= 20 && $goldWeight > 15){
    //             $categories['15+']++;
    //         }elseif ($goldWeight <= 25 && $goldWeight > 20){
    //             $categories['20+']++;
    //         }
    //     }

    //     return $categories;
    // }


    // public function countItemsByStorecode($items)
    // {
    //     $storeCounts = [];

    //     // Count items by storecode
    //     foreach ($items as $item) {
    //         $storecode = $item->storecode;

    //         if (!isset($storeCounts[$storecode])) {
    //             $storeCounts[$storecode] = 0;
    //         }

    //         $storeCounts[$storecode]++;
    //     }

    //     return $storeCounts;
    // }



    // public function filterInventoryCode($inventoryCodes, $categoryCode)
    // {
    //     return array_map(function ($code) use ($categoryCode) {
    //         // Remove the leading '1-' or '2-' from the code if present
    //         $code = preg_replace('/^[12]-/', '', $code);

    //         // Remove the category code prefix if it matches the beginning of the code
    //         if (strpos($code, $categoryCode) === 0) {
    //             $code = substr($code, strlen($categoryCode));
    //         }

    //         return $code;
    //     }, $inventoryCodes);
    // }

    // public function generateImage(){
    //     // Convert the image to base64
    //     $path = public_path('images/merchant9-logo.png');
    //     $type = pathinfo($path, PATHINFO_EXTENSION);
    //     $data = file_get_contents($path);
    //     $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    //     return $logoBase64;
    // }

    public function getSalesReceipts()
    {
        // Retrieve all records from TblReceipt where DocType is 'Sales'
        $receipts = TblReceipt::where('DocType', 'Sales')
            ->get(['InvoiceNo', 'StoreCode']);  // Only fetch InvoiceNo and StoreCode

        return $receipts;
    }

    public function getSalesInvoiceDetails($invoiceNo, $storeCode)
    {
        // Retrieve InventoryCode and other data filtered by InvoiceNo and StoreCode
        $invoiceDetails = TblSalesInvoiceDetail::where('InvoiceNo', $invoiceNo)
            ->where('StoreCode', $storeCode)
            ->get(['InventoryCode']);  // Only fetch InventoryCode

        return $invoiceDetails;
    }

    use App\Models\TblInventory;  // Assuming the model exists

    public function getInventoryInternalCode($inventoryCode)
    {
        // Retrieve InternalCode from TblInventory using InventoryCode
        $inventory = TblInventory::where('InventoryCode', $inventoryCode)
            ->first(['InternalCode']);  // Fetch InternalCode

        return $inventory;
    }

    public function getTopSalesQtyByDesignCode()
    {
        // Step 1: Get all sales receipts with InvoiceNo and StoreCode
        $salesReceipts = $this->getSalesReceipts();

        $topSales = [];

        // Step 2: Loop through the sales receipts
        foreach ($salesReceipts as $receipt) {
            // Step 3: Get sales invoice details for each receipt
            $salesDetails = $this->getSalesInvoiceDetails($receipt->InvoiceNo, $receipt->StoreCode);

            // Step 4: Loop through the sales details and get the InventoryCode
            foreach ($salesDetails as $detail) {
                // Step 5: Get InternalCode from TblInventory using InventoryCode
                $inventory = $this->getInventoryInternalCode($detail->InventoryCode);

                if ($inventory) {
                    $internalCode = $inventory->InternalCode;

                    // Add the InternalCode to the top sales list and increment quantity
                    if (!isset($topSales[$internalCode])) {
                        $topSales[$internalCode] = 0;
                    }

                    // Increment quantity (assuming the `Qty` field is present in sales details)
                    $topSales[$internalCode] += $detail->Qty;
                }
            }
        }

        // Step 6: Sort the top sales by quantity in descending order
        arsort($topSales);

        // Return the top sales by design code
        return $topSales;
    }

    public function generatePdfReport()
    {
        // Get the processed inventory data and overall earliest/latest dates
        $inventoryDataResult = $this->getInventoryData();

        $inventoryData = $inventoryDataResult['data'];
        $reportGeneratedAtStart = $inventoryDataResult['overallEarliestDate'];
        $reportGeneratedAtEnd = $inventoryDataResult['overallLatestDate'];

        $m9_logo = $this->generateImage();

        // Prepare data for the view
        $data = [
            'inventoryData' => $inventoryData,
            'report_generated_at_start' => $reportGeneratedAtStart,  // Earliest date
            'report_generated_at_end' => $reportGeneratedAtEnd,      // Latest date
            'm9_logo' => $m9_logo, // Add base64 image data
        ];

        // Render the view to HTML
        $html = view('pdf_report', $data)->render();
        

        // Use Browsershot to convert the HTML into a PDF
        Browsershot::html($html)
            // ->margins(20, 15, 20, 15)  // top, right, bottom, left margins in millimeters
            ->format('A4')  // Optional: set the page size (A4, Letter, etc.)
            ->setOption('no-sandbox', true)  // Adjust options if needed
            ->save(storage_path('app/public/inventory_report.pdf'));

        // Download the generated PDF
        return response()->download(storage_path('app/public/inventory_report.pdf'));
    }



    public function generatePdfReportPreview()
    {
        // (Same as the original preview method)
        $inventoryDataResult = $this->getInventoryData();
        $m9_logo = $this->generateImage();

        $inventoryData = $inventoryDataResult['data'];
        $reportGeneratedAtStart = $inventoryDataResult['overallEarliestDate'];
        $reportGeneratedAtEnd = $inventoryDataResult['overallLatestDate'];

        $data = [
            'inventoryData' => $inventoryData,
            'report_generated_at_start' => $reportGeneratedAtStart,
            'report_generated_at_end' => $reportGeneratedAtEnd,
            'm9_logo' => $m9_logo,
        ];

        // Return the view to preview in HTML
        return view('pdf_report', $data);
        // return dd($inventoryDataResult);
    }
}
