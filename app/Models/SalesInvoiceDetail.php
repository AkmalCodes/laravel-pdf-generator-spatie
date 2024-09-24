<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceDetail extends Model
{
    // Specify the table name since it's not following Laravel's default convention
    protected $table = 'tblsalesinvoicedetail';

    // Specify the primary key field
    protected $primaryKey = 'id';

    // Indicate that the primary key is auto-incrementing
    public $incrementing = true;

    // Define the fillable fields (those you want to allow mass assignment for)
    protected $fillable = [
        'InvoiceNo',
        'RecordNo',
        'StoreCode',
        'InventoryCode',
        'Qty',
        'GoldRate',
        'GoldRate2',
        'Mc',
        'Loss',
        'GrossPrice',
        'Discount',
        'DiscountCode',
        'AddDiscount',
        'AddDiscount2',
        'DiscountPer',
        'DiscountPer2',
        'Discount2',
        'Discount2Code',
        'TotalPrice',
        'TaxCode',
        'TaxRate',
        'Tax1',
        'Tax2',
        'Tax3',
        'NetPrice',
        'SalesMan',
        'Override',
        'LocationCode',
        'StationName',
        'Description',
        'ClassCode',
        'CategoryCode',
        'CatalogueCode',
        'DesignName',
        'InternalCode',
        'DesignVersion',
        'Range',
        'RangeType',
        'Setting',
        'JewelSize',
        'JewelLength',
        'LabelLine1',
        'LabelLine2',
        'LabelLine3',
        'LabelLine4',
        'LabelLine5',
        'GoldWeight',
        'GrossWeight',
        'GoldCost',
        'McCostTotal',
        'McPriceTotal',
        'TotalCost',
        'DiamondDesc',
        'DiamondCost',
        'DiamondCost2',
        'DiamondPrice',
        'DiamondPrice2',
        'TagPrice',
        'TagPrice2',
        'RefNo',
        'CurrCost',
        'Polling',
        'SalesType',
        'Remarks',
        'Promotion',
        'Status',
        'CreatedDate',
        'GMS',
        'Brand',
        'Exchange',
        'PromotionRef',
        'PurchInvoiceType',
        'TaxablePrice',
        'IsGSTExempted',
        'AddOnWeight',
        'AddOnCost',
        'PurchaseType',
        'AllocatePoints',
        'DepositGST',
        'PrevStoreCode',
        'PrevInvoiceNo',
        'PrevInvoiceDate',
        'ExchangeDays',
        'ProductDiscount',
        'BulkInventoryCode',
        'OTP',
        'AddCost',
        'AddCost2',
        'ActualCost',
        'PrevNetPrice',
        'ActualMcPriceTotal',
        'MiscRemarks',
        'SubSetting',
        'UnitPrice',
        'ReturnQty',
        'CanPrint',
    ];
    

    // Disable timestamps if the table does not have created_at and updated_at fields
    public $timestamps = false;
}
