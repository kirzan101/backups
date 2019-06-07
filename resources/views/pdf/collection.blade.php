<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        table {
            border-collapse: separate; 
            border-spacing: 75px 10px;
        }

        table thead td {
            background-color: #d0d5dd;
            border-bottom:1px solid black;
        }
    </style>
</head>
<body>
    <center>
        <h1>Collection Report</h1>
        <h4>{{ $fromMonth . ' '. $fromYear . ' - ' . $toMonth . ' ' . $toYear }}</h4>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $i=>$c)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $c->year }}</td>
                        <td>{{ $c->month }}</td>
                        <td style="text-align: right;">Php {{ number_format($c->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="text-align: right;"><strong>Total: <u>Php {{ number_format($grand_total, 2) }}</u></strong></td>
                </tr>
            </tfoot>                        
        </table>
    </center>
    <p style="text-align: right;"><em>Report generated at {{ date('Y-m-d H:i:s') }}</em></p>
</body>
</html>