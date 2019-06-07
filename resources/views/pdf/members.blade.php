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
    <table class="table table-bordered" id="members-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
        <th>{{ $members->first_name }}</th>
        <th>{{ $members->middle_name }}</th>
        <th>{{ $members->last_name }}</th>
        </tbody>
    </table>
    <center>
        <h1>Members Report</h1>
        <h2>{{ $type }} members</h2>
        <h3>Filter Information</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sales Deck</th>
                    <th>Full Name</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Birthday</th>
                    <th class="text-center">Gender</th>
                    <th class="text-center">Contact No.</th>
                    <th class="text-center">Address</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Unused Vouchers</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->accounts[0]->sales_deck}}</td>
                        <td>{{ $member->first_name}}{{ $member->middle_name}} {{$member->last_name }}</td>
                        <td>{{ $member->status }}</td>
                        <td>{{ $member->birthday }}</td>
                        <td>{{ $member->gender == null ? '-' : $member->gender }}</td>
                        <td>{{ $member->contactNumbers[0]->contact_number }}</td>
                        <td>{{ $member->addresses[0]->complete_address }}</td>
                        <td>{{ $member->email['email_address']}}</td>
                        <td>{{ $member->accounts[0]->vouchers->count()}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </center>
    <p style="text-align: right;"><em>Report generated at {{ date('Y-m-d H:i:s') }}</em></p>
</body>
</html>
