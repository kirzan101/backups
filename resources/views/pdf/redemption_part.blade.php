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

        <hr>
        
        @if ($type == 'byAccount')
            <p>Redemptions of account with member(s):</p>
                @foreach ($vouchers as $v)
                    @foreach ($v->account->members as $m)
                        <p><strong>{{ $m->last_name . ', ' . $m->first_name }}</strong></p>
                    @endforeach
                    @break
                @endforeach
        @else
            <p>Redemptions for: <strong>{{ $member }}</strong></p>
        @endif

        <p>From <strong>{{ $from }}</strong> to <strong>{{ $to }}</strong> in destination: <strong>{{ $des }}</strong></p>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Card Number</th>
                    <th>Date Issued</th>
                    <th>Status</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $i=>$v)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $v->card_number }}</td>
                        <td>{{ date('M d, Y' , strtotime($v->date_issued)) }}</td>
                        <td>{{ strtoupper($v->status) }}</td>
                        <td>{{ date('M d, Y' , strtotime($v->valid_from)) }}</td>
                        <td>{{ date('M d, Y' , strtotime($v->valid_to)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </center>
    <p style="text-align: right;"><em>Report generated at {{ date('Y-m-d H:i:s') }}</em></p>
</body>
</html>