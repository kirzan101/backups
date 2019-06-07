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
        <h2>Voucher Report</h2>
        <p>{{ $type }} members with <strong>{{ strtoupper($status) }}</strong> vouchers</p>
        <p>{{ date('M d, Y', strtotime($from)) }} - {{ date('M d, Y', strtotime($to)) }}</p>
        <table>
            <thead>
                <tr>
                    <th>Member(s)</th>
                    <th>Voucher Number</th>
                    <th>Status</th>
                    <th>Destination</th>
                    <th>Issued On</th>
                    <th>Redeemed On</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Guest Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td>
                            @foreach ($voucher->account->members as $m)
                                {{ $m->first_name . ' ' . $m->last_name . ', ' }}
                            @endforeach
                        </td>
                        <td>{{ $voucher->card_number }}</td>
                        <td>{{ strtoupper($voucher->status) }}</td>
                        <td>{{ $voucher->destination->destination_name }}</td>
                        <td>{{ date('m/d/Y', strtotime($voucher->date_issued)) }}</td>
                        <td>{{ $voucher->date_redeemed }}</td>
                        <td>{{ $voucher->check_in }}</td>
                        <td>{{ $voucher->check_out }}</td>
                        <td>{{ $voucher->guest_first_name . ' ' . $voucher->guest_last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </center>
    <p style="text-align: right;"><em>Report generated at {{ date('Y-m-d H:i:s') }}</em></p>
</body>
</html>