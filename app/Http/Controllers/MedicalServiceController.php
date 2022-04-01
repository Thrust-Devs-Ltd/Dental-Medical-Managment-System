<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\MedicalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MedicalServiceController extends Controller
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
                $data = DB::table('medical_services')
                    ->leftJoin('users', 'users.id', 'medical_services._who_added')
                    ->whereNull('medical_services.deleted_at')
                    ->where('medical_services.name', 'like', '%' . $request->get('search') . '%')
                    ->select(['medical_services.*', 'users.surname'])
                    ->OrderBy('medical_services.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('medical_services')
                    ->leftJoin('users', 'users.id', 'medical_services._who_added')
                    ->whereNull('medical_services.deleted_at')
                    ->select(['medical_services.*', 'users.surname'])
                    ->OrderBy('medical_services.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
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
        return view('clinical_services.index');
    }

    public function ServicesArray(Request $request)
    {

        $result = MedicalService::select('name')->get();
        $data = [];
        foreach ($result as $row) {
            $data[] = $row->name;
        }
        echo json_encode($data);
    }


    public function filterServices(Request $request)
    {
        $data = [];
        $name = $request->q;

        if ($name) {
            $search = $name;
            $data = MedicalService::where('name', 'LIKE', "%$search%")->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name, 'price' => $tag->price];
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
        Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required'
        ])->validate();
        $status = MedicalService::create([
            'name' => $request->name,
            'price' => $request->price,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Service has been added successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\MedicalService $medicalService
     * @return \Illuminate\Http\Response
     */
    public function show(MedicalService $medicalService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\MedicalService $medicalService
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = MedicalService::where('id', $id)->first();
        return response()->json($service);
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
        Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required'
        ])->validate();
        $status = MedicalService::where('id', $id)->update([
            'name' => $request->name,
            'price' => $request->price,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Service has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\MedicalService $medicalService
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $status = MedicalService::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Service has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }


}
