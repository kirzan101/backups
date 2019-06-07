<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditLogController;
use App\Account;
use App\AccountMember;
use App\Address;
use App\Destination;
use App\AuditLog;
use App\ContactNumber;
use App\Consultant;
use App\Email;
use App\Invoice;
use App\Member;
use App\MembershipType;
use App\Payment;
use App\UserGroup;
use App\Voucher;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class MemberController extends Controller
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

            $key = array_search('2', $modules_access);
            if (strpos($modules_permissions[$key], 'r') !== false) {
                return $next($request);
            }

            return redirect('/members');

        }, ['only' => ['show']]);

        $this->middleware(function ($request, $next) {
            $user_group = Auth::user()->user_group;

            $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
            $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
            $modules_access = explode(',', $getModuleAccess);
            $modules_permissions = explode(',', $getModulePerms);

            $key = array_search('2', $modules_access);
            if (strpos($modules_permissions[$key], 'c') !== false) {
                return $next($request);
            }

            return redirect('/members');

        }, ['only' => ['create']]);

        $this->middleware(function ($request, $next) {
            $user_group = Auth::user()->user_group;

            $getModuleAccess = UserGroup::where('id', $user_group)->value('modules_access');
            $getModulePerms = UserGroup::where('id', $user_group)->value('modules_permissions');
            $modules_access = explode(',', $getModuleAccess);
            $modules_permissions = explode(',', $getModulePerms);

            $key = array_search('2', $modules_access);
            if (strpos($modules_permissions[$key], 'u') !== false) {
                return $next($request);
            }

            return redirect('/members');

        }, ['only' => ['edit']]);
    }

    public function index(Request $request)
    {
        $query = Member::query();
        $search = $request->input('search');
        $portal = session('portal');

        $query = $query->where('membership_type', $portal);
    
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%');
                $q->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('per_page')) {
            $per_page = $request->input('per_page');
        } else {
            $per_page = 10;
        }

        if ($request->has('sort') && $request->input('sort') != 'account') {
            $sort = $request->input('sort');
            $dir = $request->input('dir');
            $query = $query->orderBy($sort, $dir);
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }

        $query = $query->with(['email']);
        $members = $query->paginate($per_page);

        return view('members.index', compact('members', 'search', 'per_page'));
    }

    public function show(Member $member)
    {
        $members = Member::get();

        $accountMember = AccountMember::where('member_id', $member->id)->pluck('account_id')->toArray();
        $accounts = Account::whereIn('id', $accountMember)->with(['consultant', 'members'])->get();
        $invoices = Invoice::whereIn('account_id', $accounts->pluck('id')->toArray())->get();
        $payments = Payment::whereIn('invoice_id', $invoices->pluck('id')->toArray())->get();
        $vouchers = Voucher::whereIn('account_id', $accounts->pluck('id')->toArray())->with('destination')->get();
        $destinations = Destination::all();

        $emails = Email::where('member_id', $member->id)->get();
        $contacts = ContactNumber::where('member_id', $member->id)->get();
        $address = Address::where('member_id', $member->id)->first();
        $memberType = MembershipType::where('id', $member->membership_type)->first();

        // removed the 'consultants',
        return view('members.show', compact('members', 'accounts',  'invoices', 'payments', 'vouchers', 'member', 'emails', 'contacts', 'address', 'memberType', 'destinations'));
    }

    public function create()
    {
        $portal = session('portal');
        $membership_type = MembershipType::find($portal);

        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

        $accounts = Account::whereHas('accountMember', function ($query) {
                        $query->select(DB::raw('account_id', 'member_id'));
                        $query->groupBy('account_id');
                        $query->havingRaw('count(*) < 4');
                    })
                    ->where('membership_type', $portal)
                    ->get();

        return view('members.create', compact('accounts', 'membership_type', 'countries'));
    }

    public function store(Request $request)
    {
        $fullname = $request->first_name . ' ' . $request->last_name;
        $this->validate(
            $request,
            [
                'account_input' => 'required_if:account_type,existing|nullable',
                'account' => 'required_if:account_type,existing|nullable|numeric',
                'principal_amount' => 'required_if:account_type,new|nullable|numeric|gt:0|min:0|max:1000000',
                'downpayment' => 'required_if:account_type,new|nullable|numeric|min:0|lte:principal_amount',
                'sales_deck' => 'required_if:account_type,new|nullable|max:255',
                'consultant' => 'required_if:account_type,new|nullable|regex:/^[\pL\s\-]+$/u|max:255',
                'first_name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'last_name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'status' => 'required',
                'membership_type' => 'required',
                'email' => 'nullable|email|unique:emails,email_address|unique:users,email|max:255',
                'second_email' => 'nullable|email|different:email|unique:emails,email_address|max:255',
                'contact' => 'array',
                'contact.*' => 'nullable|required_with:contact_type.*|numeric',
                'contact_type' => 'array',
                'contact_type.*' => 'nullable|required_with:contact.*|string',
                'house_number' => 'required|max:30',
                'subdivision' => 'nullable|string|max:30',
                'barangay' => 'nullable|string|max:30',
                'city' => 'required|max:50',
                'state' => 'required|max:50',
                'country' => 'required|max:30',
                'postal_code' => 'required|max:15',
                'birthday' => 'required|date|before_or_equal:'.\Carbon\Carbon::now()->subYears(18)->format('Y-m-d'),
                'gender' => 'required',
            ],
            [
                'account_input.required_if' => 'Please select an account.',
                'downpayment.lte' => 'The downpayment should not be greater than the principal amount.',
                'email.required_if' => 'The email is required.',
                'contact.*.required_with' => 'Please enter a contact number.',
                'contact.*.numeric' => 'The contact number field must be a number.',
                'contact_type.*.required_with' => 'The contact type is required.',
                'principal_amount.required_if' => 'The principal amount field is required.',
                'downpayment.required_if' => 'The downpayment field is required.',
                'sales_deck.required_if' => 'The sales deck field is required.',
                'consultant.required_if' => 'The consultant field is required.',
            ]
        );

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

        //Contact Numbers
        if ($request->contact[0] != null){
            foreach ($request->contact as $i=>$con){
                $contact = new ContactNumber;

                $contact->member_id = $member_id;
                $contact->contact_number = $con;
                $contact->contact_type = $request->contact_type[$i];
                $contact->save();
            }
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
        if ($request->email != null){
            $email = new Email;
            $email->email_address = $request->email;
            $email->member_id = $member_id;
            $email->save();

            $second_email = $request->second_email;
            if($second_email != null){
                $email = new Email;
                $email->email_address = $request->second_email;
                $email->member_id = $member_id;
                $email->save();
            }
        }

        $account_type = $request->account_type;

        if($account_type == 'new'){
            //Check if the consultant already exists in the database
            $consultant_exists = DB::table('consultants')->where('name', $request->consultant)->exists();

            if (!$consultant_exists) {
                //Create new consultant if consultant doesnt exist
                $consultant_id = DB::table('consultants')->insertGetId(array(
                    'name' => $request->consultant,
                ));
            } else {
                $consultant_id = DB::table('consultants')->where('name', $request->consultant)->value('id');
            }

            //Account
            $account = new Account;
            $account->membership_type = $request->membership_type;
            $account->sales_deck = $request->sales_deck;
            $account->consultant_id = $consultant_id;
            $account->created_by = Auth::user()->username;

            $account->save();
            $account_id = $account->id;

            //Invoice
            $downpayment = $request->downpayment;
            $principal_amount = $request->principal_amount;

            if ($downpayment == 0) {
                $status = 'draft';
            } elseif ($downpayment == $principal_amount) {
                $status = 'full';
            } else {
                $status = 'partial';
            }

            $invoice = new Invoice;
            $invoice->account_id = $account_id;
            $invoice->principal_amount = $principal_amount;
            $invoice->downpayment = $downpayment;
            $invoice->total_paid_amount = $downpayment;
            $invoice->remaining_balance = $principal_amount - $downpayment;
            $invoice->status = $status;

            $invoice->save();
            
            DB::table('invoices')->where('id', $invoice->id)->update(['invoice_number' => sprintf('%07d', $invoice->id)]);

            $invoice_id = $invoice->id;

            //Add downpayment to payments if not 0
            if ($downpayment > 0) {
                $payment = new Payment;
                $payment->invoice_id = $invoice_id;
                $payment->payment_date = date('Y-m-d');
                $payment->amount = $downpayment;
                $payment->percent_rate = 0;
                $payment->comment = 'downpayment';
                $payment->created_by = Auth::user()->username;

                $payment->save();
                $payment_id = $payment->id;
            } else {
                $payment_id = 'none'; //for audit log description
            }

            //AccountMember
            $accountMember = new AccountMember;
            $accountMember->account_id = $account_id;
            $accountMember->member_id = $member_id;

            $accountMember->save();
            $accountMember_id = $accountMember->id;

            $auditLog = new AuditLogController;
            $description = 'Created member: ' . $fullname . 'with id no. : '. $member_id. ', account: ' . $account_id . ', invoice: ' . $invoice_id . ', payment : ' . $payment_id;
            $auditLog->store($description, 2, $request->post());

        } elseif($account_type == 'existing') {

            $account_id = $request->account;
            $account = Account::find($account_id);

            $accountMember = new AccountMember;
            $accountMember->account_id = $account_id;
            $accountMember->member_id = $member_id;
            $accountMember->save();

            $accountMember_id = $accountMember->id;

            $auditLog = new AuditLogController;
            $description = 'created member: ' . $member_id . ', for account id: ' . $account_id;
            $auditLog->store($description, 2, $request->post());

        } else {
            //No account
            DB::table('members')
            ->where('id', $member->id)
            ->update([
                'status' => 'pending',
            ]);

            $auditLog = new AuditLogController;
            $description = 'created member: ' . $fullname . ' with id: ' . $member_id;
            $auditLog->store($description, 2, $request->post());
        }

        return redirect('/members')->with('message', 'Member has been added.');
    }

    public function edit(Member $member)
    {
        $getEmails = DB::table('emails')->where('member_id', $member->id)->get();
        $emails = $getEmails->toArray();
        
        if (count($emails) == 0){
            $primary_email = null;
            $secondary_email = null;
        } else if(count($emails) > 1){
            $primary_email = $emails[0];
            $secondary_email = $emails[1];
        } else {
            $primary_email = $getEmails->first();
            $secondary_email = null;
        }

        $contact = DB::table('contact_numbers')->where('member_id', $member->id)->first();
        if ($contact != null){
            $contacts = DB::table('contact_numbers')->where('member_id', $member->id)->where('contact_number', '<>', $contact->contact_number)->get();
        }
        
        $address = DB::table('addresses')->where('member_id', $member->id)->first();
        $memberType = DB::table('membership_types')->where('id', $member->membership_type)->first();

        $membership_types = MembershipType::all();

        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

        return view('members.edit', compact('member', 'primary_email', 'secondary_email', 'contact', 'contacts', 'address', 'memberType', 'contactType', 'membership_types', 'countries'));
    }

    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'first_name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'last_name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50',
                'status' => 'required',
                'membership_type' => 'required',
                'email_address' => [
                    'required_if:checkHasNoEmail,0',
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('emails')->ignore($request->old_email_id),
                    'unique:users,email',
                ],
                'second_email' => [
                    'nullable',
                    'email',
                    'different:email_address',
                    'max:255',
                    Rule::unique('emails', 'email_address')->ignore($request->old_second_email_id),
                ],
                'main_contact' => 'nullable|required_with:main_contact_type|numeric',
                'main_contact_type' => 'nullable|required_with:main_contact',
                'contact' => 'array',
                'contact.*' => 'nullable|required_with:contact_type.*|numeric',
                'contact_type' => 'array',
                'contact_type.*' => 'nullable|required_with:contact.*|string',
                'house_number' => 'required|max:30',
                'subdivision' => 'nullable|string|max:30',
                'barangay' => 'nullable|string|max:30',
                'city' => 'required|max:50',
                'state' => 'required|max:50',
                'country' => 'required|max:30',
                'postal_code' => 'required|max:15',
                'birthday' => 'required|date|before_or_equal:'.\Carbon\Carbon::now()->subYears(18)->format('Y-m-d'),
                'gender' => 'required',
            ],
            [
                'email_address.required_if' => 'The email address field is required.',
                'contact.*.required_with' => 'Please enter a contact number.',
                'contact_type.*.required_with' => 'The contact type is required.',
                'main_contact.required_with' => 'Please enter a contact number.',
                'main_contact_type.required_with' => 'The contact type is required.',
            ]
        );

        $member = Member::find($request->member_id);

        $member->first_name = $request->first_name;
        $member->middle_name = $request->middle_name;
        $member->last_name = $request->last_name;
        $member->membership_type = $request->membership_type;
        $member->birthday = $request->birthday;
        $member->gender = $request->gender;
        $member->status = $request->status;
        $member->updated_by = Auth::user()->username;

        $member->save();

        $main_contact = DB::table('contact_numbers')->where('member_id', $member->id)->first();
        if ($main_contact != null){
        DB::table('contact_numbers')
            ->where('id', $main_contact->id)
            ->update([
                'contact_number' => $request->main_contact,
                'contact_type' => $request->main_contact_type,
            ]);
        } else {
            if ($request->main_contact != null){
                $contact = new ContactNumber;
                $contact->member_id = $request->member_id;
                $contact->contact_number = $request->main_contact;
                $contact->contact_type = $request->main_contact_type;
                $contact->save();
            }
        }

        //Contact Numbers
        $removed = $request->removed;
        $removed_array = explode(',', $removed);

        foreach ($removed_array as $rem){
            DB::table('contact_numbers')->where('id', $rem)->delete();
        }
        
        if (!empty($request->contact)){
            foreach ($request->contact as $i => $con) {

                if(!array_key_exists($i, $request->contact_id)){
                    $contactNumber = new ContactNumber;
                    $contactNumber->member_id = $request->member_id;
                    $contactNumber->contact_number = $con;
                    $contactNumber->contact_type = $request->contact_type[$i];
                    $contactNumber->save();

                    continue;
                }

                $res = DB::table('contact_numbers')
                ->where('id', $request->contact_id[$i])
                ->get();

                //Insert if new contact number
                if ($res->count() == 0) {
                    //Update
                    DB::table('contact_numbers')
                    ->where('id', $request->contact_id[$i])
                    ->update([
                        'contact_number' => $con,
                        'contact_type' => $request->contact_type[$i],
                    ]);
                }
            }
        }

        //If member has an old email stored in database
        if ($request->has('old_email') && $request->old_email != null && $request->old_email != ''){
            $old_email = $request->old_email;

            //Deleted main email
            if ($request->hasNoEmail){
                DB::table('emails')
                ->where('email_address', $old_email)
                ->delete();

                $old_second_email = $request->old_second_email;
                if ($old_second_email != null)
                {
                    DB::table('emails')
                    ->where('email_address', $old_second_email)
                    ->delete();
                }
            }

            else {
                //Email has been changed
                if ($request->email_address != $old_email){
                DB::table('emails')
                    ->where('email_address', $old_email)
                    ->update(['email_address' => $request->email_address]);
                }

                $old_second_email = $request->old_second_email;

                if ($request->second_email != null && $request->second_email != $old_second_email){
                    DB::table('emails')
                    ->where('email_address', $old_second_email)
                    ->update(['email_address' => $request->second_email]);
                }

                //Create new
                if ($old_second_email == null && $request->second_email != null){
                    $email = new Email;
                    $email->email_address = $request->second_email;
                    $email->member_id = $request->member_id;
                    $email->save();
                }

                //Deleted second email
                if ($old_second_email != null && $request->second_email == null){
                    DB::table('emails')
                    ->where('email_address', $old_second_email)
                    ->delete();
                }
            }
        } 
        
        //If member has previously no email, and then created one
        else {
            if ($request->email_address != null || $request->email_address != ''){
                $email = new Email;
                $email->email_address = $request->email_address;
                $email->member_id = $request->member_id;
                $email->save();

                $second_email = $request->second_email;
                if($second_email != null){
                    $email = new Email;
                    $email->email_address = $request->second_email;
                    $email->member_id = $request->member_id;
                    $email->save();
                }
            }
        }


        DB::table('addresses')
            ->where('member_id', $request->member_id)
            ->update([
                'house_number' => $request->house_number,
                'subdivision' => $request->subdivision,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
            ]);
        $fullname = $request->first_name. ' ' . $request->last_name;

        $auditLog = new AuditLogController;
        $description = 'updated member: ' . $request->member_id;
        $auditLog->store($description, 2, $request->post());

        return redirect('/members/' . $request->member_id)->with('message', 'Member has been updated.');
    }

    public function createPayment(Request $request)
    {
        // dd($request->all());
        $remainingBalance = Invoice::where('id', $request->invoice)->value('remaining_balance');
        
        $validator = Validator::make($request->all(), [
            'invoice' => 'required', //invoice no.
            'member_id' => 'required|exists:members,id',
            'payment_date' => 'required',
            'amount' => 'required|lte:' . $remainingBalance,
            'percent_rate' => 'required|numeric',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/members/' . $request->member_id)->withErrors($validator, 'paymentErrors')->withInput();
        }

        $payment = new Payment;

        $payment->invoice_id = $request->invoice;
        $payment->payment_date = $request->payment_date;
        $payment->amount = $request->amount;
        $payment->percent_rate = $request->percent_rate;
        $payment->comment = $request->comment;
        $payment->created_by = Auth::user()->username;

        $payment->save();

        //Invoice
        $amountToBePaid = $request->amount;
        $invoices = Invoice::where('id', $request->invoice);
        $invoices->increment('total_paid_amount', $amountToBePaid);
        $invoices->decrement('remaining_balance', $amountToBePaid);

        if ($remainingBalance - $amountToBePaid <= 0) {

            Invoice::where('id', $invoice_id)
                ->update(['status' => 'full']);

        } elseif ($remainingBalance == 0 && $amountToBePaid > 0) {

            Invoice::where('id', $invoice_id)
                ->update(['status' => 'partial']);
        }

        //Create entry in audit log
        $inserted_id = $payment->id;

        $first = Member::where("id", $request->member_id)->value('first_name');
        $last = Member::where("id", $request->member_id)->value('last_name');

        $auditLog = new AuditLogController;
        $description = 'created payment for: ' . $first ." ". $last . 'payment id no. '. $inserted_id . 'for invoice no. ' . $request->invoice;
        $auditLog->store($description, 5, $request->post());

        return redirect('/members/' . $request->member_id)->with('message', 'Payment has been recorded.');
    }

    public function createVoucher(Request $request)
    {
        
        if ($request->status == 'redeemed') {
            $date_redeemed = date('Y-m-d');
        } else {
            $date_redeemed = null;
        }

        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
            'card_number' => 'required|unique:vouchers|regex:/(^([A-Za-z0-9 ][-_.,]?)+$)+/',
            'status' => 'required',
            'date_issued' => 'required|date',
            'valid_from' => 'required|date|after_or_equal:date_issued',
            'valid_to' => 'required|date|after:valid_from',
            'destination' => 'required',
            'remarks' => 'required|max:255',
        ]);
     
        if ($validator->fails()) {
            return redirect('/members/' . $request->member_id)
            ->withErrors($validator, 'voucherErrors')
            ->withInput();
        }
        
        $voucher = new Voucher;

        // $voucher->member_id = DB::table('members')->where('id', $request->member)->value('id');
        // $voucher->membership_type = DB::table('members')->where('id', $request->member)->value('membership_type');
        $voucher->account_id = $request->account_id;
        $voucher->card_number = $request->card_number;
        $voucher->status = $request->status;
        $voucher->date_issued = $request->date_issued;
        $voucher->date_redeemed = $date_redeemed;
        $voucher->valid_from = $request->valid_from;
        $voucher->valid_to = $request->valid_to;
        $voucher->destination_id = $request->destination;
        $voucher->remarks = $request->remarks;

        $voucher->save();

        //Created voucher id
        $inserted_id = $voucher->id;

        $members_id = DB::table('members')->where('id', $request->member)->value('id');
        
        $auditLog = new AuditLogController;
        $description = 'created voucher: ' . $inserted_id;
        $auditLog->store($description, 4, $request->post());

        return redirect('/members/' . $request->member_id)->with('message', 'Voucher has been added.');
    }
}
