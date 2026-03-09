<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Property;
use App\Models\Dashboard\Setting;
use App\Models\PlanUpgradeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class InvoicesController extends Controller
{
    /**
     * Display aggregated payment/invoice history from:
     * - Plan upgrade requests (subscription payments)
     * - Property featured listing receipts
     * - Property featured 3D tour receipts
     */
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getPaymentSettings();

        $items = collect();

        // 1. Plan upgrade requests (subscription/plan payments)
        $upgradeRequests = PlanUpgradeRequest::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($upgradeRequests as $req) {
            $items->push((object)[
                'type' => 'subscription',
                'type_label' => __('Subscription'),
                'related_item' => $req->plan ? $req->plan->title : __('Plan'),
                'amount' => $req->plan ? (float) $req->plan->price_monthly : 0,
                'currency' => $settings['currency'],
                'status' => $this->mapUpgradeStatus($req->status),
                'date' => $req->created_at,
                'receipt_path' => $req->transfer_receipt,
                'notes' => $req->admin_notes,
            ]);
        }

        // 2. Featured listing payments (from user's properties with receipt)
        $featuredListingPrice = (float) ($settings['featured_listing_price'] ?? 50);
        $propsWithFeatured = Property::where('user_id', $user->id)
            ->whereNotNull('featured_listing_receipt')
            ->with('price')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($propsWithFeatured as $prop) {
            $status = !empty($prop->featured_listing_until)
                ? ('approved')
                : ('pending');
            $items->push((object)[
                'type' => 'featured_listing',
                'type_label' => __('Featured Listing'),
                'related_item' => $prop->title,
                'amount' => $featuredListingPrice,
                'currency' => $settings['currency'],
                'status' => $status,
                'date' => $prop->updated_at,
                'receipt_path' => $prop->featured_listing_receipt,
                'notes' => $prop->featured_listing_until
                    ? __('Active until') . ' ' . $prop->featured_listing_until
                    : __('Awaiting admin approval'),
            ]);
        }

        // 3. Featured 3D tour payments
        $featured3dPrice = (float) ($settings['featured_3d_tour_price'] ?? 30);
        $propsWith3d = Property::where('user_id', $user->id)
            ->whereNotNull('featured_3d_tour_receipt')
            ->with('price')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($propsWith3d as $prop) {
            $status = !empty($prop->featured_3d_tour_until)
                ? ('approved')
                : ('pending');
            $items->push((object)[
                'type' => 'featured_3d_tour',
                'type_label' => __('3D Tour'),
                'related_item' => $prop->title,
                'amount' => $featured3dPrice,
                'currency' => $settings['currency'],
                'status' => $status,
                'date' => $prop->updated_at,
                'receipt_path' => $prop->featured_3d_tour_receipt,
                'notes' => $prop->featured_3d_tour_until
                    ? __('Active until') . ' ' . $prop->featured_3d_tour_until
                    : __('Awaiting admin approval'),
            ]);
        }

        // Sort all items by date descending
        $items = $items->sortByDesc(fn ($i) => $i->date)->values();

        return view('user_dashboard.invoices.index', compact('items', 'settings'));
    }

    protected function getPaymentSettings(): array
    {
        $keys = ['currency', 'featured_listing_price', 'featured_3d_tour_price'];
        $result = ['currency' => 'JOD', 'featured_listing_price' => '50', 'featured_3d_tour_price' => '30'];
        foreach ($keys as $key) {
            $val = Setting::where('key', $key)->value('value');
            if ($val !== null && $val !== '') {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    protected function mapUpgradeStatus(string $status): string
    {
        return match ($status) {
            PlanUpgradeRequest::STATUS_ACCEPTED => 'approved',
            PlanUpgradeRequest::STATUS_REJECTED => 'rejected',
            default => 'pending',
        };
    }
}
