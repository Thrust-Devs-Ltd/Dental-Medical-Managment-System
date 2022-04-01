<?php

namespace App\Http\Controllers;

use App\InvoiceItem;
use App\MedicalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InvoiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $invoice_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $invoice_id)
    {
        if ($request->ajax()) {

            $data = InvoiceItem::where('invoice_id', $invoice_id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('service', function ($row) {
                    return $row->medical_service->name;
                })
                ->addColumn('qty', function ($row) {
                    return number_format($row->qty);
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('total_amount', function ($row) {
                    return number_format($row->price * $row->qty);
                })
                ->addColumn('procedure_doctor', function ($row) {
                    return $row->procedure_doctor->surname;
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editItem(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {
                    $btn = '<a href="#" onclick="deleteItem(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status', 'editBtn', 'deleteBtn'])
                ->make(true);
        }
    }

    //this applies on the doctor's invoicing dashboard
    public function AppointmentInvoiceItems(Request $request, $appointment_id)
    {
        if ($request->ajax()) {

            $data = DB::table('invoice_items')
                ->leftJoin('medical_services', 'medical_services.id', 'invoice_items.medical_service_id')
                ->leftJoin('invoices', 'invoices.id', 'invoice_items.invoice_id')
                ->whereNull('invoice_items.deleted_at')
                ->where('invoices.appointment_id', $appointment_id)
                ->select('invoice_items.*', 'medical_services.name as service_name')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('service', function ($row) {
                    return $row->service_name;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editItem(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {
                    $btn = '<a href="#" onclick="deleteItem(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status', 'editBtn', 'deleteBtn'])
                ->make(true);
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
        //
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
        $item = DB::table('invoice_items')
            ->join('medical_services', 'medical_services.id', 'invoice_items.medical_service_id')
            ->join('users', 'users.id', 'invoice_items.doctor_id')
            ->where('invoice_items.id', $id)
            ->select('invoice_items.*', 'medical_services.name', 'users.surname', 'users.othername')
            ->first();
        return response()->json($item);
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
        Validator::make($request->all(), [
            'price' => 'required',
            'qty' => 'required',
            'doctor_id' => 'required',
            'medical_service_id' => 'required'
        ])->validate();
        $status = InvoiceItem::where('id', $id)->update(
            [
                'qty' => $request->qty,
                'price' => $request->price,
                'medical_service_id' => $request->medical_service_id,
                'doctor_id' => $request->doctor_id,
                'tooth_no' => $request->tooth_no,
                '_who_added' => Auth::User()->id
            ]
        );
        if ($status) {
            return response()->json(['message' => 'Invoice Item has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = InvoiceItem::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Invoice Item has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }
}
