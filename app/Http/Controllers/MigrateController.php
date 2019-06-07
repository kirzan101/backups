<?php

namespace App\Http\Controllers;

use App\Consultant;
use App\ContactNumber;
use App\Member;
use DB;

class MigrateController extends Controller
{
    /**
     * Migrate old db to new db
     *
     * @return void
     */
    public function members()
    {
        //Individual members
        $i_members = DB::table('member_profile')
            ->where('fullname', 'not like', '%/%')
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

            $member->save();

            $contact = new ContactNumber;
            $contact->member_id = $member->id;
            $contact->contact_number = $m->contact;
            $contact->contact_type = 'home';
            $contact->save();
        }

        //Multiple members
        $m_members = DB::table('member_profile')
            ->where('fullname', 'like', '%/%')
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
            }
        }

        return redirect('/');
    }

    public function consultants()
    {
        $consultants = DB::table('member_profile')->select('consultant')->distinct()->get();

        foreach ($consultants as $c) {
            $consultant = new Consultant;
            $consultant->name = $c->consultant;
            $consultant->save();
        }

        return redirect('/');
    }
}
