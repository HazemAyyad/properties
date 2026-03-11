<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Property;
use App\Models\Dashboard\Setting;
use App\Models\PlanUpgradeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class InvoicesController extends Controller
{
    /**
     * Display aggregated payment/invoice history (plan upgrade, featured listing, 3D tour).
     */
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getPaymentSettings();
        $items = self::getBillingItemsForUser($user, $settings);
        return view('user_dashboard.invoices.index', compact('items', 'settings'));
    }

    /**
     * Build unified billing items for a user (plan upgrade + featured listing + 3D tour).
     * Used by profile billing section and invoices index.
     *
     * @param  User  $user
     * @param  array|null  $settings  Optional; if null, fetched via getPaymentSettings()
     * @return \Illuminate\Support\Collection
     */
    public static function getBillingItemsForUser(User $user, ?array $settings = null): Collection
    {
        $settings = $settings ?? (new self)->getPaymentSettings();
        $items = collect();

        // 1. Plan upgrade requests
        $upgradeRequests = PlanUpgradeRequest::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($upgradeRequests as $req) {
            $items->push((object)[
                'type' => 'subscription',
                'type_label' => __('Plan Upgrade'),
                'related_item' => $req->plan ? $req->plan->title : __('Plan'),
                'amount' => $req->plan ? (float) $req->plan->price_monthly : 0,
                'currency' => $settings['currency'],
                'status' => (new self)->mapUpgradeStatus($req->status),
                'date' => $req->created_at,
                'processed_at' => $req->processed_at ?? null,
                'receipt_path' => $req->transfer_receipt,
                'receipt_url' => $req->transfer_receipt_url ?? '',
                'notes' => $req->admin_notes,
            ]);
        }

        // 2. Featured listing (properties with receipt)
        $featuredListingPrice = (float) ($settings['featured_listing_price'] ?? 50);
        $propsWithFeatured = Property::where('user_id', $user->id)
            ->whereNotNull('featured_listing_receipt')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($propsWithFeatured as $prop) {
            $status = !empty($prop->featured_listing_until) ? 'approved' : 'pending';
            $items->push((object)[
                'type' => 'featured_listing',
                'type_label' => __('Featured Listing'),
                'related_item' => $prop->title,
                'amount' => $featuredListingPrice,
                'currency' => $settings['currency'],
                'status' => $status,
                'date' => $prop->updated_at,
                'processed_at' => null,
                'receipt_path' => $prop->featured_listing_receipt,
                'receipt_url' => self::normalizeReceiptUrl($prop->featured_listing_receipt),
                'notes' => $prop->featured_listing_until
                    ? __('Active until') . ' ' . $prop->featured_listing_until
                    : __('Awaiting admin approval'),
            ]);
        }

        // 3. Featured 3D tour (properties with receipt)
        $featured3dPrice = (float) ($settings['featured_3d_tour_price'] ?? 30);
        $propsWith3d = Property::where('user_id', $user->id)
            ->whereNotNull('featured_3d_tour_receipt')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($propsWith3d as $prop) {
            $status = !empty($prop->featured_3d_tour_until) ? 'approved' : 'pending';
            $items->push((object)[
                'type' => 'featured_3d_tour',
                'type_label' => __('3D Tour'),
                'related_item' => $prop->title,
                'amount' => $featured3dPrice,
                'currency' => $settings['currency'],
                'status' => $status,
                'date' => $prop->updated_at,
                'processed_at' => null,
                'receipt_path' => $prop->featured_3d_tour_receipt,
                'receipt_url' => self::normalizeReceiptUrl($prop->featured_3d_tour_receipt),
                'notes' => $prop->featured_3d_tour_until
                    ? __('Active until') . ' ' . $prop->featured_3d_tour_until
                    : __('Awaiting admin approval'),
            ]);
        }

        return $items->sortByDesc(fn ($i) => $i->date)->values();
    }

    /**
     * Normalize stored receipt path to full URL (handles /public/ prefix).
     */
    public static function normalizeReceiptUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }
        $path = str_replace('/public/', '', $path);
        $path = ltrim($path, '/');
        return $path ? asset($path) : '';
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
