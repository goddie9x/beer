<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $files = glob(env('FOLDER_REPORT_PATH') . '/*.xlsx');
        $filesInfo = [];
        foreach ($files as $key=>$file) {
            $filesInfo[] = [
                'name' => basename($file),
                'path' => $file
            ];
        }
        return view('frontend.report.index', compact('filesInfo'));
    }
    public function GetFileByPath(Request $request)
    {
        $path = $request->path;
        $name = $request->name;
        $headers = [
                'Content-Type'              => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Length'            => filesize($path),
                'Cache-Control'             => 'must-revalidate',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Disposition'       => 'attachment; filename="'.$name.'"'
        ];
        return response()->download($path, $name, $headers);
    }
    public function export() 
    {
        return new UsersExport('test.xlsx');
    }
}
