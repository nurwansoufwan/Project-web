<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::paginate(5);
        $lowStockItems = Equipment::where('stock', '<=', 3)->get();
        return view('equipment.index', compact('equipment', 'lowStockItems'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'rental_price_per_day' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama alat camping wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'rental_price_per_day.required' => 'Tarif sewa per hari wajib diisi.',
            'rental_price_per_day.min' => 'Tarif sewa tidak boleh kurang dari 0.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/equipment'), $imageName);
            $data['image_path'] = 'uploads/equipment/' . $imageName;
        }

        Equipment::create($data);

        return redirect()->route('equipment.index')->with('success', 'Alat camping berhasil ditambahkan.');
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'rental_price_per_day' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama alat camping wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'rental_price_per_day.required' => 'Tarif sewa per hari wajib diisi.',
            'rental_price_per_day.min' => 'Tarif sewa tidak boleh kurang dari 0.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($equipment->image_path && File::exists(public_path($equipment->image_path))) {
                File::delete(public_path($equipment->image_path));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/equipment'), $imageName);
            $data['image_path'] = 'uploads/equipment/' . $imageName;
        }

        $equipment->update($data);

        return redirect()->route('equipment.index')->with('success', 'Alat camping berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->image_path && File::exists(public_path($equipment->image_path))) {
            File::delete(public_path($equipment->image_path));
        }

        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Alat camping berhasil dihapus.');
    }
}
