<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse; 
            width: 100%;
            table-layout: fixed;
        }

        table thead td {
            background-color: #d0d5dd;
            border-bottom:1px solid black;
        }

        table, th, td {
            border: 1px solid black;
            text-align: center;
        }

        td {
            white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
            white-space: -pre-wrap;      /* Opera 4-6 */
            white-space: -o-pre-wrap;    /* Opera 7 */
            white-space: pre-wrap;       /* css-3 */
            word-wrap: break-word;       /* Internet Explorer 5.5+ */
            word-break: break-all;
            white-space: normal;
        }
    </style>
</head>
<body>
    <center>
        <h1>Redemption Report</h1>
        
        @if ($type == 'monthly')
            <h3>{{ $year }} monthly redemption</h3>
        @else
            <h3>Yearly redemptions from {{ $from }} to {{ $to }}</h3>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Destination</th>
                    <th>Total Vouchers</th>
                    <th>Unused</th>
                    <th>Redeemed</th>
                    <th>Canceled</th>
                    <th>Forfeited</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $v)
                    <tr>
                        <td>{{ $v->month }} {{ $type == 'yearly' ? $v->year : '' }}</td>
                        <td>{{ $v->destination->destination_name }}</td>
                        <td>{{ $v->vouchers }}</td>
                        <td>{{ $v->unused }}</td>
                        <td>{{ $v->redeemed }}</td>
                        <td>{{ $v->canceled }}</td>
                        <td>{{ $v->forfeited }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </center>
    <p style="text-align: right;"><em>Report generated at {{ date('Y-m-d H:i:s') }}</em></p>
</body>
</html>