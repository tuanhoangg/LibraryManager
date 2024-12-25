<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
        //
    }

    public function findOne($data)
    {

        return $this->userRepository->with('role', 'members.membership_plan')->findOne($data);
    }
    public function getUser($data)
    {

        return $this->userRepository->with('role', 'members.membership_plan')->findOne($data);
    }

    public function getUserShow($data)
    {

        return $this->userRepository->getUser($data);
    }
    public function delete($id)
    {
        $result = $this->userRepository->delete($id);
        if ($result) {
            return [
                'status' => 'success',
                'msg' => 'Xóa thành công',
            ];
        }
        return [
            'status' => 'error',
            'msg' => 'Xóa thất bại',
        ];
    }

    public function getUserList($request)
    {
        return $this->userRepository->getUserList($request);
    }

    public function changePassword($newPassword)
    {
        $result = $this->userRepository->update(Auth::user()->id, ['password' => Hash::make($newPassword)]);

        return $result;
    }
    public function updateProfile($data)
    {
        if (isset($data['avatar'])) {
            $path = $data['avatar']->store('public/avatars');
            unset($data['avatar']);
            $data['image'] = Storage::url($path);
        }
        unset($data['_token']);

        $result = $this->userRepository->update($data['id'], $data);

        if ($result) {
            return [
                'status' => 'success',
                'msg' => 'Cập nhật thành công',
            ];
        }
        return [
            'status' => 'error',
            'msg' => 'Cập nhật thất bại',
        ];
    }

    public function create($data)
    {
        if (isset($data['avatar'])) {
            $path = $data['avatar']->store('public/avatars');
            unset($data['avatar']);
            $data['image'] = Storage::url($path);
        }
        unset($data['_token']);

        $result = $this->userRepository->create($data);

        if ($result) {
            return [
                'status' => 'success',
                'msg' => 'Thêm người thành công',
            ];
        }
        return [
            'status' => 'error',
            'msg' => 'Thêm người thất bại',
        ];
    }

    public function resendEmail($id)
    {
        $user = $this->userRepository->findOne(['id' => $id]);

        if (!$user) {
            return [
                'status' => 'error',
                'msg' => 'Không tìm thấy người dùng',
            ];
        }
        $user->sendEmailVerificationNotification();

        return [
            'status' => 'success',
            'msg' => 'Gửi thành công',
        ];
    }

    public function exportUserDetail()
    {
        return $this->userRepository->exportUserDetail();
    }
}
