@extends('layouts.admin')

@section('title')
    VoucherMS | Reports - Accounts
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Account Reports</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2><i class="fa fa-credit-card"></i> Accounts</h2>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">
                    <br>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatables">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Members</th>
                                    <th>Consultant</th>
                                    <th>Destination</th>
                                    <th class="text-center">Total Vouchers</th>
                                    <th class="text-center">Unused</th>
                                    <th class="text-center">Redeemed</th>
                                    <th class="text-center">Canceled</th>
                                    <th class="text-center">Forfeited</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> {{-- card --}}
        </div> {{-- col --}}
    </div> {{-- row --}}

@endsection

@section('scripts')
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.print.min.js"></script>

    <script>
        $(function() {
            $('#per_page').change(function() {
                this.form.submit();
            });

            $('#destination').change(function() {
                this.form.submit();
            });
        });

        $(document).ready(function () {
            $('#datatables').DataTable({
                initComplete: function () {
                    this.api().columns().every(function () {
                        var columns = this;
                        var select = $('<select><option value=""></option></select>').appendTo( $(column.footer()).empty() ).on( 'change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $this.val()
                            );

                            column.search( val ? '^' + val + '$' : '', true, false ).draw();
                        });

                        column.data().unique().sort().each( function (d, j) {
                            select.append('<option value="'+d+'">'+d+'</option>')
                        });
                    })
                },
                ajax: "{{ route('dtAccounts')}}",
                lengthMenu: [[25,100,-1], ['25 rows','100 rows','All']],
                pageLength: 25,
                lengthChange: true,
                columns: [
                    {
                        data: 'id', name: 'id'
                    },
                    {
                        data: 'm_name', name: 'm_name'
                    },
                    {   
                        data: 'c_name', name: 'c_name'
                    },
                    {
                        data:'destination_name', name:'destination_name'
                    },
                    {
                        data: 'total', name: 'total'
                    },
                    {
                        data: 'unused', name: 'unused'
                    },
                    {
                        data: 'redeemed', name: 'redeemed'
                    },
                    {
                        data: 'canceled' , name: 'canceleds'
                    },
                    {
                        data: 'forfeited'  , name:'forfeited'
                    }         
                ],
                dom: 'Blfrtip',
                buttons: {
                    dom: {
                        button: {
                            tag:'button',
                            className: ' '
                        },
                    },
                buttons: [
                    {
                        extend: 'excel',
                        text: '<span class="fa fa-file-excel-o"></span> Excel Export',
                        className: 'btn btn-success mr-3 mb-3',
                        exportOptions: {
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                            }
                        }
                    }, 
                    {
                        extend: 'pdf',
                        text: '<span class="fa fa-file-excel-o"></span> PDF Export',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        className: "btn btn-success mr-3 mb-3",
                        exportOptions: {
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                            }
                        }
                    }
                ]
            }  
        });
    });
</script>
@endsection