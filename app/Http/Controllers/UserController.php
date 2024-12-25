<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Imports\UsersImport;
use App\Jobs\ImportUsersJob;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    //
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService,
    ) {}


    public function index()
    {
        return view('pages.user_list.index');
    }
    public function profile()
    {
        return view('pages.profile.index');
    }

    public function updateProfile(Request $request)
    {
        // dd($request->file('avatar'), $request);
        $data = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'phone' => 'nullable',
            'email' => 'required',
            'address' => 'nullable',
            'password' => 'required',
            'role_id' => 'required',
            'avatar' => 'nullable',
        ]);
        $result = $this->userService->updateProfile($request->all());
        // dd($result);
        return redirect()->route('profile')->with(['message' => $result['msg'], 'alert-type' => $result['status']]);
    }

    public function addUserView()
    {
        $roles = $this->roleService->getRoles();
        return view('pages.user_list.add', compact('roles'));
    }


    public function editUserView(Request $request)
    {
        $user = $this->userService->getUser(['id' => $request->id]);
        $roles = $this->roleService->getRoles();

        return view('pages.user_list.edit', compact('user', 'roles'));
    }

    public function show(Request $request)
    {
        $user = $this->userService->getUserShow(['id' => $request->id]);

        $roles = $this->roleService->getRoles();
        return view('pages.user_list.show', compact('user', 'roles'));
    }
    public function edit(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required',
                'phone' => 'nullable',
                'email' => [
                    'required',
                    Rule::unique('users')->ignore($request->id),
                ],
                'address' => 'nullable',
                // 'password' => 'required',
                // 'password_confirmation' => 'required|same:password',
                'role_id' => 'required',
                'avatar' => 'nullable',
            ],
            [
                'name.required' => 'Trường họ và tên không được bỏ trống',
            ]
        );
        // dd(1);
        $result = $this->userService->updateProfile($request->all());
        return redirect()->route('user.list')->with(['message' => $result['msg'], 'alert-type' => $result['status']]);

        // return view('pages.user_list.edit', compact('user', 'roles'));
    }

    public function deleteUser(Request $request)
    {
        // dd(1);
        $result = $this->userService->delete($request->id);

        return redirect()->route('user.list')->with(['message' => $result['msg'], 'alert-type' => $result['status']]);
    }
    public function addUser(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required',
                'phone' => 'nullable',
                'email' => 'required|unique:users',
                'address' => 'nullable',
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
                'role_id' => 'required',
                'avatar' => 'nullable',
            ],
            [
                'name.required' => 'Trường họ và tên không được bỏ trống',
            ]
        );

        $result = $this->userService->create($request->all());
        return redirect()->route('user.list')->with(['message' => $result['msg'], 'alert-type' => $result['status']]);
    }

    public function export()
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $path = $request->file('file')->store('temp');
        $jobId = uniqid(); // Generate a unique ID for the job

        try {
            Cache::put('import_status_' . $jobId, [
                'status' => 'pending',
                'message' => 'Job is still processing'
            ], now()->addMinutes(10));

            ImportUsersJob::dispatch($path, $jobId)->onQueue('default');

            return response()->json([
                'status' => 'success',
                'message' => 'Import thành công',
                'jobId' => $jobId
            ]);
        } catch (\Throwable $th) {
            Log::error("Error importing user", [$th]);
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function checkImportStatus($jobId)
    {
        $job = \Illuminate\Support\Facades\Cache::get('import_status_' . $jobId);

        if ($job) {
            return response()->json([
                'status' => $job['status'],
                'message' => $job['message']
            ]);
        } else {
            return response()->json([
                'status' => 'pending',
                'message' => 'Job is still processing'
            ]);
        }
    }
}
