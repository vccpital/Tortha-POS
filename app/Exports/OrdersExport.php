<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Order::with(['cashier', 'customer', 'store', 'items.product']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Store',
            'Cashier',
            'Customer',
            'Products',
            'Total',
            'Status',
            'Payment Status',
            'Due Date',
            'Created At',
            'Updated At',
        ];
    }

    public function map($order): array
    {
        $productsList = $order->items->map(function ($item) {
            return ($item->product->name ?? 'Not Available') . ' (Qty: ' . $item->quantity . ')';
        })->implode(', ');
        return [
            $order->id,
            $order->store->name ?? 'N/A',
            $order->cashier->name ?? 'N/A',
            $order->customer->name ?? 'N/A',
            $productsList ?: 'N/A',
            number_format($order->total, 2),
            $order->status,
            $order->payment_status,
            $order->due_date,
            $order->created_at,
            $order->updated_at,
        ];
    }
}
