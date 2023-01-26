<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTerminalActionRequest;
use App\Http\Requests\UpdateTerminalActionRequest;
use App\Models\Terminal;
use App\Models\TerminalAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Toastr;
use Yajra\DataTables\DataTables;
use Session;

class TerminalActionController extends Controller
{

    public function index()
    {
        $terminals = DB::table('terminal_actions')->select('terminal_actions.*','users.name as userName')
                        ->leftJoin('users','users.id','=','terminal_actions.user_id')
                        ->get();;
        return view('admin.terminal.list',compact('terminals'));
    }

    public function datatable(Request $request)
    {
        $Query = TerminalAction::select('terminal_actions.*','users.name as userName')
                ->leftJoin('users','users.id','=','terminal_actions.user_id')
                ->orderBy('terminal_actions.id', 'desc')
                ->get();
        return DataTables::of($Query)
            ->addIndexColumn()
            ->addColumn('status', function(TerminalAction $terminalAction) {
                if ($terminalAction->status) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('is_approved', function(TerminalAction $terminalAction) {
                if ($terminalAction->is_approved) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('actions', function(TerminalAction $terminalAction) {
                /*if($terminalAction['user_id']) {
                    return '<a href=" ' . route('terminals.edit', $terminalAction->id) . ' " class="btn btn-default btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></a>';
                } else {
                    return "<a href=\" " . route('terminals.edit', $terminalAction->id) . " \" class=\"btn btn-default btn-xs m-r-5\"  data-toggle=\"tooltip\" data-original-title=\"Edit\"><i class=\"fa fa-pencil font-14\"></i></a>
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
