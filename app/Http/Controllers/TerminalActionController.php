<?php

namespace App\Http\Controllers;

use App\Exports\TerminalExport;
use App\Http\Requests\StoreTerminalActionRequest;
use App\Http\Requests\UpdateTerminalActionRequest;
use App\Models\Terminal;
use App\Models\TerminalAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Toastr;
use Yajra\DataTables\DataTables;
use Session;
use Maatwebsite\Excel\Facades\Excel;

class TerminalActionController extends Controller
{

    public function index(Request $request)
    {
        /*$StartDate =  $request->has('StartDate') ? $request->StartDate : now()->modify('-30 day')->format('Y-m-d');*/
        $StartDate =  $request->has('StartDate') ? $request->StartDate : null;
        $EndDate = $request->has('EndDate') ? $request->EndDate : null;
        $terminalList= TerminalAction::all();
        if($request->has('download')){
                return Excel::download(new TerminalExport($request), "terminal_" . date('d_m_Y_H_i_s') . ".xlsx");
            }
        /*$terminals = DB::table('terminal_actions')->select('terminal_actions.*','users.name as userName')
                        ->leftJoin('users','users.id','=','terminal_actions.user_id')
                        ->whereBetween('terminal_actions.approved_at', [$StartDate . ' 00:00:00', $EndDate . ' 23:59:59'])
                        ->when($request->terminalId, function ($query) use($request) {
                            $query->where('terminal_actions.id', $request->terminalId);
                        })
                        ->get();*/
        //dd($request->all(),$StartDate,$EndDate,$terminals);
        return view('admin.terminal.list',compact('terminalList','StartDate','EndDate'));
    }

    public function datatable(Request $request)
    {
        $Query = TerminalAction::select('terminal_actions.*','users.name as userName')
                ->leftJoin('users','users.id','=','terminal_actions.user_id')
                ->when(request()->StartDate, function ($query, $StartDate) {
                    $query->where('terminal_actions.approved_at', '>=', $StartDate . ' 00:00:00');})
                ->when(request()->EndDate, function ($query, $EndDate) {
                    $query->where('terminal_actions.approved_at', '<=', $EndDate .  ' 23:59:59');})
                ->when(request()->terminalId, function ($query) use($request) {
                    $query->where('terminal_actions.id', $request->terminalId);})
                ->latest()->get();
        //dd(DataTables::of($Query));
        return DataTables::of($Query)
            ->addIndexColumn()
            ->addColumn('status', function(TerminalAction $terminalAction) {
                if ($terminalAction->status) { return '<span class="badge badge-success">Active</span>';}
                else { return '<span class="badge badge-danger">Inactive</span>';}})
            ->addColumn('is_approved', function(TerminalAction $terminalAction) {
                if ($terminalAction->is_approved) {return '<span class="badge badge-success">Active</span>';}
                else { return '<span class="badge badge-danger">Inactive</span>';}})
            ->addColumn('actions', function(TerminalAction $terminalAction) {
                /*if($terminalAction['user_id']) {
                    return '<a href=" ' . route('terminals.edit', $terminalAction->id) . ' " class="btn btn-default btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></a>';
                } else {
                    return "<a href=\" " . route('terminals.edit', $terminalAction->id) . " \" class=\"btn btn-default btn-xs m-r-5\"  data-toggle=\"tooltip\" data-original-title=\"Edit\"><i class=\"fa fa-pencil font-14\"></i></a>

                        <button data-id=\" $terminalAction->id \" class=\"btn btn-default btn-xs m-r-5 deleteUser \" data-original-title=\"Delete\"><i class=\"fa fa-trash-o font-14\"></i></button>
                        ";
                }*/
                return '<a href=" ' . route('terminals.edit', $terminalAction->id) . ' " class="btn btn-default btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></a>';
            })
            ->rawColumns(['status','is_approved','actions'])
            ->make();
    }
    /*<form action=\" " .  route('terminals.destroy', $terminalAction->id) ."\" method=\"POST\">
                            <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                            <input type=\"hidden\" name=\"_csrf\" value=\"CSRF\">
                            <button  type=\"submit\" class=\"btn btn-default btn-xs m-r-5\"  data-original-title=\"Delete\"><i class=\"fa fa-trash-o font-14\"></i></a>
                            </form>*/
    public function create()
    {
        return view('admin.terminal.create');
    }

    public function store(StoreTerminalActionRequest $request)
    {
        try {
            TerminalAction::create($request->all());
            Toastr::success('Terminal Registered Successfully!', 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } catch (\Throwable $th) { dd($th);
            Toastr::error('Something wrong, please contact with admin!', 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $terminal= TerminalAction::find($id);
        return view('admin.terminal.edit',compact('terminal'));
    }


    public function update(UpdateTerminalActionRequest $request, TerminalAction $terminalAction)
    {
        try {
            session()->put('terminal_id',$request->terminal_id);
            $input=$request->all();
            DB::beginTransaction();
            TerminalAction::find($request->terminal_id)->update($input);
            Terminal::Where('terminal_id',$request->terminal_id)->update(['reg_no' => $request->reg_no]);

            Toastr::success('Terminal Updated Successfully!', 'Success', ["positionClass" => "toast-top-right"]);
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) { dd($th);
            DB::rollBack();
            Toastr::error('Something wrong, please contact with admin!', 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        /*return response()->json([
            'status' => true,
            'message' => $id
        ], 200);*/
        try {
            $terminalAction= TerminalAction::find($id);
            if (!$terminalAction->user_id){
                $terminalAction->delete();
                Terminal::where('terminal_id',$id)->first()->delete();
            }
            Toastr::success('Terminal Deleted Successfully!', 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } catch (\Throwable $th) { dd($th);
            Toastr::error('Something wrong, please contact with admin!', 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

}
