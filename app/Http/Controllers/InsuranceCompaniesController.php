<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\InsuranceCompany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InsuranceCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('insurance_companies')
                ->leftJoin('users', 'users.id', 'insurance_companies._who_added')
                ->whereNull('insurance_companies.deleted_at')
                ->select(['insurance_companies.*', 'users.surname'])
                ->OrderBy('insurance_companies.id', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->surname;
                })
                ->addColumn('status', function ($row) {
                    if ($row->deleted_at != null) {
                        return '<span class="text-danger">Inactive</span>';
                    } else {
                        return '<span class="text-primary">Active</span>';
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
                ->rawColumns(['status', 'editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('insurance_companies.index');
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
            'name' => 'required'
        ])->validate();
        $status = InsuranceCompany::create([
            'name' => $request->name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Insurance company has been added successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $company = InsuranceCompany::where('id', $id)->first();
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'name' => 'required'
        ])->validate();
        $status = InsuranceCompany::where('id', $id)->update([
            'name' => $request->name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Insurance company has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }

    public function filterCompanies(Request $request)
    {
        $data = [];
        $name = $request->q;

        if ($name) {
            $search = $name;
            $data =
                InsuranceCompany::where('name', 'LIKE', "%$search%")->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            }
            return \Response::json($formatted_tags);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $status = InsuranceCompany::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Insurance company has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }


}
