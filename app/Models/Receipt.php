<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    // Specify the table name since it's not following Laravel's default convention
    protected $table = 'tblreceipt';

    // Specify the primary key field
    protected $primaryKey = 'id';

    // Indicate that the primary key is auto-incrementing
    public $incrementing = true;

    // Define the fillable fields (those you want to allow mass assignment for)
    protected $fillable = [
        'RefNo',
        'StoreCode',
        'LocationCode',
        'StationName',
        'DocType',
        'DocNo',
        'DocDate',
        'InvoiceNo',
        'InvoiceDate',
        'TransDate',
        'Amount',
        'PayType',
        'SlipNo',
        'CurrCode',
        'ExchRate',
        'LocalAmount',
        'Polling',
        'TaxCode',
        'TaxRate',
        'Tax',
        'SalesPerson',
        'RefAmount',
        'RefAmount2',
        'IsCashier',
    ];
    

    // Disable timestamps if the table does not have created_at and updated_at fields
    public $timestamps = false;
}
