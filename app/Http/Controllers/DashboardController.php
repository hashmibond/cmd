<?php

namespace App\Http\Controllers;

use App\Models\TerminalDataReceive;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $terminalAllData=TerminalDataReceive::orderBy('id', 'DESC')->get();
        return view('admin.dashboard',compact('terminalAllData'));
    }

    public function datatable(Request $request)
    {
        $Query = TerminalDataReceive::latest()->take(5000)->get();
        return DataTables::of($Query)->addIndexColumn()->make();
    }
}
