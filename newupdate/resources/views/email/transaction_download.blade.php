
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tax Invoice </title>
</head>

<body style="font-family: Arial, sans-serif; margin: 40px; color: #000; font-size: 14px;">

<div style="max-width: 900px; margin: auto; border: 1px solid #ccc; padding: 30px;">

    <img src="{{ $logoPath }}" alt="image">

    <h2 style=" margin-bottom: 40px;">Tax Invoice</h2>

    <!-- Header Info -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
        <div style="width: 48%;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="background-color: #555; color: white;">
                    <td colspan="2" style="padding: 8px;">From</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 8px;">{{ $businessInfo['name'] }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 8px;">{{ $businessInfo['address'] }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 8px;"><span class="info-label">Contact: </span>{{ $businessInfo['contact_email'] }}<br>
                        {{ $businessInfo['phone'] }}</td>
                </tr>
            </table>
        </div>
        <div style="width: 48%;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="background-color: #555; color: white;">
                    <td colspan="2" style="padding: 8px;">To</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 8px;">{{ $transaction->affiliate?->firstname.' '.$transaction->affiliate?->lastname ?? 'Guest User' }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 8px;">{{ $transaction->affiliate->id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 8px;">{{ $transaction->affiliate->email ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Date & Invoice Number -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
        <table style="width: 48%; border-collapse: collapse;">
            <tr style="background-color: #555; color: white;">
                <td style="padding: 8px;">Date</td>
                <td style="padding: 8px;">Invoice No.</td>
            </tr>
            <tr>
                <td style="padding: 8px;">{{ $transaction->created_at->format('M d, Y') }}</td>
                <td style="padding: 8px;">{{ $transaction->trx_id.'-'.Str::random(5) }}</td>
            </tr>
        </table>
        <table style="width: 48%; border-collapse: collapse;">
            <tr style="background-color: #555; color: white;">
                <td style="padding: 8px;"><span class="info-label">Transaction Type</span></td>
            </tr>
            <tr>
                @if($transaction->trx_type == '+')
                    <span class="status-badge badge-success">Deposit</span>
                @else
                    <span class="status-badge badge-danger">Withdrawal</span>
                @endif
            </tr>
        </table>
    </div>

    <!-- Item Table -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <tr style="background-color: #e0e0e0;">
            <th style="padding: 10px; text-align: left;">Description</th>
            <th style="padding: 10px; text-align: center;">Type</th>
            <th style="padding: 10px; text-align: right;">Amount</th>
            <th style="padding: 10px; text-align: right;">Fee</th>
            <th style="padding: 10px; text-align: right;">Net Amount</th>
        </tr>
        <tr>
            <td style="padding: 10px;">{{ $transaction->remarks }}</td>
            <td style="padding: 10px; text-align: center;">
                @if($transaction->trx_type == '+')
                    <span class="text-success">Credit</span>
                @else
                    <span class="text-danger">Debit</span>
                @endif</td>
            <td style="padding: 10px; text-align: right;" class="{{ $transaction->trx_type === '+' ? 'text-success' : 'text-danger' }}">{{ $transaction->trx_type }} {{ currencyPosition($transaction->amount) }}</td>
            <td style="padding: 10px; text-align: right;" class="text-danger">{{ currencyPosition($transaction->charge) }}</td>
            <td style="padding: 10px; text-align: right;">@php
                    $netAmount = ($transaction->trx_type === '+')
                        ? $transaction->amount - $transaction->charge
                        : $transaction->amount + $transaction->charge;
                @endphp
                {{ currencyPosition($netAmount) }}</td>
        </tr>
    </table>

    <!-- Summary Totals -->
    <div style="display: flex; justify-content: flex-end;">
        <table style="width: 300px; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px;">Subtotal:</td>
                <td style="padding: 8px; text-align: right;">{{ currencyPosition($transaction->amount) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px;">Transaction Fee:</td>
                <td style="padding: 8px; text-align: right;">{{ currencyPosition($transaction->charge) }}</td>
            </tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td style="padding: 8px;">Net Amount:</td>
                <td style="padding: 8px; text-align: right;">{{ currencyPosition($netAmount) }}</td>
            </tr>
        </table>
    </div>

    <!-- Note -->
    <div class="footer-note" style="margin-top: 32px;padding-top: 16px;border-top: 1px solid var(--border-2);color: var(--text-color-1);font-size: 14px;">
        <p>This invoice was automatically generated on {{ now()->format('M d, Y H:i') }}.</p>
        <p>For any questions regarding this transaction, please contact our support team at {{ $businessInfo['contact_email'] }}</p>
    </div>

</div>

</body>

</html>
