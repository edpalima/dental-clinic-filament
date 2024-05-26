<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        /*
  Common invoice styles. These styles will work in a browser or using the HTML
  to PDF anvil endpoint.
*/

        body {
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            padding: 0;
        }

        table tr td:last-child {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .large {
            font-size: 1.75em;
        }

        .total {
            font-weight: bold;
            color: #061c34;
        }

        .logo-container {
            color: #061c34;
            margin: 20px 0 70px 0;
        }

        .invoice-info-container {
            font-size: 0.875em;
        }

        .invoice-info-container td {
            padding: 4px 0;
        }

        .client-name {
            font-size: 1.5em;
            vertical-align: top;
        }

        .line-items-container {
            margin: 70px 0;
            font-size: 0.875em;
        }

        .line-items-container th {
            text-align: left;
            color: #999;
            border-bottom: 2px solid #ddd;
            padding: 10px 0 15px 0;
            font-size: 0.75em;
            text-transform: uppercase;
        }

        .line-items-container th:last-child {
            text-align: right;
        }

        .line-items-container td {
            padding: 15px 0;
        }

        .line-items-container tbody tr:first-child td {
            padding-top: 25px;
        }

        .line-items-container.has-bottom-border tbody tr:last-child td {
            padding-bottom: 25px;
            border-bottom: 2px solid #ddd;
        }

        .line-items-container.has-bottom-border {
            margin-bottom: 0;
        }

        .line-items-container th.heading-quantity {
            width: 50px;
        }

        .line-items-container th.heading-price {
            text-align: right;
            width: 100px;
        }

        .line-items-container th.heading-subtotal {
            width: 100px;
        }

        .payment-info {
            width: 38%;
            font-size: 0.75em;
            line-height: 1.5;
        }

        .footer {
            margin-top: 100px;
        }

        .footer-thanks {
            font-size: 1.125em;
        }

        .footer-thanks img {
            display: inline-block;
            position: relative;
            top: 1px;
            width: 16px;
            margin-right: 4px;
        }

        .footer-info {
            float: right;
            margin-top: 5px;
            font-size: 0.75em;
            color: #ccc;
        }

        .footer-info span {
            padding: 0 5px;
            color: black;
        }

        .footer-info span:last-child {
            padding-right: 0;
        }

        .page-container {
            display: none;
        }

        /*
  The styles here for use when generating a PDF invoice with the HTML code.

  * Set up a repeating page counter
  * Place the .footer-info in the last page's footer
*/

        .footer {
            margin-top: 30px;
        }

        .footer-info {
            float: none;
            position: running(footer);
            margin-top: -25px;
        }

        .page-container {
            display: block;
            position: running(pageContainer);
            margin-top: -25px;
            font-size: 12px;
            text-align: right;
            color: #999;
        }

        .page-container .page::after {
            content: counter(page);
        }

        .page-container .pages::after {
            content: counter(pages);
        }


        @page {
            @bottom-right {
                content: element(pageContainer);
            }

            @bottom-left {
                content: element(footer);
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        Page <span class="page"></span> of <span class="pages"></span>
    </div>

    <div class="logo-container">
        Almoro Santiago Dental Clinic
    </div>

    <table class="invoice-info-container">
        <tr>
            <td rowspan="2" class="client-name">
                {{ $appointment->patient->fullname }}
            </td>
            <td>
                {{ $appointment->doctor->fullname }}
            </td>
        </tr>
        <tr>
            <td>
                {{ $appointment->doctor->contact_no }}
            </td>
        </tr>
        <tr>
            <td>
                Invoice Date: <strong>{{ \Carbon\Carbon::parse($appointment->date)->translatedFormat('F j, Y') }}</strong>
            </td>
            <td>
                {{ $appointment->doctor->address }}
            </td>
        </tr>
        <tr>
            <td>
                Invoice No: <strong>{{ $appointment->id }}</strong>
            </td>
            <td>
                {{ $appointment->doctor->email }}
            </td>
        </tr>
    </table>

    <table class="line-items-container">
        <thead>
            <tr>
                <th class="heading-quantity">Qty</th>
                <th class="heading-description">Description</th>
                <th class="heading-price">Price</th>
                <th class="heading-subtotal">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $appointment->procedure->name }}</td>
                <td class="right">P{{ number_format($appointment->procedure->cost, 2) }}</td>
                <td class="bold">P{{ number_format($appointment->procedure->cost, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="line-items-container has-bottom-border">
        <thead>
            <tr>
                <th>Payment Info</th>
                <th>Due By</th>
                <th>Total Due</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="payment-info">
                    <div>
                        Patient No: <strong>{{ $appointment->patient->id }}</strong>
                    </div>
                    <div>
                        Payment Option: <strong>{{ $appointment->payment_options }}</strong>
                    </div>
                    @if ($appointment->payment_options == 'gcash')
                        <div>
                            Reference Number: <strong>{{ $appointment->reference_number }}</strong>
                        </div>
                    @endif
                    <div>
                        Appointment Status: <strong>{{ $appointment->status }}</strong>
                    </div>
                </td>
                <td class="large">
                    {{ \Carbon\Carbon::parse($appointment->date)->addDays(30)->translatedFormat('F j, Y') }}</td>
                <td class="large total">P{{ number_format($appointment->procedure->cost, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
