<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::orderBy('sort_order')->get();
        $settings = $this->getSliderSettings();
        return view('admin.hero-sliders.index', compact('sliders', 'settings'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'display_duration' => 'nullable|integer|min:3|max:30',
            'is_active' => 'boolean'
        ]);
        
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('hero-sliders', 'public');
        }
        
        $validated['display_duration'] = $validated['display_duration'] ?? 5;
        $validated['sort_order'] = $validated['sort_order'] ?? HeroSlider::max('sort_order') + 1;
        $validated['is_active'] = $request->has('is_active');
        
        HeroSlider::create($validated);
        
        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slide berhasil ditambahkan');
    }
        
    public function update(Request $request, HeroSlider $heroSlider)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'display_duration' => 'nullable|integer|min:3|max:30',
            'is_active' => 'boolean'
        ]);
        
        if ($request->hasFile('image')) {
            if ($heroSlider->image_path && Storage::disk('public')->exists($heroSlider->image_path)) {
                Storage::disk('public')->delete($heroSlider->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('hero-sliders', 'public');
        }
        
        $validated['display_duration'] = $validated['display_duration'] ?? 5;
        $validated['is_active'] = $request->has('is_active');
        
        $heroSlider->update($validated);
        
        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slide berhasil diperbarui');
    }
    
    public function destroy(HeroSlider $heroSlider)
    {
        if ($heroSlider->image_path && Storage::disk('public')->exists($heroSlider->image_path)) {
            Storage::disk('public')->delete($heroSlider->image_path);
        }
        $heroSlider->delete();
        
        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slide berhasil dihapus');
    }
    
    public function updateOrder(Request $request)
    {
        $order = $request->validate(['order' => 'required|array'])['order'];
        
        foreach ($order as $index => $id) {
            HeroSlider::where('id', $id)->update(['sort_order' => $index]);
        }
        
        return response()->json(['success' => true]);
    }
    
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'auto_play' => 'boolean',
            'transition_duration' => 'nullable|integer|min:300|max:2000',
            'show_arrows' => 'boolean',
            'show_dots' => 'boolean'
        ]);
        
        $settings = $this->getSliderSettings();
        foreach ($validated as $key => $value) {
            $settings[$key] = $value;
        }
        
        file_put_contents(
            storage_path('app/hero_slider_settings.json'),
            json_encode($settings, JSON_PRETTY_PRINT)
        );
        
        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Pengaturan slider berhasil diperbarui');
    }
    
    private function getSliderSettings()
    {
        $path = storage_path('app/hero_slider_settings.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?? [
                'auto_play' => true,
                'transition_duration' => 500,
                'show_arrows' => true,
                'show_dots' => true
            ];
        }
        return [
            'auto_play' => true,
            'transition_duration' => 500,
            'show_arrows' => true,
            'show_dots' => true
        ];
    }
}