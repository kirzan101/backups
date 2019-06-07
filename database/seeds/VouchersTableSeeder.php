<?php

use Illuminate\Database\Seeder;

class VouchersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Voucher::class, 70)->create();

        DB::insert("insert into vouchers (account_id, card_number,status,date_issued,valid_from, valid_to, remarks,destination_id, date_redeemed, check_in, check_out, guest_first_name, guest_middle_name, guest_last_name, created_by, updated_by, created_at, updated_at)

        select am.account_id, mv.voucher_card_no, lcase(mv.voucher_status), mv.date_issued,mv.valid_from, mv.valid_to, mv.voucher_name, 7, null, null, null, null, null, null,'-',null, IF(mv.date_created > '0000-00-00 00:00:00',mv.date_created,NOW()) date_created, IF(mv.date_updated > '0000-00-00 00:00:00',mv.date_updated,NULL) date_updated
        from member_profile mp
        inner join member_voucher mv on mp.id = mv.member_voucher_id
        inner join members m on  mp.fullname like CONCAT('%' , m.first_name, '%') and mp.fullname like CONCAT('%' , m.last_name , '%')
        inner join account_member am on m.id = am.member_id
        group by mv.voucher_card_no;");
    }

}
