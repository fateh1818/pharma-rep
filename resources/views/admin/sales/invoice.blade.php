<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{AppSettings::get('app_name', 'Pharmacy')}}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            padding: 20px;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-box {
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            padding: 20px;
            font-size: 16px;
            line-height: 24px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-title {
            font-size: 32px;
            line-height: 45px;
            color: #333;
        }
        .invoice-details {
            text-align: right;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        table td {
            padding: 5px;
            vertical-align: top;
        }
        table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        table tr.item td {
            border-bottom: 1px solid #eee;
        }
        table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .print-btn {
            background: #009688;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none;
            display: inline-block;
        }
        @media print {
            .print-btn {
                display: none;
            }
            .invoice-box {
                box-shadow: none;
                border: 0;
            }
        }
    </style>
</head>
<body>
    <a href="javascript:window.print()" class="print-btn">Print Invoice</a>
    
    <div class="invoice-box">
        <div class="invoice-header">
            <div class="invoice-title">
                {{AppSettings::get('app_name', 'PHARMACY')}}<br>
                <small style="font-size: 14px;">Invoice #INV-{{$sale->id}}</small>
            </div>
            <div class="invoice-details">
                Created: {{date_format(date_create($sale->created_at),'d M Y')}}<br>
            </div>
        </div>

        <table>
            <tr class="heading">
                <td>Item</td>
                <td>Price</td>
                <td>Quantity</td>
                <td>Total</td>
            </tr>

            <tr class="item">
                <td>{{$sale->product->purchase->product}}</td>
                <td>{{AppSettings::get('app_currency', '$')}} {{$sale->product->price}}</td>
                <td>{{$sale->quantity}}</td>
                <td>{{AppSettings::get('app_currency', '$')}} {{$sale->total_price}}</td>
            </tr>

            <tr class="total">
                <td colspan="3"></td>
                <td>Total: {{AppSettings::get('app_currency', '$')}} {{$sale->total_price}}</td>
            </tr>
        </table>
        
        <br>
        <p style="text-align: center; font-size: 14px;">Thank you for your business!</p>
    </div>
</body>
</html>
