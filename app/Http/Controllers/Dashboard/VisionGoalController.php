<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\VisionGoal;
use App\Models\Dashboard\VisionSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class VisionGoalController extends Controller
{
    public function index()
    {
        return view('dashboard.vision_goals.index');
    }

    public function get_goals(Request $request)
    {
        $vision = VisionSection::query()->first();

        $query = VisionGoal::query()
            ->when($vision, fn ($q) => $q->where('vision_section_id', $vision->id))
            ->orderBy('sort_order');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('title', function ($row) {
                return '<strong class="Titillium-font danger">' . e($row->title) . '</strong>';
            })
            ->addColumn('status', function ($row) {
                $label = $row->is_active ? __('Active') : __('Inactive');
                $class = $row->is_active ? 'text-success' : 'text-danger';
                return '<span class="' . $class . '">' . $label . '</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('admin.vision_goals.edit', $row->id);
                return '<a href="' . $editUrl . '" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem(' . $row->id . ')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';
            })
            ->rawColumns(['title', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $vision = VisionSection::query()->firstOrFail();

        return view('dashboard.vision_goals.add', compact('vision'));
    }

    public function store(Request $request)
    {
        $vision = VisionSection::query()->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'title.ar' => 'required|string|max:255',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'icon' => 'nullable|file|mimes:jpg,jpeg,png,svg,webp',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response([
                'responseJSON' => $validator->errors(),
                'input' => $request->all(),
                'message' => __('Verify that the data is correct, fill in all fields'),
            ], 422);
        }

        $data = [];
        if ($request->hasFile('icon')) {
            $uploadPath = env('PATH_FILE_URL') . '/uploads/vision_goals/';
            File::ensureDirectoryExists($uploadPath);
            $image_url = $request->file('icon');
            $image_name = '/public/uploads/vision_goals/' . time() . '.' . $image_url->getClientOriginalExtension();
            $image_url->move($uploadPath, basename($image_name));
            $data['icon'] = $image_name;
        }

        VisionGoal::query()->create([
            'vision_section_id' => $vision->id,
            'title' => [
                'en' => $request->input('title.en'),
                'ar' => $request->input('title.ar'),
            ],
            'description' => [
                'en' => $request->input('description.en'),
                'ar' => $request->input('description.ar'),
            ],
            'icon' => $data['icon'] ?? null,
            'sort_order' => $request->input('sort_order', 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return response()->json(['success' => __('The process has successfully')]);
    }

    public function edit($id)
    {
        $goal = VisionGoal::query()->findOrFail($id);

        return view('dashboard.vision_goals.edit', compact('goal'));
    }

    public function update(Request $request, $id)
    {
        $goal = VisionGoal::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'title.ar' => 'required|string|max:255',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'icon' => 'nullable|file|mimes:jpg,jpeg,png,svg,webp',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response([
                'responseJSON' => $validator->errors(),
                'input' => $request->all(),
                'message' => __('Verify that the data is correct, fill in all fields'),
            ], 422);
        }

        $data = [];
        if ($request->hasFile('icon')) {
            $uploadPath = env('PATH_FILE_URL') . '/uploads/vision_goals/';
            File::ensureDirectoryExists($uploadPath);
            $image_url = $request->file('icon');
            $image_name = '/public/uploads/vision_goals/' . time() . '.' . $image_url->getClientOriginalExtension();
            $image_url->move($uploadPath, basename($image_name));
            $data['icon'] = $image_name;
        }

        $goal->update([
            'title' => [
                'en' => $request->input('title.en'),
                'ar' => $request->input('title.ar'),
            ],
            'description' => [
                'en' => $request->input('description.en'),
                'ar' => $request->input('description.ar'),
            ],
            'icon' => $data['icon'] ?? $goal->icon,
            'sort_order' => $request->input('sort_order', $goal->sort_order),
            'is_active' => $request->boolean('is_active'),
        ]);

        return response()->json(['success' => __('The process has successfully')]);
    }

    public function delete($id)
    {
        $goal = VisionGoal::find($id);
        $goal?->delete();

        $arr = ['msg' => __('There are some errors, try again'), 'status' => false];
        if ($goal) {
            $arr = ['msg' => __('operation accomplished successfully'), 'status' => true];
        }

        return response()->json($arr);
    }
}
