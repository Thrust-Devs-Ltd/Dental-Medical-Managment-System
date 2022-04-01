<?php

namespace App\Http\Controllers;

use App\MedicalCardItem;
use Illuminate\Http\Request;

class MedicalCardItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param \App\MedicalCardItem $medicalCardItem
     * @return \Illuminate\Http\Response
     */
    public function show(MedicalCardItem $medicalCardItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\MedicalCardItem $medicalCardItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicalCardItem $medicalCardItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MedicalCardItem $medicalCardItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicalCardItem $medicalCardItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\MedicalCardItem $medicalCardItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = MedicalCardItem::where('id', $id)->delete();
        if ($status) {
            return Response()->json(["message" => 'Card Has been deleted successfully', "status" => true
            ]);
        }
        return Response()->json(["message" => 'Oops an error has occurred please try again later', "status" => true]);

    }
}
