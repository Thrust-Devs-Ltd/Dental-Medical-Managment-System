<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
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

            $data = Supplier::OrderBy('updated_at', 'DESC')->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->AddedBy->othername;
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('suppliers.index');
    }


    public function filterSuppliers(Request $request)
    {
        $search = $request->get('term');

        $result = Supplier::select('name')->get();
        $data = [];
        foreach ($result as $row) {
            $data[] = $row->name;
        }
        echo json_encode($data);
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
            'name' => 'required'
        ])->validate();
        $status = Supplier::create([
            'name' => $request->name,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Supplier has been added successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'name' => 'required'
        ])->validate();
        $status = Supplier::where('id', $id)->update([
            'name' => $request->name,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Supplier has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Supplier::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Supplier has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }
}
