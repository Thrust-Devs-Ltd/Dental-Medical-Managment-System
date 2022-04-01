<?php

namespace App\Http\Controllers;

use App\BirthDayMessage;
use App\Http\Helper\FunctionsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BirthDayMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($_GET['search'])) {
                $data = DB::table('birth_day_messages')
                    ->leftJoin('users', 'users.id', 'birth_day_messages._who_added')
                    ->whereNull('birth_day_messages.deleted_at')
                    ->where('birth_day_messages.message', 'like', '%' . $request->get('search') . '%')
                    ->select(['birth_day_messages.*', 'users.surname'])
                    ->OrderBy('birth_day_messages.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('birth_day_messages')
                    ->leftJoin('users', 'users.id', 'birth_day_messages._who_added')
                    ->whereNull('birth_day_messages.deleted_at')
                    ->select(['birth_day_messages.*', 'users.surname'])
                    ->OrderBy('birth_day_messages.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->surname;
                })
                ->addColumn('editBtn', function ($row) {
                    if ($row->deleted_at == null) {
                        return '<a href="#" onclick="editRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    }
                })
                ->addColumn('deleteBtn', function ($row) {
                    return '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';

                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('birthday_wishes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'message' => 'required'
        ])->validate();
        $success = BirthDayMessage::create([
            'message' => $request->message,
            '_who_added' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("Message has been added successfully", $success);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\BirthDayMessage $birthDayMessage
     * @return Response
     */
    public function show(BirthDayMessage $birthDayMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $wish = BirthDayMessage::where('id', $id)->first();
        return response()->json($wish);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'message' => 'required'
        ])->validate();
        $success = BirthDayMessage::where('id', $id)->update([
            'message' => $request->message,
            '_who_added' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("Message has been updated successfully", $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        $success = BirthDayMessage::where('id', $id)->delete();
        return FunctionsHelper::messageResponse("Message has been deleted successfully", $success);
    }
}
