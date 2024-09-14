<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    // Specify the table name since it's not following Laravel's default convention
    protected $table = 'tblinventory';

    // Specify the primary key field
    protected $primaryKey = 'id';

    // Indicate that the primary key is auto-incrementing
    public $incrementing = true;

    // Define the fillable fields (those you want to allow mass assignment for)
    protected $fillable = [
        'StoreCode', 'InventoryCode', 'Description', 'ClassCode', 'CategoryCode', 'CatalogueCode', 'DesignName', 
        'InternalCode', 'DesignVersion', 'Range', 'RangeType', 'Setting', 'SubSetting', 'GoldWeight', 'GrossWeight', 
        'Purity', 'JewelSize', 'JewelLength', 'GoldRate', 'GoldCost', 'McCostType', 'McCost', 'McCostTotal', 
        'McPriceType', 'McPrice', 'McPriceTotal', 'PTaxCode', 'TaxPercentage', 'SCPercentage', 'Tax1', 'Tax2', 
        'Tax3', 'STaxCode', 'TotalCost', 'AddCost', 'AddCost2', 'AddCost3', 'AddCost4', 'PurchaseQty', 'QtyOnHand', 
        'SuppCurrCode', 'SuppTagPrice', 'SuppDiscount', 'SuppPrice', 'ReportCurrCode', 'ReportExchRate', 'ReportTagPrice', 
        'LocalCurrCode', 'LocalExchRate', 'MarkupPercentage', 'MarkupPercentage2', 'MarkupPercentage3', 'CurrCode', 
        'ExchRate', 'DiamondDesc', 'DiamondCost', 'DiamondPrice', 'ProfitMargin', 'TagPrice', 'DiamondCost2CurrCode', 
        'DiamondCost2', 'DiamondPrice2', 'TagPrice2CurrCode', 'TagPrice2', 'Remarks', 'LabelLine1', 'LabelLine2', 
        'LabelLine3', 'LabelLine4', 'LabelLine5', 'SalesBillNo', 'SalesDate', 'SalesAmount', 'SalesBy', 'LocationCode', 
        'Status', 'MiscRemarks', 'RefNo', 'CurrCost', 'Reserve', 'Transit', 'Consign', 'Loan', 'LoanToStore', 'LoanDate', 
        'TagType', 'SalesType', 'JobSheetNo', 'VendorCode', 'PurchDate', 'PurchInvoiceType', 'Brand', 'Booked', 
        'BookedBy', 'BookedStore', 'BookedDate', 'DiscountCode', 'DiscountPercentage', 'Discontinued', 'Gift', 
        'CreatedBy', 'CreatedDate', 'ModifiedBy', 'ModifiedDate', 'Polling', 'TempRef1', 'TempRef2', 'TempRef3', 
        'TransferToStore', 'CustomerCode', 'ConsignNo', 'ConsignFromStore', 'GMS', 'ReceivedDate', 'OriginalCost', 
        'InvoiceNo', 'BookedRefNo', 'ParentCost', 'PurchaseGoldWeight', 'PONo', 'POSeqNo', 'Exchange', 'AdjWeight', 
        'AdjCost', 'ItemType', 'GL_Percentage', 'GL_Weight', 'GL_Cost', 'ReservePrice', 'PurchaseType', 'RMRefNo', 
        'ParentCurrCode', 'ActualCost', 'ImagePath', 'ReserveQty', 'ExchangeQty', 'McCostF', 'McCostTotalF', 
        'ReserveWeight', 'ExchangeWeight', 'TransitWeight', 'StockOutWeight', 'ReturnWeight', 'ConsignQty', 
        'ConsignWeight'
    ];

    // Disable timestamps if the table does not have created_at and updated_at fields
    public $timestamps = false;
}
