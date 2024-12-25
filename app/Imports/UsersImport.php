<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class UsersImport implements ToCollection
{
    protected $jobId;

    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }

    public function collection(Collection $rows)
    {
        // Skip header row
        $rows->shift();
        $error = 0;
        $currentRow = 0;
        foreach ($rows as $row) {
            $currentRow++;
            // Convert collection to array with named keys
            $rowArray = [
                'name' => $row[0],
                'phone' => $row[1],
                'email' => $row[2],
                'address' => $row[3],
                'password' => $row[4],
                'role_id' => $row[5],
                'avatar' => $row[6],
            ];
            DB::beginTransaction();
            try {
                // Validate the row data
                $validator = Validator::make($rowArray, [
                    'name' => 'required|string',
                    'phone' => 'nullable',
                    'email' => 'required|email|unique:users,email',
                    'address' => 'nullable|string',
                    'password' => 'required|string|min:6',
                    'role_id' => 'required|integer',
                    'avatar' => 'nullable|url',
                ]);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                // Insert or update user
                User::updateOrCreate(
                    ['email' => $rowArray['email']], // Check for existing email
                    [
                        'name' => $rowArray['name'],
                        'phone' => $rowArray['phone'],
                        'address' => $rowArray['address'],
                        'password' => Hash::make($rowArray['password']),
                        'role_id' => $rowArray['role_id'],
                        'avatar' => $rowArray['avatar'],
                    ]
                );
                DB::commit();
            } catch (ValidationException $e) {
                // Log validation errors and continue
                $error++;
                Log::error("Validation error for row: " . json_encode($rowArray) . " - " . $e->getMessage());
                // Optionally save error details to cache or database
                Cache::put('import_status_' . $this->jobId, [
                    'status' => 'error',
                    'message' => 'Tại dòng ' . $currentRow . ' ' . $e->getMessage()
                ], now()->addMinutes(10));
                // Continue processing other rows
                DB::rollBack();
                // continue;
            } catch (\Exception $e) {
                // Log general errors and continue
                $error++;
                Log::error("Error importing row: " . json_encode($rowArray) . " - " . $e->getMessage());
                Cache::put('import_status_' . $this->jobId, [
                    'status' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ], now()->addMinutes(10));
                // Continue processing other rows
                DB::rollBack();
                // continue;
            }
            if ($error == 0) {
                Cache::put('import_status_' . $this->jobId, [
                    'status' => 'success',
                    'message' => 'Import thành công'
                ], now()->addMinutes(10));
            }
        }
    }
}
