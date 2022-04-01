<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\Notifications\AppointmentReminderNotification;
use App\Notifications\ReminderNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Thomasjohnkane\Snooze\ScheduledNotification;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (!empty($_GET['search'])) {

                $data = DB::table('users')
                    ->leftJoin('roles', 'roles.id', 'users.role_id')
                    ->leftJoin('branches', 'branches.id', 'users.branch_id')
                    ->whereNull('users.deleted_at')
                    ->where('users.surname', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('users.othername', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('users.email', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('users.phone_no', 'like', '%' . $request->get('search') . '%')
                    ->select(['users.*', 'roles.name as user_role', 'branches.name as branch'])
                    ->OrderBy('users.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('users')
                    ->leftJoin('roles', 'roles.id', 'users.role_id')
                    ->leftJoin('branches', 'branches.id', 'users.branch_id')
                    ->whereNull('users.deleted_at')
                    ->select(['users.*', 'roles.name as user_role', 'branches.name as branch'])
                    ->OrderBy('users.id', 'desc')
                    ->get();
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('is_doctor', function ($row) {
                    if ($row->is_doctor == "Yes") {
                        return '<span class="label label-sm label-success">' . $row->is_doctor . '</span>';
                    } else {
                        return '<span class="label label-sm label-default">' . $row->is_doctor . '</span>';
                    }
                })
                ->addColumn('editBtn', function ($row) {
                    if ($row->deleted_at == null) {
                        return '<a href="#" onclick="editRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    }
                })
                ->addColumn('deleteBtn', function ($row) {

                    return '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';

                })
                ->rawColumns(['is_doctor', 'editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('users.index');
    }


    public function filterDoctor(Request $request)
    {
        $data = [];
        $name = $request->q;

        if ($name) {
            $search = $name;
            $data = DB::table("users")
                ->whereNull('users.deleted_at')
                ->where('users.is_doctor', '=', 'Yes')
                ->select('users.*')
                ->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->surname . " " . $tag->othername];
            }
            return \Response::json($formatted_tags);
        }
    }

    public function filterEmployees(Request $request)
    {
        $data = [];
        $name = $request->q;
        if ($name) {
            $search = $name;
            $data = DB::table("users")
                ->whereNull('users.deleted_at')
                ->where('surname', 'LIKE', "%$search%")
                ->Orwhere('othername', 'LIKE', "%$search%")
                ->select('users.*')
                ->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->surname . " " . $tag->othername];
            }
            return \Response::json($formatted_tags);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'surname' => ['required'],
            'othername' => ['required'],
            'email' => 'required',
            'password' => 'min:6 | required_with:password_confirmation',
            'password_confirmation' => 'min:6 | same:password_confirmation'
        ])->validate();
        $status = User::create([
            'surname' => $request->surname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'alternative_no' => $request->alternative_no,
            'nin' => $request->nin,
            'role_id' => $request->role_id,
            'branch_id' => $request->branch_id,
            'is_doctor' => $request->is_doctor,
            'password' => Hash::make($request->password),
        ]);
        return FunctionsHelper::messageResponse("User has been registered successfully", $status);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = DB::table("users")
            ->leftJoin('branches', 'branches.id', 'users.branch_id')
            ->leftJoin('roles', 'roles.id', 'users.role_id')
            ->where('users.id', $id)
            ->whereNull('users.deleted_at')
            ->select('users.*', 'roles.name as user_role', 'branches.name as branch')
            ->first();
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status = User::where('id', $id)->update([
            'surname' => $request->surname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'alternative_no' => $request->alternative_no,
            'role_id' => $request->role_id,
            'is_doctor' => $request->is_doctor,
            'branch_id' => $request->branch_id,
            'nin' => $request->nin
        ]);
        return FunctionsHelper::messageResponse("User has been updated successfully", $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = User::where('id', $id)->delete();
        return FunctionsHelper::messageResponse("User has been deleted successfully", $status);

    }


}
