<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\NsapScheme;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class NSAPSchemeController extends Controller
{
    public function index()
    {
        return view('dashboard.importNsapSchemes');
    }

    public function store(Request $request)
    {
        $data = $request->input('data');

        // Validation rules
        $validator = Validator::make($data, [
            '*.scheme_code' => 'required|string|max:255',
            '*.scheme_name' => 'required|string|max:255',
            '*.central_state_scheme' => 'required|string|max:255',
            '*.fin_year' => 'required|string|max:255',
            '*.state_disbursement' => 'required|numeric',
            '*.central_disbursement' => 'required|numeric',
            '*.total_disbursement' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        foreach ($data as $row) {
            NsapScheme::create([
                'scheme_code' => $row['scheme_code'],
                'scheme_name' => $row['scheme_name'],
                'central_state_scheme' => $row['central_state_scheme'],
                'fin_year' => $row['fin_year'],
                'state_disbursement' => $row['state_disbursement'],
                'central_disbursement' => $row['central_disbursement'],
                'total_disbursement' => $row['total_disbursement'],
            ]);
        }

        return response()->json(['message' => 'NSAP schemes imported successfully.']);
    }



    public function parseExcel(Request $request)
    {
        $file = $request->file('excelFile');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        // Expected headers
        $expectedHeaders = [
            'id',
            'scheme_code',
            'scheme_name',
            'central_state_scheme',
            'fin_year',
            'state_disbursement',
            'central_disbursement',
            'total_disbursement'];

        if (empty($data)) {
            return response()->json(['error' => 'No data found in the file.'], 400);
        }

        // Extract headers and validate
        $headers = array_map('strtolower', array_values(array_filter(array_shift($data), function ($header) {
            return !is_null($header);
        })));

        \Log::info('Extracted headers:', $headers);

        if (array_diff($expectedHeaders, $headers)) {
            return response()->json(['error' => 'Invalid headers in the file. '], 202);
        }

        $formattedData = [];

        foreach ($data as $index => $row) {
            \Log::info('Processing row:', $row);

            $filteredRow = [];
            foreach ($headers as $headerIndex => $header) {
                $filteredRow[$header] = $row[array_keys($row)[$headerIndex]] ?? null;
            }

            $formattedData[] = $filteredRow;
        }

        \Log::info('Formatted data:', $formattedData);

        return response()->json([
            'headers' => $expectedHeaders,
            'data' => $formattedData,
        ]);
    }

    public function loadNsapSchemeView()
    {
        $nsapSchemes = NsapScheme::all();
        return view('dashboard.nsapScheme', compact('nsapSchemes'));
    }

    public function getNsapSchemeData($id)
    {
        $scheme = NsapScheme::findOrFail($id);
        return response()->json($scheme);
    }

    public function updateNsapSchemeData(Request $request, $id)
    {
        $scheme = NsapScheme::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'scheme_code' => 'required|string|max:255',
            'scheme_name' => 'required|string|max:255',
            'central_state_scheme' => 'required|string|max:255',
            'fin_year' => 'required|string|max:255',
            'state_disbursement' => 'required|numeric',
            'central_disbursement' => 'required|numeric',
            'total_disbursement' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $scheme->update($request->all());

        return response()->json(['message' => 'NSAP Scheme updated successfully.']);
    }

    public function destroyNsapSchemeData($id)
    {
        $scheme = NsapScheme::findOrFail($id);
        $scheme->delete();

        return response()->json(['message' => 'NSAP Scheme deleted successfully.']);
    }


}
