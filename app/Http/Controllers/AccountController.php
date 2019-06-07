<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountMember;
use App\Address;
use App\ContactNumber;
use App\Destination;
use App\Email;
use App\Http\Controllers\AuditLogController;
use App\Invoice;
use App\InvoiceDetails;
use App\Member;
use App\MembershipType;
use App\Payment;
use App\UserGroup;
use App\Voucher;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user_group = Auth::user()->user_group;

            $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
            $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
            $modules_access = explode(',', $getModuleAccess);
            $modules_permissions = explode(',', $getModulePerms);

            $key = array_search('3', $modules_access);
            if (strpos($modules_permissions[$key], 'u') !== false) {
                return $next($request);
            }

            return redirect('/accounts');

        }, ['only' => ['editMembers', 'createPayment']]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($request->has('per_page')) {

            $per_page = $request->input('per_page');

        } else {
            $per_page = 10;
        }

        if ($request->has('sort')) {

            $sort = $request->input('sort');
            $dir = $request->input('dir');

        } else {
            $sort = 'created_at';
            $dir = 'desc';
        }

        $portal = session('portal');

        $accounts = Account::whereHas('members', function ($q) use ($portal, $search) {
            $q->where('members.membership_type', $portal);
            $q->where(function ($query) use ($search) {
                $query->where('members.first_name', 'like', '%' . $search . '%');
                $query->orWhere('members.last_name', 'like', '%' . $search . '%');
            });
        })
            ->where('membership_type', $portal)
            ->orderBy($sort, $dir)
            ->paginate($per_page);

        return view('accounts.index', compact('accounts', 'search', 'per_page'));
    }

    public function show(Account $account)
    {
        $account_id = $account->id;

        $invoices = Invoice::where('account_id', $account_id)->with('payments')->get();
        $vouchers = Voucher::where('account_id', $account->id)->with('destination')->get();
        $destinations = Destination::all();

        return view('accounts.show', compact('account', 'invoices', 'vouchers', 'destinations'));
    }

    public function editMembers(Account $account)
    {
        $membership_types = MembershipType::all();

        $accountMembers = AccountMember::where('account_id', $account->id)->get();
        $first = $accountMembers->first();
        $firstMember = Member::where('id', $first->member_id)->with(['membershipType', 'email', 'contactNumbers', 'addresses'])->first();

        $array_members = AccountMember::select('member_id')->where('account_id', $account->id)->where('member_id', '<>', $first->member_id)->get()->toArray();
        $otherMembers = Member::whereIn('id', $array_members)
            ->with(['membershipType', 'email', 'contactNumbers', 'addresses'])
            ->get();

        // dd($firstMember->email);

        return view('accounts.members', compact('account', 'firstMember', 'otherMembers', 'membership_types', 'countries'));
    }

    public function addMember(Account $account)
    {
        $member_count = AccountMember::where('account_id', $account->id)->count();

        if ($member_count >= 4) {
            return redirect('/accounts');
        }

        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

        $membership_types = MembershipType::all();
        $members = Member::where('membership_type', $account->membership_type)->get();

        return view('accounts.add_member', compact('account', 'members', 'membership_types', 'countries'));
    }

    public function storeMember(Request $request)
    {
        if ($request->member_type == 'new') {
            $request->validate([
                'first_name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'last_name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'status' => 'required',
                'email' => 'required|unique:emails,email_address|max:255',
                'contact' => 'required|max:255',
                'contact_type' => 'required',
                'contact2' => 'nullable|max:255',
                'contact_type2' => 'required_with:contact2',
                'house_number' => 'required|max:30',
                'subdivision' => 'nullable|string|max:30',
                'barangay' => 'nullable|string|max:30',
                'city' => 'required|max:50',
                'state' => 'required|max:50',
                'country' => 'required|max:30',
                'postal_code' => 'required|max:15',
                'birthday' => 'required|date',
                'gender' => 'required',
            ]);

            $member = new Member;

            $member->first_name = $request->first_name;
            $member->middle_name = $request->middle_name;
            $member->last_name = $request->last_name;
            $member->membership_type = $request->membership_type;
            $member->birthday = $request->birthday;
            $member->gender = $request->gender;
            $member->status = $request->status;
            $member->created_by = Auth::user()->username;

            $member->save();

            //Created member id
            $member_id = $member->id;

            //Contact Number
            $contact = new ContactNumber;

            $contact->member_id = $member_id;
            $contact->contact_number = $request->contact;
            $contact->contact_type = $request->contact_type;
            $contact->save();

            if ($request->has('contact2') && $request->contact2 != '' && $request->contact2 != null) {
                $contact->member_id = $member_id;
                $contact->contact_number = $request->contact2;
                $contact->contact_type = $request->contact_type2;
                $contact->save();
            }

            //Address
            $address = new Address;
            $address->member_id = $member_id;
            $address->house_number = $request->house_number;
            $address->subdivision = $request->subdivision;
            $address->barangay = $request->barangay;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->country = $request->country;
            $address->postal_code = $request->postal_code;
            $address->save();

            //Email
            $email = new Email;
            $email->email_address = $request->email;
            $email->member_id = $member_id;
            $email->save();

        } else { //Existing member
            
            $request->validate([
                'member_input' => 'required',
                'member' => 'required_if:member_type,existing|nullable',
            ]);
            $member_id = $request->member;
        }

        $account_id = $request->account;
        $account = Account::find($account_id);

        $accountMember = new AccountMember;
        $accountMember->account_id = $account_id;
        $accountMember->member_id = $member_id;
        $accountMember->save();

        $accountMember_id = $accountMember->id;

        $auditLog = new AuditLogController;
        $description = 'created member: ' . $member_id . ', for account: ' . $account_id;
        $auditLog->store($description, 2, $request->post());

        return redirect('/accounts/members/' . $account_id)->with('message', 'Member has been added.');
    }

    public function removeMember(Request $request)
    {
        $accountMember = AccountMember::where('account_id', $request->account)->where('member_id', $request->member)->first();

        $accountMember->delete();

        $auditLog = new AuditLogController;
        $description = 'removed member #: ' . $request->member . ' from account: ' . $request->account;
        $auditLog->store($description, 3, $request->post());

        return redirect('/accounts/members/' . $request->account)->with('message', 'Member has been removed.');
    }

    public function createInvoice(Request $request)
    {
        $account_id = $request->account_id;

        $validator = Validator::make($request->all(), [
            'invoice_date' => 'required|date',
            'due_at' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*' => 'required',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|numeric',
            'total' => 'required|gt:0',
        ]);

        if ($validator->fails()) {
            return redirect('/accounts/' . $account_id)->withErrors($validator, 'invoiceErrors')->withInput();
        }

        $invoice = new Invoice;

        $invoice->account_id = $request->account_id;
        $invoice->principal_amount = $request->total;
        $invoice->downpayment = 0;
        $invoice->total_paid_amount = 0;
        $invoice->remaining_balance = $request->total;
        $invoice->status = 'draft';
        $invoice->created_by = Auth::user()->username;

        $invoice->save();

        DB::table('invoices')->where('id', $invoice->id)->update(['invoice_number' => sprintf('%07d', $invoice->id)]);

        foreach ($request->items as $i => $item) {
            $invoiceDetails = new InvoiceDetails;

            $invoiceDetails->invoice_id = $invoice->id;
            $invoiceDetails->item = $request->items[$i];
            $invoiceDetails->quantity = $request->quantities[$i];
            $invoiceDetails->unit_price = $request->unit_prices[$i];
            $invoiceDetails->amount = $request->amounts[$i];

            $invoiceDetails->save();
        }

        $auditLog = new AuditLogController;
        $description = 'created invoice: ' . $invoice->id . ' for account: ' . $request->account . '.';
        $auditLog->store($description, 8, $request->post());

        return redirect('/accounts/' . $account_id)->with('message', 'Invoice has been added.');
    }

    public function createPayment(Request $request)
    {
        $invoice_id = $request->invoice_input;

        $remainingBalance = Invoice::where('id', $invoice_id)->value('remaining_balance');
        $account_id = $request->account_id;

        $validator = Validator::make($request->all(), [
            'invoice' => 'required|exists:invoices,id',
            'payment_date' => 'required',
            'amount' => 'required|lte:' . $remainingBalance,
            'percent_rate' => 'required|numeric',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/accounts/' . $account_id)->withErrors($validator, 'paymentErrors')->withInput();
        }

        $payment = new Payment;

        $payment->invoice_id = $request->invoice_input;
        $payment->payment_date = $request->payment_date;
        $payment->amount = $request->amount;
        $payment->percent_rate = $request->percent_rate;
        $payment->comment = $request->comment;
        $payment->created_by = Auth::user()->username;

        $payment->save();

        //Invoice
        $amountToBePaid = $request->amount;

        $invoices = Invoice::where('id', $invoice_id);
        $invoices->increment('total_paid_amount', $amountToBePaid);
        $invoices->decrement('remaining_balance', $amountToBePaid);

        if ($remainingBalance - $amountToBePaid <= 0) {

            Invoice::where('id', $invoice_id)
                ->update(['status' => 'full']);

        } elseif ($remainingBalance == 0 && $amountToBePaid > 0) {

            Invoice::where('id', $invoice_id)
                ->update(['status' => 'partial']);
        }

        $auditLog = new AuditLogController;
        $description = 'created payment: ' . $payment->id . ' for invoice: ' . $invoice_id . '.';
        $auditLog->store($description, 5, $request->post());

        return redirect('/accounts/' . $account_id)->with('message', 'Payment has been recorded.');
    }

    public function createVoucher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member' => 'required|exists:members,id', /* account id */
            'status' => 'required',
            'card_number' => 'required|unique:vouchers|regex:/(^([A-Za-z0-9 ][-_.,]?)+$)+/',
            'date_issued' => 'required|date',
            'valid_from' => 'required|date|after_or_equal:date_issued',
            'valid_to' => 'required|date|after:valid_from',
            'destination' => 'required',
            'remarks' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/accounts/' . $request->member)->withErrors($validator, 'voucherErrors')->withInput();
        }

        $voucher = new Voucher;

        $voucher->account_id = $request->member;
        $voucher->card_number = $request->card_number;
        $voucher->status = $request->status;
        $voucher->date_issued = $request->date_issued;
        $voucher->valid_from = $request->valid_from;
        $voucher->valid_to = $request->valid_to;
        $voucher->destination_id = $request->destination;
        $voucher->remarks = $request->remarks;
        $voucher->created_by = Auth::user()->username;

        $voucher->save();

        $auditLog = new AuditLogController;
        $description = 'created voucher: ' . $voucher->id;
        $auditLog->store($description, 4, $request->post());

        return redirect('/accounts/' . $request->member)->with('message', 'Voucher has been added.');
    }
}
