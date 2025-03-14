<?php

namespace App\Http\Controllers;

use App\Models\tunjTranspost;
use Illuminate\Http\Request;

class TunjTranspostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('transport.index', [
            'tunjangans' => tunjTranspost::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);

        tunjTranspost::create($request->all());

        return redirect()->route('transport.index')
            ->with('success', 'Tunjangan Transportasi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(tunjTranspost $tunjTranspost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tunjangan = tunjTranspost::findOrFail($id);
        return view('transport.edit', compact('tunjangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);

        $tunjangan = tunjTranspost::findOrFail($id);
        $tunjangan->update([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->route('transport.index')->with('success', 'Tunjangan berhasil diperbarui.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tunjTranspost $tunjTranspost)
    {
        //
    }
}
