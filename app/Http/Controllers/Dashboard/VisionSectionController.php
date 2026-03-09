<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\VisionSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisionSectionController extends Controller
{
    public function edit()
    {
        $vision = VisionSection::query()->first();

        if (!$vision) {
            abort(404, __('Vision section not found. Please run migrations.'));
        }

        return view('dashboard.vision.edit', compact('vision'));
    }

    public function update(Request $request)
    {
        $vision = VisionSection::query()->firstOrFail();

        $validator = Validator::make($request->all(), [
            'vision_title' => 'required|array',
            'vision_title.en' => 'required|string|max:255',
            'vision_title.ar' => 'required|string|max:255',
            'vision_description' => 'required|array',
            'vision_description.en' => 'required|string',
            'vision_description.ar' => 'required|string',
            'goals_title' => 'nullable|array',
            'goals_title.en' => 'nullable|string|max:255',
            'goals_title.ar' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response([
                'responseJSON' => $validator->errors(),
                'input' => $request->all(),
                'message' => __('Verify that the data is correct, fill in all fields'),
            ], 422);
        }

        $vision->setTranslations('vision_title', $request->input('vision_title'));
        $vision->setTranslations('vision_description', $request->input('vision_description'));

        if ($request->filled('goals_title')) {
            $vision->setTranslations('goals_title', $request->input('goals_title'));
        }

        $vision->is_active = $request->boolean('is_active');
        $vision->save();

        return response()->json(['success' => __('The process has successfully')]);
    }
}
