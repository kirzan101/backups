@extends('layouts.admin')

@section('title')
    VoucherMS | Redemptions
@endsection

@section('content')

        {{-- start modal update --}}
            <div>
                <div class="modal fade bd-example-modal-lg" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <form method="POST" action='/redemptions/redeemed'>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addModalLabel"><i class="fa fa-fw fa-plus"></i>Redeem Voucher</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">                        
                                    {{ csrf_field() }}

                                    <div class="form-row">
                                        {{-- Date Redeemed --}}
                                        <div class="form-group col-md-4">
                                            <label for="voucher_id">Voucher No.</label>
                                            <input type="text" class="form-control" id="voucher_number" name="voucher_number" value="{{ !old('voucher_number') ? "": old('voucher_number') }}" readonly>
                                            <input type="hidden" id="voucher_id" name="voucher_id" value="{{ !old('voucher_id') ? "": old('voucher_id') }}">
                                            @if ($errors->has('voucher_id'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('voucher_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                        
                                        {{-- Dates Valid --}}
                                        <div class="form-group col-md-4">
                                            <label for="valid_from">Valid From</label>
                                            <input type="date" class="form-control {{ $errors->has('valid_from') ? ' is-invalid' : '' }}" id="valid_from" name="valid_from" value="{{ !old('valid_from') ? " ": old('valid_from') }}" disabled>
                                            @if ($errors->has('valid_from'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('valid_from') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="valid_to">Valid To</label>
                                            <input type="date" class="form-control {{ $errors->has('valid_to') ? ' is-invalid' : '' }}" id="valid_to" name="valid_to" value="{{ !old('valid_to') ? " ": old('valid_to') }}" disabled>
                                            @if ($errors->has('valid_to'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('valid_to') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>     
                            
                                    <input type="text" class="form-control {{ $errors->has('voucher_id') ? ' is-invalid' : '' }}" id="voucher_id" name="voucher_id" value="{{ !old('valid_from') ? " ": old('valid_from') }}" hidden>
                                    <input type="date" class="form-control {{ $errors->has('valid_from') ? ' is-invalid' : '' }}" id="valid_from" name="valid_from" value="{{ !old('valid_from') ? " ": old('valid_from') }}" hidden>
                                    <input type="date" class="form-control {{ $errors->has('valid_to') ? ' is-invalid' : '' }}" id="valid_to" name="valid_to" value="{{ !old('valid_to') ? " ": old('valid_to') }}" hidden>

                                    <hr>
                                
                                    <div id="redeemInput">
                                        <h5>Redeem Information</h5>
                                        <div class="form-row">
                                            {{-- Date Redeemed --}}
                                            <div class="form-group col-md-12">
                                                <label for="date_redeemed">Date Redeemed</label>
                                                <input type="date" class="form-control {{ $errors->has('date_redeemed') ? ' is-invalid' : '' }}" id="date_redeemed" name="date_redeemed" value="{{ !old('date_redeemed') ? date("Y-m-d")  : old('date_redeemed') }}" placeholder="Date Redeem">
                                                @if ($errors->has('date_redeemed'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('date_redeemed') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="on" id="check_guest" name="check_guest" {{ old('check_guest') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="check_guest">
                                                    Redeemed by other guest
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row mt-3" id="row_members">
                                            <div class="form-group col-md-12">
                                                <label for="members_list">Members</label>
                                                <input type="text" readonly class="form-control" id="members_list" name="members_list" value="{{ old('members_list') }}">
                                            </div>
                                        </div>

                                        <div class="form-row mt-3" id="guest_row">                                    
                                            {{-- Guest First Name  --}}
                                            <div class="form-group col-md-4">
                                                <label for="guest_first_name">Guest First Name</label>
                                                <input type="text" class="form-control {{ $errors->has('guest_first_name') ? ' is-invalid' : '' }}" id="guest_first_name" name="guest_first_name" value="{{ !old('guest_first_name') ? "" : old('guest_first_name') }}" placeholder="First Name">
                                                @if ($errors->has('guest_first_name'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('guest_first_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Guest Middle Name --}}
                                            <div class="form-group col-md-4">
                                                <label for="guest_middle_name">Guest Middle Name</label>
                                                <input type="text" class="form-control {{ $errors->has('guest_middle_name') ? ' is-invalid' : '' }}" id="guest_middle_name" name="guest_middle_name" value="{{ !old('guest_middle_name') ? "" : old('guest_middle_name') }}" placeholder="Middle Name">
                                                @if ($errors->has('guest_middle_name'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('guest_middle_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        
                                            {{-- Guest Last Name --}}
                                            <div class="form-group col-md-4">
                                                <label for="guest_last_name">Guest Last Name</label>
                                                <input type="text" class="form-control {{ $errors->has('guest_last_name') ? ' is-invalid' : '' }}" id="guest_last_name" name="guest_last_name" value="{{ !old('guest_last_name') ? "" : old('guest_last_name') }}" placeholder="Last Name">
                                                @if ($errors->has('guest_last_name'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('guest_last_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h5>Check In Details</h5>

                                        <div class="form-row">
                                            {{-- Check In Date --}}
                                            <div class="form-group col-md-6">
                                                <label for="check_in_date">Check In Date</label>
                                                <input type="date" class="form-control {{ $errors->has('check_in_date') ? ' is-invalid' : '' }}" id="check_in_date" name="check_in_date" value="{{ !old('check_in_date') ? date("Y-m-d") : old('check_in_date') }}" placeholder="Payment Date">
                                                @if ($errors->has('check_in_date'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('check_in_date') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                        {{-- Check In Time --}}
                                        <div class="form-group col-md-6">
                                                <label for="check_in_time">Check In Time</label>
                                                <input type="time" class="form-control {{ $errors->has('check_in_time') ? ' is-invalid' : '' }}" id="check_in_time" name="check_in_time" value="" placeholder="Payment time">
                                                @if ($errors->has('check_in_time'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('check_in_time') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                        {{-- Check Out Date --}}
                                            <div class="form-group col-md-6">
                                                <label for="check_out_date">Check Out Date</label>
                                                <input type="date" class="form-control {{ $errors->has('check_out_date') ? ' is-invalid' : '' }}" id="check_out_date" name="check_out_date" value="{{ !old('check_out_date') ? date("Y-m-d") : old('check_out_date') }}" placeholder="Payment Date">
                                                @if ($errors->has('check_out_date'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('check_out_date') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                

                                            {{-- Check Out time --}}
                                            <div class="form-group col-md-6">
                                                <label for="check_out_time">Check Out Time</label>
                                                <input type="time" class="form-control {{ $errors->has('check_out_time') ? ' is-invalid' : '' }}" id="check_out_time" name="check_out_time" value="" placeholder="Payment time">
                                                @if ($errors->has('check_out_time'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('check_out_time') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                            <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="destination">Destination</label>
                                                <select class="form-control {{ $errors->has('destination') ? ' is-invalid' : '' }}" id="destination" name="destination">
                                                    @foreach ($destinations as $des)
                                                        <option value="{{ $des->id }}" {{ old('destination') == $des->id ? 'selected' : '' }} >{{ $des->code }} - {{ $des->destination_name }}</option>
                                                    @endforeach
                                                </select>
                                                {{-- <input class="form-control {{ $errors->has('destination') ? ' is-invalid' : '' }}" id="destination_input" name="destination_input" list="destinations" placeholder="Begin typing to search for destination" autocomplete="off">
                                                <datalist id="destinations">
                                                    @foreach ($destinations as $list) 
                                                        <option id="{{ $list->id }}" value="{{ $list->id }}">
                                                            {{ $list->destination_name}}
                                                        </option>
                                                    @endforeach
                                                </datalist> --}}
                                                @if ($errors->has('destination'))
                                                    <span class="invalid-feedback">
                                                        <strong>Please select a valid destination.</strong>
                                                    </span>
                                                @endif

                                                {{-- <input type="hidden" id="destination" name="destination" value="{{ old('destination') }}"> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- end of form --}}
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        {{-- end modal update  --}}

        {{-- breadcrumbws --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Redemptions</li>
            </ol>
        </nav>

        {{-- messages --}}
        <div class="row">
            <div class="col-12">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <i class="fa fa-fw fa-check"></i> {{ session()->get('message') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-fw fa-calendar"></i> Redemptions</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="/redemptions">
                    <div class="row mb-3">                    
                        <div class="col-7"></div>
                        <div class="col-5">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search for redemptions" aria-label="Search" value="{{ $search }}">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-search"></i> Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <form method="GET" action="/audit-log" class="form-inline">
                            <div class="row">
                                <div class="col-6">
                                     <label class="my-1 mr-2" for="per_page">Entries per page: </label>
                                    <select class="custom-select my-1 mr-sm-2 col-sm-3" id="per_page" name="per_page">
                                        <option value="10" {{ $per_page == '10' ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $per_page == '25' ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $per_page == '50' ? 'selected' : '' }}>50</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    
                        @if ($search != '')
                        <p>
                            Showing results for <strong>{{ $search }}</strong>
                        </p>
                        @endif

                        <table class="table table-hover" style="font-size:12px;">
                            <thead>
                                <tr>
                                    <th class="text-center"># <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Member(s) <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=account_id&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=account_id&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Voucher Number <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=card_number&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=card_number&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Remarks <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=remarks&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=remarks&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Date Issued <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=date_issued&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=date_issued&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Valid From <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=valid_from&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=valid_from&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Valid To <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=valid_to&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=valid_to&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Status <a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=asc"><i class="text-right text-dark fa fa-fw fa-caret-up"></i></a><a href="/redemptions?search={{ $search }}&per_page={{ $per_page }}&sort=status&dir=desc"><i class="text-right text-dark fa fa-fw fa-caret-down"></i></a></th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($redemptions as $i=>$res)
                                    <tr>
                                        <th class="text-center">{{ $i + 1 + ($redemptions->perPage() * ($redemptions->currentPage() - 1)) }}</th>
                                    <td><a class="link-table open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach">
                                            @foreach($res->account->accountMember as $name)
                                                {{ $name->member->first_name . " " . $name->member->last_name }}
                                                <br>
                                            @endforeach
                                            </a>
                                        </td>
                                        <td><a class="link-table open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach">{{ $res->card_number }}</a></td>
                                        <td><a class="link-table open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach">{{ $res->remarks }}</a></td>
                                        <td><a class="link-table open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach">{{ $res->date_issued }}</a></td>
                                        <td><a class="link-table open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach">{{ $res->valid_from }}</a></td>
                                        <td><a class="link-table open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach">{{ $res->valid_to }}</a></td>
                                        <td><a class="link-table open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach"><strong>
                                            <p class=
                                                <?php 
                                                    if ($res->status == 'redeemed'){
                                                        echo 'text-success';
                                                    } else if ($res->status == 'canceled' || $res->status == 'forfeited') {
                                                        echo 'text-danger';
                                                    } else {
                                                        echo '';
                                                    }
                                                ?>>
                                                {{ strtoupper($res->status) }}</p>
                                        </strong></a></td>
                                        <td><a class="btn btn-success open-AddModal" href="" data-toggle="modal" data-target="{{ $res->status === "unused" ? "#addModal" : " " }}" data-id="{{ $res->id }}" data-desti={{ $res->destination_id }} data-number="{{ $res->card_number }}" data-from="{{ $res->valid_from }}" data-to="{{ $res->valid_to }}" data-members="@foreach($res->account->accountMember as $name){{ $name->member->first_name . " " . $name->member->last_name }}@if(!$loop->last){{ "," }} @endif @endforeach">Redeem</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{-- Pagination --}}
                        {{ $redemptions->appends([
                            'search' => $search,
                            'per_page' => $per_page,
                            'sort' => app('request')->input('sort'),
                            'dir' => app('request')->input('dir')
                        ])->links() }}

                    </div>
                </form>
            </div>
        </div> {{-- card --}}
    </div> {{-- redemptions --}}

 <style>
        .link-table {
            display: block;
            color:#0d0d0d;
        }

        .link-table:hover {
            text-decoration: none;
            color:#0d0d0d;
        }
    </style>

@endsection

<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>

@section('scripts')

        @if ($errors->any())
            <script>
                $('#addModal').modal('show');
            </script>
        @endif


    <script>
        $(document).ready(function () {            
            if ($('#check_guest').is(':checked')){
                $("#guest_row").show();
                $("#row_members").hide();
            } else {
                $("#guest_row").hide();
                $("#guest_first_name").val('');
                $("#guest_last_name").val('');
                $("#row_members").show();
            }
        });

        $(function() {
            $('#per_page').change(function() {
                this.form.submit();
            });
        });

        $('#check_guest').click(function() {
            $("#guest_row").toggle(this.checked);
            $("#guest_first_name").val('');
            $("#guest_last_name").val('');
            $("#row_members").toggle(!this.checked);
        });

        $(document).on("click", ".open-AddModal", function() {
            var voucherID = $(this).data('id');
            var voucherNumber = $(this).data('number');
            var validFrom = $(this).data('from');
            var validTo = $(this).data('to');
            var des = $(this).data('desti');
            var members = $(this).data('members');

            $(".modal-body #voucher_id").val( voucherID );
            $(".modal-body #voucher_number").val( voucherNumber );
            $(".modal-body #valid_to").val( validTo );
            $(".modal-body #valid_from").val( validFrom );
            $(".modal-body #destination").val( des );
            $(".modal-body #members_list").val(members);

            $(".modal-body .is-invalid").removeClass('is-invalid');
        });
    </script>

@endsection