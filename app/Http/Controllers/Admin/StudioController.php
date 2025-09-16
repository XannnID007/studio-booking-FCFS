<?php
// app/Http/Controllers/Admin/StudioController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::latest()->paginate(10);
        return view('admin.studios.index', compact('studios'));
    }

    public function create()
    {
        return view('admin.studios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('studios', 'public');
        }

        Studio::create($data);

        return redirect()->route('admin.studios.index')
            ->with('success', 'Studio berhasil ditambahkan!');
    }

    public function show(Studio $studio)
    {
        $studio->load(['bookings' => function ($query) {
            $query->with('user')->latest();
        }]);

        return view('admin.studios.show', compact('studio'));
    }

    public function edit(Studio $studio)
    {
        return view('admin.studios.edit', compact('studio'));
    }

    public function update(Request $request, Studio $studio)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($studio->image) {
                Storage::disk('public')->delete($studio->image);
            }
            $data['image'] = $request->file('image')->store('studios', 'public');
        }

        $studio->update($data);

        return redirect()->route('admin.studios.index')
            ->with('success', 'Studio berhasil diupdate!');
    }

    public function destroy(Studio $studio)
    {
        // Check if studio has active bookings
        $activeBookings = $studio->bookings()
            ->whereIn('status', ['pending', 'paid', 'confirmed'])
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', 'Studio tidak dapat dihapus karena masih memiliki booking aktif!');
        }

        // Delete image
        if ($studio->image) {
            Storage::disk('public')->delete($studio->image);
        }

        $studio->delete();

        return redirect()->route('admin.studios.index')
            ->with('success', 'Studio berhasil dihapus!');
    }
}
