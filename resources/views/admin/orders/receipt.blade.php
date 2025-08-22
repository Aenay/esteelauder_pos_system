<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt #{{ $order->Order_ID }}</title>
    <style>
        /* 80mm receipt width approx 302-320px for print */
        :root { --paper-width: 320px; }
        * { box-sizing: border-box; }
        body { font-family: 'Courier New', Courier, monospace; margin: 0; background: #f5f5f5; }
        .receipt { width: var(--paper-width); margin: 16px auto; background: #fff; padding: 12px; color: #111; }
        .center { text-align: center; }
        .bold { font-weight: 700; }
        .small { font-size: 12px; }
        .xs { font-size: 11px; }
        .lg { font-size: 16px; }
        .row { display: flex; justify-content: space-between; align-items: baseline; }
        .mt-6 { margin-top: 6px; }
        .mt-8 { margin-top: 8px; }
        .mt-12 { margin-top: 12px; }
        .divider { border-top: 1px dashed #999; margin: 8px 0; }
        .items { margin-top: 6px; }
        .item { display: grid; grid-template-columns: 1fr auto; gap: 8px; margin: 4px 0; }
        .qtyprice { display: flex; gap: 8px; }
        .mono { font-family: 'Courier New', Courier, monospace; }
        .right { text-align: right; }
        .w-100 { width: 100%; }
        @media print {
            body { background: #fff; }
            .receipt { margin: 0; width: var(--paper-width); box-shadow: none; }
            .no-print { display: none !important; }
            @page { margin: 6mm; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="center">
            <div class="bold lg">ESTEE LAUDER</div>
            <div class="small">Address line 1, City</div>
            <div class="small">Tel: +1 000 000 0000</div>
        </div>
        <div class="divider"></div>
        <div class="row small">
            <div>Date: {{ $order->Order_Date->format('Y-m-d') }}</div>
            <div>Time: {{ $order->Order_Date->format('H:i') }}</div>
        </div>
        <div class="row small">
            <div>Order: #{{ $order->Order_ID }}</div>
            <div>Staff: {{ optional($order->staff)->Staff_Name ?? 'N/A' }}</div>
        </div>
        @if($order->customer)
        <div class="small mt-6">Customer: {{ $order->customer->Customer_Name }}</div>
        @endif
        <div class="divider"></div>

        <div class="items small">
            <div class="row bold">
                <div>QTY ITEM</div>
                <div class="right">AMT</div>
            </div>
            <div class="divider"></div>
            @foreach($order->orderDetails as $detail)
                <div class="item">
                    <div>
                        <div class="row">
                            <div class="qtyprice">
                                <span>{{ $detail->Quantity }}</span>
                                <span>{{ $detail->product->Product_Name }}</span>
                            </div>
                        </div>
                        <div class="xs">SKU: {{ $detail->product->SKU }}</div>
                    </div>
                    <div class="right">${{ number_format($detail->product->Price * $detail->Quantity, 2) }}</div>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>
        <div class="row small"><div>Subtotal</div><div>${{ number_format($order->Subtotal, 2) }}</div></div>
        @if($order->Discount_Amount > 0)
            <div class="row small"><div>Discount</div><div>-${{ number_format($order->Discount_Amount, 2) }}</div></div>
        @endif
        <div class="row small bold"><div>Total</div><div>${{ number_format($order->Final_Amount, 2) }}</div></div>
        <div class="row small mt-6"><div>Payment</div><div class="capitalize">{{ ucfirst($order->payment_method) }}</div></div>
        @if($order->transaction_id)
            <div class="xs">Txn: {{ $order->transaction_id }}</div>
        @endif

        <div class="divider"></div>
        <div class="center small">THANK YOU!</div>
    </div>

    <div class="center no-print" style="margin:12px;">
        <button onclick="window.print()">Print</button>
        <a href="{{ route('admin.orders.show', $order) }}">Back</a>
    </div>
</body>
</html>
