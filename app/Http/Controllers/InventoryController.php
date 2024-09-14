<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function getInventoryData()
    {
        $result = [];

        $descriptions = Inventory::select('Description')
            ->distinct()
            ->pluck('Description')
            ->toArray();

        // Initialize variables to track overall earliest and latest dates
        $overallEarliestDate = null;
        $overallLatestDate = null;

        foreach ($descriptions as $description) {
            // Fetch all items for the current description where SalesDate is not null
            $items = Inventory::where('Description', $description)
                ->whereNotNull('SalesDate') // Only items that are sold
                ->get(['InventoryCode', 'SalesAmount', 'CategoryCode', 'SalesDate', 'storecode']); // Include storecode

            if ($items->isEmpty()) {
                continue;
            }

            // Count items sold by storecode
            $storeCounts = $this->countItemsByStorecode($items);

            // Collect inventory codes, category code, total sales amount, number of items sold
            $inventoryCodes = $items->pluck('InventoryCode')->toArray();
            $categoryCode = $items->pluck('CategoryCode')->first();
            $salesAmount = $items->sum('SalesAmount');
            $itemsSoldCount = $items->count();

            // Get earliest and latest sales dates for the current description
            $earliestDate = Carbon::parse($items->min('SalesDate'))->format('Y-m-d');
            $latestDate = Carbon::parse($items->max('SalesDate'))->format('Y-m-d');

            // Update overall earliest and latest dates across all descriptions
            if (is_null($overallEarliestDate) || $earliestDate < $overallEarliestDate) {
                $overallEarliestDate = $earliestDate;
            }
            if (is_null($overallLatestDate) || $latestDate > $overallLatestDate) {
                $overallLatestDate = $latestDate;
            }

            $filteredInventoryCodes = $this->filterInventoryCode($inventoryCodes, $categoryCode);

            // Add data to the result array
            $result[$description] = [
                'inventory_codes' => $filteredInventoryCodes,
                'category_code' => $categoryCode,
                'sales_amount' => $salesAmount . " RM",
                'items_sold_count' => $itemsSoldCount . " pcs",
                'earliest_date' => $earliestDate,
                'latest_date' => $latestDate,
                'store_counts' => $storeCounts, // Add store-specific counts
            ];
        }

        // Return the result and the overall earliest and latest dates
        return [
            'data' => $result,
            'overallEarliestDate' => $overallEarliestDate,
            'overallLatestDate' => $overallLatestDate,
        ];
    }

    public function countItemsByStorecode($items)
    {
        $storeCounts = [];

        // Count items by storecode
        foreach ($items as $item) {
            $storecode = $item->storecode;

            if (!isset($storeCounts[$storecode])) {
                $storeCounts[$storecode] = 0;
            }

            $storeCounts[$storecode]++;
        }

        return $storeCounts;
    }



    public function filterInventoryCode($inventoryCodes, $categoryCode)
    {
        return array_map(function ($code) use ($categoryCode) {
            // Remove the leading '1-' or '2-' from the code if present
            $code = preg_replace('/^[12]-/', '', $code);

            // Remove the category code prefix if it matches the beginning of the code
            if (strpos($code, $categoryCode) === 0) {
                $code = substr($code, strlen($categoryCode));
            }

            return $code;
        }, $inventoryCodes);
    }

    public function generateImage(){
        // Convert the image to base64
        $path = public_path('images/merchant9-logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $logoBase64;
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
