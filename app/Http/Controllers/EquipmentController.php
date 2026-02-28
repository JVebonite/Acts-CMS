<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $equipment = $query->latest()->paginate(15);
        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|unique:equipment,serial_number',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('equipment/photos', 'public');
        }

        $item = Equipment::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_equipment',
            'model_type' => 'Equipment',
            'model_id' => $item->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('equipment.show', $item)
            ->with('success', 'Equipment added successfully.');
    }

    public function show(Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|unique:equipment,serial_number,' . $equipment->id,
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('equipment/photos', 'public');
        }

        $equipment->update($validated);

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')
            ->with('success', 'Equipment deleted successfully.');
    }
}
