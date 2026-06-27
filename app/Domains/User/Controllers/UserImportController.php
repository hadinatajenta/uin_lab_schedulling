<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Services\UserImportService;
use Illuminate\Http\Request;

class UserImportController extends Controller
{
    protected UserImportService $importService;

    public function __construct(UserImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        return view('users.import');
    }

    public function validateBulk(Request $request)
    {
        $type = $request->input('type');
        $rows = $request->input('rows', []);
        
        foreach ($rows as &$row) {
            $errors = $this->importService->validateSingleRow($type, $row);
            $row['errors'] = (object) $errors;
            $row['isValid'] = empty($errors);
        }

        return response()->json(['success' => true, 'data' => $rows]);
    }

    public function validateRow(Request $request)
    {
        $type = $request->input('type');
        $row = $request->input('row');
        
        $errors = $this->importService->validateSingleRow($type, $row);
        $row['errors'] = (object) $errors;
        $row['isValid'] = empty($errors);

        return response()->json(['success' => true, 'data' => $row]);
    }

    public function processBulk(Request $request)
    {
        $type = $request->input('type');
        $rows = $request->input('rows', []);
        
        $success = $this->importService->processBulk($type, $rows, \Illuminate\Support\Facades\Auth::id());

        if (!$success) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data valid untuk diproses.']);
        }

        session()->flash('success', '🚀 Import Sedang Diproses! Data pengguna sedang ditambahkan ke sistem di latar belakang. Silakan cek riwayat di Activity Logs.');

        return response()->json(['success' => true]);
    }
}
