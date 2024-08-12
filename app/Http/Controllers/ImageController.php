<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    //
    public function update(Request $request, Image $image)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($image->name);

            $path = $request->file('image')->store('images', 'public'); 
            $image->update(['name' => $path]);
        }

        $image->update([
            'product_id' => $validated['product_id']
        ]);

        return response()->json([
            'message' => 'Image updated successfully.',
            'image' => $image
        ]);
    }

    public function destroy(Image $image)
    {
        Storage::disk('public')->delete($image->name);
        $image->delete();

        return response()->json([
            'message' => 'Image deleted successfully.'
        ]);
    }
}
