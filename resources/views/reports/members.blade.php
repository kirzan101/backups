@extends('layouts.admin')

@section('title')
    VoucherMS | Reports - Members
@endsection

@section('content')
<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Members Reports</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2> <i class="fa fa-credit-card"></i>  Members</h2>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-striped table-bordered" id="members-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sales Deck</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Birthday</th>
                                    <th>Gender</th>
                                    <th>Contact Number</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Unused Vouchers</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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



<script type="text/javascript">
$(document).ready(function() {
    $('#members-table').DataTable( {
            // processing:true,
            // serverSide:true,
            ajax: "{{ route('allmembers')}}",
            ordering: true,
            lengthMenu: [[25,100,-1], ['25','100','All']],
            pageLength: 25,
            lenghtChange: false,
            columns: [
                {
                    data: 'id', name: 'id'
                },
                {
                    data: 'sales_deck', name: 'sales_deck'
                },
                {   data: null,
                    render: function (data, type, full) {
                    
                        if( full.middle_name === null ) {

                            return full['first_name']+' '+ full['last_name'];
                            
                        } else {
                            return full['first_name']+' '+full['middle_name']+' '+ full['last_name'];
                        }
                   
                    }
                },
                {
                    data: 'status', name: 'status'
                },
                {
                    data: 'birthday', name: 'birthday'
                },
                {
                    data: 'gender', name: 'gender'
                },
                {
                    data: 'contact_number' , name: 'contact_numbers'
                },
                {
                    data: 'complete_address'  , name:'complete_address'
                },
                {
                    data: 'email_address' , name: 'address'                    
                },
                {
                   data: 'voucherCount' , name : 'accounts.vouchers',
                }          
            ],
            dom:'Blfrtip',
            buttons: {
                dom: {
                    button: {
                        tag:'button',
                        className: ' '
                    }
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
                    // {
                    //     extend: 'pdf',
                    //     text: '<span class="fa fa-file-excel-o"></span> PDF Export',
                    //     orientation: 'landscape',
                    //     pageSize: 'LEGAL',
                    //     className: "btn btn-success",
                    //     exportOptions: {
                    //         modifier: {
                    //             search: 'applied',
                    //             order: 'applied',

                    //         }
                    //     }
                    // }
                ]
            } //end of button
        });
    });
</script>
@endsection