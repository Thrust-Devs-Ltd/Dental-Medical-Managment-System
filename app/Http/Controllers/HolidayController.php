<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Http\Helper\FunctionsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class HolidayController extends Controller
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

            $data = DB::table('holidays')
                ->leftJoin('users', 'users.id', 'holidays._who_added')
                ->whereNull('holidays.deleted_at')
                ->select(['holidays.*', 'users.surname'])
                ->OrderBy('holidays.id', 'desc')
                ->get();
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
        return view('holidays.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(),
            [
                'name' => 'required',
                'holiday_date' => 'required',
                'repeat_date' => 'required'
            ])->validate();

        $success = Holiday::create(
            [
                'name' => $request->name,
                'holiday_date' => $request->holiday_date,
                'repeat_date' => $request->repeat_date,
                '_who_added' => Auth::User()->id
            ]);
        return FunctionsHelper::messageResponse("Holiday has been added successfully", $success);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Holiday $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        $holiday = Holiday::where('id', $id)->first();
        return response()->json($holiday);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(),
            [
                'name' => 'required',
                'holiday_date' => 'required',
                'repeat_date' => 'required'
            ])->validate();

        $success = Holiday::where('id', $id)->update(
            [
                'name' => $request->name,
                'holiday_date' => $request->holiday_date,
                'repeat_date' => $request->repeat_date,
                '_who_added' => Auth::User()->id
            ]);
        return FunctionsHelper::messageResponse("Holiday has been updated successfully", $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $success = Holiday::where('id', $id)->delete();
        return FunctionsHelper::messageResponse("Holiday has been deleted successfully", $success);
    }
}
