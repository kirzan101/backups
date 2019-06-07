<?php

use App\Account;
use App\AccountMember;
use App\Address;
use App\Consultant;
use App\ContactNumber;
use App\Email;
use App\Invoice;
use App\Member;
use App\Payment;
use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Member::class, 100)
        //     ->create()
        //     ->each(function ($m) {
        //         $m->email()->save(factory(App\Email::class)->make());
        //         $m->contactNumbers()->save(factory(App\ContactNumber::class)->make());
        //         $m->addresses()->save(factory(App\Address::class)->make());
        //         $m->accountMember()->save(factory(App\AccountMember::class)->make());
        //     });

        //Individual members
        $i_members = DB::table('member_profile')
            ->where('fullname', 'not like', '%/%')
            ->where('fullname', 'like', '%,%')
            ->get();

        foreach ($i_members as $m) {
            $name = explode(",", $m->fullname);
            $lastName = $name[0];

            $splitName = $name[1];
            $separateName = explode(" ", $splitName);
            $middleName = end($separateName);

            if (strlen($middleName) <= 3 && strpos($middleName, '.') !== false) {
                $mname = $middleName;
                $getFName = explode($mname, $splitName);
                $firstName = $getFName[0];
            } else {
                $mname = null;
                $firstName = $splitName;
            }

            $member = new Member;

            $member->first_name = $firstName;
            $member->middle_name = $mname;
            $member->last_name = $lastName;
            $member->membership_type = 1;
            $member->birthday = $m->birthday;
            $member->status = strtolower($m->status);
            $member->created_at = $m->date_created;

            $member->save();

            $contact = new ContactNumber;
            $contact->member_id = $member->id;
            $contact->contact_number = $m->contact;
            $contact->contact_type = 'home';
            $contact->save();

            $member_email = $m->email;

            //Multiple emails
            if (strpos($member_email, '/') !== false || strpos($member_email, ';') !== false) {

                $split_emails = array();

                if (strpos($member_email, '/') !== false) {
                    $split_emails = explode('/', $member_email);
                } else if (strpos($member_email, ';') !== false) {
                    $split_emails = explode(';', $member_email);
                }

                $count = count($split_emails);

                //If more than 1 email
                if ($count > 1) {
                    for ($i = 0; $i <= 1; $i++) {
                        $email = new Email;
                        $email->email_address = $split_emails[$i];
                        $email->member_id = $member->id;
                        $email->save();
                    }
                }
            }

            //Single email
            else {
                $email = new Email;
                $email->email_address = $member_email;
                $email->member_id = $member->id;
                $email->save();
            }

            $address = new Address;
            $address->member_id = $member->id;
            $address->complete_address = $m->address;
            $address->save();

            $consultant = Consultant::where('name', $m->consultant)->first();

            $account = new Account;
            $account->membership_type = 1;
            $account->sales_deck = $m->sales_deck;
            $account->consultant_id = $consultant->id;
            $account->save();

            $accountMember = new AccountMember;
            $accountMember->account_id = $account->id;
            $accountMember->member_id = $member->id;
            $accountMember->save();

            $invoice = new Invoice;
            $invoice->account_id = $account->id;
            $invoice->principal_amount = $m->principal_amount;
            $invoice->downpayment = $m->down_payment;
            $invoice->total_paid_amount = $m->paid_amount != null ? $m->paid_amount : 0;
            $invoice->remaining_balance = $m->remaining_balance;

            if ($m->remaining_balance === 0) {
                $invoice->status = 'full';
            } elseif ($m->remaining_balance > 0 && $m->down_payment > 0) {
                $invoice->status = 'partial';
            } else {
                $invoice->status = 'draft';
            }

            $invoice->save();

            DB::table('invoices')->where('id', $invoice->id)->update(['invoice_number' => sprintf('%07d', $invoice->id)]);

            //Payments
            $payment_records = DB::table('member_payment_records')->where('pid', $m->id)->get();

            foreach ($payment_records as $p) {
                $payment = new Payment;
                $payment->invoice_id = $invoice->id;
                $payment->payment_date = $p->payment_date;
                $payment->amount = $p->payment_amount;
                $payment->percent_rate = $p->percent_rate;
                $payment->comment = $p->comments;
                $payment->save();
            }
        }

        //Multiple members
        $m_members = DB::table('member_profile')
            ->where('fullname', 'like', '%/%')
            ->where('fullname', 'like', '%,%')
            ->get();

        foreach ($m_members as $m) {
            $members = explode(" / ", $m->fullname);

            for ($i = 0; $i <= 1; $i++) {
                if (!array_key_exists(1, $members)) {
                    break;
                }

                $name = explode(",", $members[$i]);
                $lastName = $name[0];

                if (!array_key_exists(1, $name)) {
                    $mname = null;
                    $firstName = "test";

                } else {

                    $splitName = $name[1];
                    $separateName = explode(" ", $splitName);
                    $middleName = end($separateName);

                    if (strlen($middleName) <= 3 && strpos($middleName, '.') !== false) {
                        $mname = $middleName;
                        $getFName = explode($mname, $splitName);
                        $firstName = $getFName[0];
                    } else {
                        $mname = null;
                        $firstName = $splitName;
                    }
                }

                $member = new Member;

                $member->first_name = $firstName;
                $member->middle_name = $mname;
                $member->last_name = $lastName;
                $member->membership_type = 1;
                $member->birthday = $m->birthday;
                $member->status = strtolower($m->status);

                $member->save();

                $contact = new ContactNumber;
                $contact->member_id = $member->id;
                $contact->contact_number = $m->contact;
                $contact->contact_type = 'home';
                $contact->save();

                $member_email = $m->email;

                //Multiple emails
                if (strpos($member_email, '/') !== false || strpos($member_email, ';') !== false) {

                    $split_emails = array();

                    if (strpos($member_email, '/') !== false) {
                        $split_emails = explode('/', $member_email);
                    } else if (strpos($member_email, ';') !== false) {
                        $split_emails = explode(';', $member_email);
                    }

                    $count = count($split_emails);

                    //If more than 1 email
                    if ($count > 1) {
                        $email = new Email;
                        $email->email_address = $split_emails[$i];
                        $email->member_id = $member->id;
                        $email->save();
                    }
                }

                //Single email
                else {
                    $email = new Email;
                    $email->email_address = $member_email;
                    $email->member_id = $member->id;
                    $email->save();
                }

                $address = new Address;
                $address->member_id = $member->id;
                $address->complete_address = $m->address;
                $address->save();

                if ($i === 0) {
                    $consultant = Consultant::where('name', $m->consultant)->first();

                    $account = new Account;
                    $account->membership_type = 1;
                    $account->sales_deck = $m->sales_deck;
                    $account->consultant_id = $consultant->id;
                    $account->save();

                    $accountMember = new AccountMember;
                    $accountMember->account_id = $account->id;
                    $accountMember->member_id = $member->id;
                    $accountMember->save();

                } else {

                    $account = Account::select('id')->orderBy('id', 'desc')->first();
                    $accountMember = new AccountMember;
                    $accountMember->account_id = $account->id;
                    $accountMember->member_id = $member->id;
                    $accountMember->save();
                }

                $invoice = new Invoice;
                $invoice->account_id = $account->id;
                $invoice->principal_amount = $m->principal_amount;
                $invoice->downpayment = $m->down_payment;
                $invoice->total_paid_amount = $m->paid_amount != null ? $m->paid_amount : 0;
                $invoice->remaining_balance = $m->remaining_balance;

                if ($m->remaining_balance === 0) {
                    $invoice->status = 'full';
                } elseif ($m->remaining_balance > 0 && $m->down_payment > 0) {
                    $invoice->status = 'partial';
                } else {
                    $invoice->status = 'draft';
                }

                $invoice->save();

                DB::table('invoices')->where('id', $invoice->id)->update(['invoice_number' => sprintf('%07d', $invoice->id)]);

                //Payments
                $payment_records = DB::table('member_payment_records')->where('pid', $m->id)->get();

                foreach ($payment_records as $p) {
                    $payment = new Payment;
                    $payment->invoice_id = $invoice->id;
                    $payment->payment_date = $p->payment_date;
                    $payment->amount = $p->payment_amount;
                    $payment->percent_rate = $p->percent_rate;
                    $payment->comment = $p->comments;
                    $payment->save();
                }
            }
        }
    }
}
