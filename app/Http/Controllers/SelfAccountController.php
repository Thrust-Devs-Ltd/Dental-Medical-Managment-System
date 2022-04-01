<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\InvoicePayment;
use App\SelfAccount;
use App\SelfAccountDeposit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SelfAccountController extends Controller
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
            if (!empty($_GET['search'])) {
                $data = DB::table('self_accounts')
                    ->leftJoin('users', 'users.id', 'self_accounts._who_added')
                    ->whereNull('self_accounts.deleted_at')
                    ->where('self_accounts.account_holder', 'like', '%' . $request->get('search') . '%')
                    ->select(['self_accounts.*', 'users.surname'])
                    ->OrderBy('self_accounts.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('self_accounts')
                    ->leftJoin('users', 'users.id', 'self_accounts._who_added')
                    ->whereNull('self_accounts.deleted_at')
                    ->select(['self_accounts.*', 'users.surname'])
                    ->OrderBy('self_accounts.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('account_holder', function ($row) {
                    return ' <a href="' . url('self-accounts/' . $row->id) . '"  >' . $row->account_holder . '</a>';
                })
                ->addColumn('account_balance', function ($row) {
                    //get the bill payments on this account
                    $payments = InvoicePayment::where('self_account_id', $row->id)->sum('amount');
                    //get total account deposits
                    $deposits = SelfAccountDeposit::where('self_account_id', $row->id)->sum('amount');
                    $account_balance = $deposits - $payments;
                    return '<span class="text-primary">' . number_format($account_balance) . '</span>';
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
                ->rawColumns(['account_holder', 'status', 'account_balance', 'editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('self_accounts.index');
    }

    public function filterAccounts(Request $request)
    {
        $data = [];
        $name = $request->q;

        if ($name) {
            $search = $name;
            $data =
                SelfAccount::where('account_holder', 'LIKE', "%$search%")->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->account_holder];
            }
            return \Response::json($formatted_tags);
        }
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
            'name' => 'required',
//            'phone_no' => 'required'
        ])->validate();
        $status = SelfAccount::create([
            'account_no' => SelfAccount::AccountNo(),
            'account_holder' => $request->name,
            'holder_phone_no' => $request->phone_no,
            'holder_email' => $request->email,
            'holder_address' => $request->address,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Self Account has been created successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);


    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $data['account_info'] = SelfAccount::where('id', $id)->first();
        return view('self_accounts.preview_account')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\SelfAccount $selfAccount
     * @return Response
     */
    public function edit($id)
    {
        $self_account = SelfAccount::where('id', $id)->first();
        return response()->json($self_account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\SelfAccount $selfAccount
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'name' => 'required',
//            'phone_no' => 'required'
        ])->validate();
        $status = SelfAccount::where('id', $id)->update([
            'account_no' => SelfAccount::AccountNo(),
            'account_holder' => $request->name,
            'holder_phone_no' => $request->phone_no,
            'holder_email' => $request->email,
            'holder_address' => $request->address,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Self Account has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $status = SelfAccount::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Self Account has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }

}
