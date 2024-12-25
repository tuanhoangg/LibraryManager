<?php

namespace App\Helper;

use App\Models\Members;
use App\Models\User;
use Illuminate\Support\Str;


class MemberHelper
{
    static public function generateMemberCode()
    {
        $prefix = 'MBR'; // Tiền tố cho mã thành viên
        $date = date('Ymd'); // Ngày hiện tại theo định dạng YYYYMMDD
        $randomNumber = mt_rand(1000, 9999); // Tạo số ngẫu nhiên có 4 chữ số

        $memberCode = $prefix . $date . $randomNumber;

        // Đảm bảo tính duy nhất của mã thành viên
        while (Members::where('member_code', $memberCode)->exists()) {
            $randomNumber = mt_rand(1000, 9999); // Tạo lại số ngẫu nhiên nếu trùng lặp
            $memberCode = $prefix . $date . $randomNumber;
        }

        return $memberCode;
    }
}
