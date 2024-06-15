<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class ImportUsersDataController extends Controller
{
    public function index()
    {
        return view('dashboard.importUsers');
    }

    public function store(Request $request)
    {
        $data = $request->input('data');

        $validator = Validator::make($data, [
            '*.name' => 'required|string|max:255',
            '*.email' => 'required|email|unique:users,email',
            '*.role' => 'required|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        foreach ($data as $row) {
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => bcrypt('123456789'),
            ]);

            $role = Role::where('name', $row['role'])->first();
            $user->assignRole($role);
        }

        return response()->json(['message' => 'Users imported successfully.']);
    }


    public function parseExcel(Request $request)
    {
        $file = $request->file('excelFile');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        // Expected headers
        $expectedHeaders = ['name', 'email', 'role'];

        if (empty($data)) {
            return response()->json(['error' => 'No data found in the file.'], 400);
        }

        // Extract headers and validate
        $headers = array_map('strtolower', array_values(array_filter(array_shift($data), function ($header) {
            return !is_null($header);
        })));

        \Log::info('Extracted headers:', $headers);

        if (array_diff($expectedHeaders, $headers)) {
            return response()->json(['error' => 'Invalid headers in the file. Expected headers: name, email, role.'], 400);
        }

        $formattedData = [];
        $emails = [];
        $invalidRows = [];
        $validRoles = Role::pluck('name')->toArray(); // Get all valid roles from the database
        $existingEmails = User::pluck('email')->toArray(); // Get all existing emails from the database

        foreach ($data as $index => $row) {
            \Log::info('Processing row:', $row);

            $filteredRow = [];
            foreach ($headers as $headerIndex => $header) {
                $filteredRow[$header] = $row[array_keys($row)[$headerIndex]] ?? null;
            }

            $email = strtolower($filteredRow['email']);
            $role = strtolower(trim($filteredRow['role'])); // Convert role to lowercase and trim spaces

            if (empty($email)) {
                $invalidRows[] = "Row " . ($index + 2) . ": Email is required.";
                continue;
            }

            if (in_array($email, $existingEmails)) {
                $invalidRows[] = "Row " . ($index + 2) . ": Email already exists in the database - " . $filteredRow['email'];
                continue;
            }

            if (in_array($email, $emails)) {
                $invalidRows[] = "Row " . ($index + 2) . ": Duplicate email found - " . $filteredRow['email'];
                continue;
            }

            if ($role && !in_array($role, $validRoles)) {
                $invalidRows[] = "Row " . ($index + 2) . ": Invalid role - " . $filteredRow['role'];
                continue;
            }

            if (!empty(array_filter($filteredRow))) {
                $emails[] = $email;
                $formattedData[] = $filteredRow;
            }
        }

        \Log::info('Formatted data:', $formattedData);

        return response()->json([
            'headers' => $expectedHeaders,
            'data' => $formattedData,
            'invalidRows' => $invalidRows
        ]);
    }

    public function validateUserData(Request $request)
    {
        // Log incoming request data for debugging
        \Log::info('Request data:', $request->all());

        $email = strtolower(trim($request->input('email')));
        $role = strtolower(trim($request->input('role')));

        // Validate request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        \Log::info('Email:', ['email' => $email]);

        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            return response()->json(['error' => 'Email already exists.'], 203);
        }

        $validRole = Role::where('name', $role)->first();
        if (!$validRole) {
            return response()->json(['error' => 'Invalid role.'], 203);
        }

        return response()->json(['message' => 'Validation successful.']);
    }



}
