<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InventoryController;

Route::get('/', function () {
    return view('welcome');
});

// Route to generate and download the PDF report
Route::get('/inventory-report', [InventoryController::class, 'getTopSalesQtyByDesignCode'])->name('inventory.getTopSalesQtyByDesignCode');

// Route to preview the report in HTML before generating the PDF
Route::get('/inventory-report/preview', [InventoryController::class, 'generatePdfReportPreview'])->name('inventory.report.preview');

