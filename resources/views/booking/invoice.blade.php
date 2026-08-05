<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $booking->booking_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 14px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }

        .header-table, .details-table, .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
        }

        .meta-text {
            text-align: right;
        }

        .divider {
            border-bottom: 2px solid #e2e8f0;
            margin: 20px 0;
        }

        .details-table td {
            width: 50%;
            vertical-align: top;
            padding-bottom: 20px;
        }

        .items-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #cbd5e1;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-table {
            float: right;
            width: 250px;
            margin-top: 20px;
        }

        .totals-table td {
            padding: 6px 10px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 16px;
            color: #4f46e5;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px !important;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Header Info -->
        <table class="header-table">
            <tr>
                <td>
                    <span class="title">{{ App\Models\Setting::get('site_name', 'RentMoto') }}</span><br>
                    <small style="color: #64748b;">Premium Rentals Platform</small>
                </td>
                <td class="meta-text">
                    <strong>Invoice #:</strong> {{ $booking->booking_number }}<br>
                    <strong>Date:</strong> {{ date('F d, Y') }}<br>
                    <strong>Status:</strong> <span style="text-transform: uppercase; color: #16a34a; font-weight: bold;">{{ $booking->status }}</span>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Address Info -->
        <table class="details-table">
            <tr>
                <td>
                    <strong style="color: #4f46e5;">Renter Information</strong><br>
                    Name: {{ $booking->user->name }}<br>
                    Email: {{ $booking->user->email }}<br>
                    Phone: {{ $booking->user->phone ?? 'N/A' }}<br>
                    Address: {{ $booking->user->address ?? 'N/A' }}
                </td>
                <td>
                    <strong style="color: #4f46e5;">Rental Office Depot</strong><br>
                    Pickup Depot: {{ $booking->pickupLocation->name }}<br>
                    Return Depot: {{ $booking->returnLocation->name }}<br>
                    Pickup Date: {{ $booking->pickup_date->format('Y-m-d') }}<br>
                    Return Date: {{ $booking->return_date->format('Y-m-d') }}
                </td>
            </tr>
        </table>

        <!-- Itemized Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Rented Item</th>
                    <th style="text-align: center;">Daily Rate</th>
                    <th style="text-align: center;">Duration</th>
                    <th style="text-align: right;">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $booking->vehicle->name }}</strong><br>
                        <small style="color: #64748b;">License Plate: {{ $booking->vehicle->license_plate }} | Color: {{ $booking->vehicle->color }}</small>
                    </td>
                    <td style="text-align: center;">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->price_per_day, 2) }}</td>
                    <td style="text-align: center;">{{ $booking->total_days }} Days</td>
                    <td style="text-align: right;">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals Block -->
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->subtotal, 2) }}</td>
            </tr>
            @if($booking->discount_amount > 0)
                <tr style="color: #16a34a;">
                    <td>Promo Code:</td>
                    <td style="text-align: right;">-{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->discount_amount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Taxes (12%):</td>
                <td style="text-align: right;">{{ App\Models\Setting::get('currency_symbol', '₹') }}{{ number_format($booking->tax_amount, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td style="text-align: right;">{{ App\Models\Setting::get('currency_symbol', "₹") }}{{ number_format($booking->grand_total, 2) }}</td>
            </tr>
        </table>
        
        <div style="clear: both;"></div>

        <!-- Footer terms -->
        <div class="footer">
            <p>Thank you for choosing {{ App\Models\Setting::get('site_name', 'RentMoto') }}! If you have queries about this invoice, contact {{ App\Models\Setting::get('site_email', 'support@rentmoto.com') }}.</p>
            <p>100 Rental Plaza, Suite A, San Francisco, CA | +1 (555) 234-5678</p>
        </div>
    </div>
</body>
</html>
