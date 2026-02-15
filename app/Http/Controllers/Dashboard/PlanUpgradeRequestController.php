<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PlanUpgradeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PlanUpgradeRequestController extends Controller
{
    public function index()
    {
        return view('dashboard.plan_upgrade_requests.index');
    }

    public function getRequests(Request $request)
    {
        $query = PlanUpgradeRequest::with(['user', 'plan']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '—';
            })
            ->addColumn('user_email', function ($row) {
                return $row->user ? $row->user->email : '—';
            })
            ->addColumn('plan_name', function ($row) {
                return $row->plan ? $row->plan->title : '—';
            })
            ->addColumn('created_at_formatted', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '—';
            })
            ->addColumn('status_badge', function ($row) {
                $class = $row->status === PlanUpgradeRequest::STATUS_PENDING ? 'warning'
                    : ($row->status === PlanUpgradeRequest::STATUS_ACCEPTED ? 'success' : 'danger');
                return '<span class="badge bg-' . $class . '">' . __($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $show = route('admin.plan-upgrade-requests.show', $row->id);
                return '<a href="' . $show . '" class="btn btn-sm btn-info"><i class="ti ti-eye"></i></a>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $request = PlanUpgradeRequest::with(['user', 'plan'])->findOrFail($id);
        return view('dashboard.plan_upgrade_requests.show', compact('request'));
    }

    public function accept(Request $request, $id)
    {
        $upgradeRequest = PlanUpgradeRequest::with('user')->findOrFail($id);
        if (!$upgradeRequest->isPending()) {
            return response()->json(['message' => __('Request already processed')], 400);
        }
        $upgradeRequest->update([
            'status' => PlanUpgradeRequest::STATUS_ACCEPTED,
            'admin_notes' => $request->input('admin_notes'),
        ]);
        $upgradeRequest->user->update(['plan_id' => $upgradeRequest->plan_id]);
        return response()->json(['success' => __('Request accepted. User plan updated.')]);
    }

    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response(["responseJSON" => $validator->errors(), "message" => $validator->errors()->first()], 422);
        }
        $upgradeRequest = PlanUpgradeRequest::findOrFail($id);
        if (!$upgradeRequest->isPending()) {
            return response()->json(['message' => __('Request already processed')], 400);
        }
        $upgradeRequest->update([
            'status' => PlanUpgradeRequest::STATUS_REJECTED,
            'admin_notes' => $request->input('admin_notes'),
        ]);
        return response()->json(['success' => __('Request rejected.')]);
    }
}
