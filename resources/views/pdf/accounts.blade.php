<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse; 
            width: 100%;
        }

        table thead td {
            background-color: #d0d5dd;
            border-bottom:1px solid black;
        }

        table, th, td {
            border: 1px solid black;
            text-align: center;
        }
    </style>
</head>
<body>
    <center>
        <h1>Members Report</h1>
        <h2>{{ $type }} members</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Members</th>
                    <th>Consultant</th>
                    <th class="text-center">Total Vouchers</th>
                    <th class="text-center">Unused</th>
                    <th class="text-center">Redeemed</th>
                    <th class="text-center">Canceled</th>
                    <th class="text-center">Forfeited</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)                                    
                    <tr>
                        <td>{{ $account->id }}</td>
                        <td>{{ $account->m_name }}</td>
                        <td>{{ $account->c_name }}</td>
                        <td class="text-center">{{ $account->total }}</td>
                        <td class="text-center">{{ $account->unused }}</td>
                        <td class="text-center">{{ $account->redeemed }}</td>
                        <td class="text-center">{{ $account->canceled }}</td>
                        <td class="text-center">{{ $account->forfeited }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </center>
    <p style="text-align: right;"><em>Report generated at {{ date('Y-m-d H:i:s') }}</em></p>
</body>
</html>