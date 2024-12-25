<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    //
    //
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function changePassword(Request $request)
    {
        $current_pass = $request->current_password;
        $new_password = $request->new_password;
        if (!Hash::check($current_pass, Auth::user()->password)) {
            return response()->json([
                'error' => true,
                'msg' => 'Mật khẩu hiện tại không đúng',
                'alertType' => 'error',
            ]);
        }
        $user = $this->userService->changePassword($new_password);

        return response()->json([
            'error' => false,
            'msg' => 'Thay đổi mật khẩu thành công',
            'alertType' => 'success',
        ]);
    }

    public function getUserList(Request $request)
    {
        $result = $this->userService->getUserList($request->all());

        return response()->json([
            'data' => $result
        ]);
    }

    public function deleteUser(Request $request)
    {
        $result = $this->userService->delete($request->id);

        return response()->json([
            'error' => false,
            'msg' => 'Xóa thành công',
            'alertType' => 'success',
        ]);
    }

    public function resendEmail(Request $request)
    {

        $result = $this->userService->resendEmail($request->id);
        if ($result['status'] == 'error') {
            return response()->json([
                'error' => true,
                'msg' => 'Gửi thất bại',
                'alertType' => 'error',
            ]);
        }
        return response()->json([
            'error' => false,
            'msg' => 'Gửi thành công',
            'alertType' => 'success',
        ]);
    }
}
