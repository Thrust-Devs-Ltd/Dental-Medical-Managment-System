<?php

namespace App\Http\Controllers;

use App\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class QuotationItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $quotation_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $quotation_id)
    {
        if ($request->ajax()) {

            $data = DB::table('quotation_items')
                ->join('medical_services', 'medical_services.id', 'quotation_items.medical_service_id')
                ->join('users', 'users.id', 'quotation_items._who_added')
                ->whereNull('quotation_items.deleted_at')
                ->where('quotation_items.quotation_id', $quotation_id)
                ->select('quotation_items.*', 'medical_services.name', 'users.othername')
                ->OrderBy('quotation_items.id', 'desc')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('service', function ($row) {
                    return $row->name;
                })
                ->addColumn('qty', function ($row) {
                    return number_format($row->qty);
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('total_amount', function ($row) {
                    return number_format($row->qty * $row->price);
                })
                ->addColumn('added_by', function ($row) {
                    return $row->othername;
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
        Validator::make($request->all(), [
            'price' => 'required',
            'qty' => 'required',
            'medical_service_id' => 'required'
        ])->validate();
        $status = QuotationItem::create(
            [
                'qty' => $request->qty,
                'price' => $request->price,
                'medical_service_id' => $request->medical_service_id,
                'tooth_no' => $request->tooth_no,
                'quotation_id' => $request->quotation_id,
                '_who_added' => Auth::User()->id
            ]
        );
        if ($status) {
            return response()->json(['message' => 'Quotation Item has been added successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\quotationItem $quotationItem
     * @return \Illuminate\Http\Response
     */
    public function show(quotationItem $quotationItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $quotationItem_id
     * @return \Illuminate\Http\Response
     */
    public function edit($quotationItem_id)
    {
        $item = DB::table('quotation_items')
            ->join('medical_services', 'medical_services.id', 'quotation_items.medical_service_id')
            ->where('quotation_items.id', $quotationItem_id)
            ->select('quotation_items.*', 'medical_services.name')
            ->first();
        return response()->json($item);
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
            'qty' => 'required',
            'price' => 'required',
            'medical_service_id' => 'required'
        ])->validate();
        $status = QuotationItem::where('id', $id)->update(
            [
                'qty' => $request->qty,
                'price' => $request->price,
                'medical_service_id' => $request->medical_service_id,
                'tooth_no' => $request->tooth_no,
                '_who_added' => Auth::User()->id
            ]
        );
        if ($status) {
            return response()->json(['message' => 'Quotation Item has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $quotationItem_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($quotationItem_id)
    {
        $status = QuotationItem::where('id', $quotationItem_id)->delete();
        if ($status) {
            return response()->json(['message' => 'Quotation Item Item has been deleted successfully', 'status' =>
                true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }
}
