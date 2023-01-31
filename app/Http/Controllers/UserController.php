<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Terminal;
use App\Models\TerminalAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Toastr;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $StartDate =  $request->has('StartDate') ? $request->StartDate : null;
        $EndDate = $request->has('EndDate') ? $request->EndDate : null;

        $userList= User::latest()->get();

        //dd($request->all(),$StartDate,$EndDate,$terminals);
        return view('admin.user.list',compact('userList','StartDate','EndDate'));
    }

    public function datatable(Request $request)
    {
        $Query = User::when(request()->StartDate, function ($query, $StartDate) {
                $query->where('created_at', '>=', $StartDate . ' 00:00:00');})
                ->when(request()->EndDate, function ($query, $EndDate) {
                $query->where('created_at', '<=', $EndDate .  ' 23:59:59');})
                ->when(request()->userId, function ($query, $userId) {
                $query->where('id', $userId);})
                ->latest()
                ->get();
        //dd(DataTables::of($Query));
        return DataTables::of($Query)
            ->addIndexColumn()
            ->addColumn('actions', function(User $user) {
                if($user['id']!=1) {
                    return '<a href=" ' . route('users.edit', $user->id) . ' " class="btn btn-default btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></a>';}})
                /*else {
                    return "<a href=\" " . route('terminals.edit', $terminalAction->id) . " \" class=\"btn btn-default btn-xs m-r-5\"  data-toggle=\"tooltip\" data-original-title=\"Edit\"><i class=\"fa fa-pencil font-14\"></i></a>

                        <button data-id=\" $terminalAction->id \" class=\"btn btn-default btn-xs m-r-5 deleteUser \" data-original-title=\"Delete\"><i class=\"fa fa-trash-o font-14\"></i></button>
                        ";
                }*/
            ->rawColumns(['actions'])
            ->make();
    }
    public function create()
    {
        $terminalList= TerminalAction::where([['is_approved',0],['user_id',null]])->get();
        return view('admin.user.create',compact('terminalList'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user=User::create([
                'name'=>$request->name,
                'phone'=>$request->phone,
                'password'=>Hash::make($request->password),
                'role_id'=>3
            ]);
            if ($request->has('terminalId')){
                $terminal_info=TerminalAction::where([['id',$request->terminalId],['user_id',null]])->first();
                $terminal_info->update([
                    'allocate_place'=>$request->allocate_place,
                    'status'=>1,
                    'status_updated_at'=>now(),
                    'is_approved'=>1,
                    'approved_at'=>now(),
                    'user_id'=>$user->id
                ]);
                Terminal::create([
                    'terminal_id' => $terminal_info->id,
                    'reg_no' => $terminal_info->reg_no,
                    'user_id' => $user->id,
                    'shutter_sensor_status' => 0,
                    'shutter_sensor_updated_at' => now(),
                    'smoke_sensor_status' => 0,
                    'smoke_sensor_updated_at' => now(),
                    'gas_sensor_status' => 0,
                    'gas_sensor_updated_at' => now(),
                    'motion_sensor_status' => 0,
                    'motion_sensor_updated_at' => now(),
                    'is_terminal_on' => 1,
                    'terminal_updated_at' => now(),
                ]);
            }
            Toastr::success('User Registered Successfully!', 'Success', ["positionClass" => "toast-top-right"]);
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) { DB::rollBack();dd($th);
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
        $user= User::find($id);
        /*$userTerminals=TerminalAction::where('user_id',$id)->pluck('id','allocate_place')->toArray();
        $terminals= TerminalAction::all();*/
        return view('admin.user.edit',compact('user'));
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $input = [];
            foreach ($request->all() as $k=>$v){
                if ($v!=null) $input[$k]=$v;
            }
            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }
            User::find($request->user_id)->update($input);
            Toastr::success('User Updated Successfully!', 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } catch (\Throwable $th) { //dd($th);
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
