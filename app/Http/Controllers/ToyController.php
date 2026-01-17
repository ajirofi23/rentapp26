<?php

namespace App\Http\Controllers;

use App\Models\Toy;
use Illuminate\Http\Request;

class ToyController extends Controller
{
    public function update(Request $request, Toy $toy)
    {
        $request->validate([
            'price' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $toy->update($request->only('price', 'is_active'));

        return back()->with('success', 'Harga mainan ' . $toy->code . ' berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:toys,code',
            'category' => 'required|in:B,K,M',
            'price' => 'required|integer|min:0',
        ]);

        Toy::create($request->all());

        return back()->with('success', 'Mainan baru berhasil ditambahkan.');
    }
}
