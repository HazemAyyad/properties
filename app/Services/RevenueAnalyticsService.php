<?php

namespace App\Services;

use App\Models\Dashboard\Property;
use App\Models\Dashboard\Setting;
use App\Models\PlanUpgradeRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Admin dashboard revenue analytics.
 * Only counts accepted/approved payments. Pending and rejected are excluded.
 *
 * Revenue sources:
 * - Subscription: accepted plan_upgrade_requests, amount from plan.price_monthly
 * - Featured Listing: properties with featured_listing_until set, amount from settings
 * - 3D Tour: properties with featured_3d_tour_until set, amount from settings
 */
class RevenueAnalyticsService
{
    protected string $currency = 'JOD';
    protected float $featuredListingPrice = 50;
    protected float $featured3dPrice = 30;

    public function __construct()
    {
        $this->loadSettings();
    }

    protected function loadSettings(): void
    {
        $currency = Setting::where('key', 'currency')->value('value');
        if ($currency !== null && $currency !== '') {
            $this->currency = $currency;
        }
        $fl = Setting::where('key', 'featured_listing_price')->value('value');
        if ($fl !== null && $fl !== '') {
            $this->featuredListingPrice = (float) $fl;
        }
        $f3 = Setting::where('key', 'featured_3d_tour_price')->value('value');
        if ($f3 !== null && $f3 !== '') {
            $this->featured3dPrice = (float) $f3;
        }
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * All accepted revenue items for aggregation (subscription + featured + 3D).
     * Each item: date (Carbon), amount (float), type (string), source (string), user_id, related (string).
     *
     * @return Collection<int, object{date: Carbon, amount: float, type: string, source: string, user_id: int|null, related: string, raw_id: int}>
     */
    public function getAcceptedRevenueItems(): Collection
    {
        $items = collect();

        // 1. Subscription: accepted plan upgrade requests. Amount = plan.price_monthly; date = updated_at.
        $accepted = PlanUpgradeRequest::where('status', PlanUpgradeRequest::STATUS_ACCEPTED)
            ->with('plan')
            ->get();
        foreach ($accepted as $req) {
            $amount = $req->plan ? (float) $req->plan->price_monthly : 0;
            $items->push((object)[
                'date' => Carbon::parse($req->updated_at),
                'amount' => $amount,
                'type' => 'subscription',
                'source' => 'subscription',
                'user_id' => $req->user_id,
                'related' => $req->plan ? $req->plan->title : '—',
                'raw_id' => $req->id,
            ]);
        }

        // 2. Featured Listing: properties with receipt and until set (approved). Amount from settings; date = updated_at.
        $featuredApproved = Property::whereNotNull('featured_listing_receipt')
            ->whereNotNull('featured_listing_until')
            ->get();
        foreach ($featuredApproved as $p) {
            $items->push((object)[
                'date' => Carbon::parse($p->updated_at),
                'amount' => $this->featuredListingPrice,
                'type' => 'featured_listing',
                'source' => 'featured_listing',
                'user_id' => $p->user_id,
                'related' => $p->title ?? '—',
                'raw_id' => $p->id,
            ]);
        }

        // 3. 3D Tour: properties with receipt and until set (approved).
        $tourApproved = Property::whereNotNull('featured_3d_tour_receipt')
            ->whereNotNull('featured_3d_tour_until')
            ->get();
        foreach ($tourApproved as $p) {
            $items->push((object)[
                'date' => Carbon::parse($p->updated_at),
                'amount' => $this->featured3dPrice,
                'type' => 'featured_3d_tour',
                'source' => 'featured_3d_tour',
                'user_id' => $p->user_id,
                'related' => $p->title ?? '—',
                'raw_id' => $p->id,
            ]);
        }

        return $items->sortByDesc('date')->values();
    }

    /**
     * Total revenue (all time).
     */
    public function getTotalRevenue(): float
    {
        return $this->getAcceptedRevenueItems()->sum('amount');
    }

    /**
     * Revenue today (by updated_at date).
     */
    public function getRevenueToday(): float
    {
        $today = now()->toDateString();
        return $this->getAcceptedRevenueItems()->filter(fn ($i) => $i->date->toDateString() === $today)->sum('amount');
    }

    /**
     * Revenue this week (start of week to now).
     */
    public function getRevenueThisWeek(): float
    {
        $start = now()->startOfWeek();
        return $this->getAcceptedRevenueItems()->filter(fn ($i) => $i->date->gte($start))->sum('amount');
    }

    /**
     * Revenue this month.
     */
    public function getRevenueThisMonth(): float
    {
        $start = now()->startOfMonth();
        return $this->getAcceptedRevenueItems()->filter(fn ($i) => $i->date->gte($start))->sum('amount');
    }

    /**
     * Revenue this year.
     */
    public function getRevenueThisYear(): float
    {
        $start = now()->startOfYear();
        return $this->getAcceptedRevenueItems()->filter(fn ($i) => $i->date->gte($start))->sum('amount');
    }

    /**
     * Revenue by source: subscription, featured_listing, featured_3d_tour totals.
     *
     * @return array{subscription: float, featured_listing: float, featured_3d_tour: float}
     */
    public function getRevenueBySource(): array
    {
        $items = $this->getAcceptedRevenueItems();
        return [
            'subscription' => $items->where('source', 'subscription')->sum('amount'),
            'featured_listing' => $items->where('source', 'featured_listing')->sum('amount'),
            'featured_3d_tour' => $items->where('source', 'featured_3d_tour')->sum('amount'),
        ];
    }

    /**
     * Last 30 days: daily totals for chart. Keys = date string (Y-m-d), value = amount.
     *
     * @return array<string, float>
     */
    public function getRevenueLast30Days(): array
    {
        $items = $this->getAcceptedRevenueItems();
        $start = now()->subDays(29)->startOfDay();
        $filtered = $items->filter(fn ($i) => $i->date->gte($start));
        $byDay = $filtered->groupBy(fn ($i) => $i->date->toDateString());
        $result = [];
        for ($d = 0; $d < 30; $d++) {
            $date = now()->subDays(29 - $d)->toDateString();
            $result[$date] = $byDay->get($date, collect())->sum('amount');
        }
        return $result;
    }

    /**
     * This year: monthly totals. Keys = Y-m, value = amount.
     *
     * @return array<string, float>
     */
    public function getRevenueByMonthThisYear(): array
    {
        $items = $this->getAcceptedRevenueItems();
        $start = now()->startOfYear();
        $filtered = $items->filter(fn ($i) => $i->date->gte($start));
        $byMonth = $filtered->groupBy(fn ($i) => $i->date->format('Y-m'));
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = now()->year . '-' . str_pad((string) $m, 2, '0', STR_PAD_LEFT);
            $result[$key] = $byMonth->get($key, collect())->sum('amount');
        }
        return $result;
    }

    /**
     * Recent accepted payments for table (newest first). Each row: date, user_name, type, related, amount, status, source.
     */
    public function getRecentAcceptedPayments(int $limit = 15): Collection
    {
        $items = $this->getAcceptedRevenueItems()->take($limit * 2); // we might have many, need to load users
        $userIds = $items->pluck('user_id')->unique()->filter()->values()->all();
        $users = \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id');
        return $items->take($limit)->map(function ($i) use ($users) {
            return (object)[
                'date' => $i->date,
                'user_name' => $users->get($i->user_id)?->name ?? '—',
                'type' => $i->type,
                'related' => $i->related,
                'amount' => $i->amount,
                'status' => 'accepted',
                'source' => $i->source,
                'currency' => $this->currency,
            ];
        });
    }

    /**
     * Pending counts (not revenue): pending upgrade requests, pending featured listing, pending 3D tour.
     *
     * @return array{pending_plan_upgrades: int, pending_featured_listing: int, pending_featured_3d: int}
     */
    public function getPendingCounts(): array
    {
        return [
            'pending_plan_upgrades' => PlanUpgradeRequest::where('status', PlanUpgradeRequest::STATUS_PENDING)->count(),
            'pending_featured_listing' => Property::whereNotNull('featured_listing_receipt')->whereNull('featured_listing_until')->count(),
            'pending_featured_3d' => Property::whereNotNull('featured_3d_tour_receipt')->whereNull('featured_3d_tour_until')->count(),
        ];
    }

    /**
     * Approved/accepted counts per source (for optional display).
     *
     * @return array{accepted_plan_upgrades: int, approved_featured_listing: int, approved_featured_3d: int}
     */
    public function getApprovedCounts(): array
    {
        return [
            'accepted_plan_upgrades' => PlanUpgradeRequest::where('status', PlanUpgradeRequest::STATUS_ACCEPTED)->count(),
            'approved_featured_listing' => Property::whereNotNull('featured_listing_receipt')->whereNotNull('featured_listing_until')->count(),
            'approved_featured_3d' => Property::whereNotNull('featured_3d_tour_receipt')->whereNotNull('featured_3d_tour_until')->count(),
        ];
    }

    /**
     * Single call that builds revenue items once and returns all dashboard metrics (avoids N+1 and repeated queries).
     *
     * @return array{
     *   currency: string,
     *   total_revenue: float,
     *   revenue_today: float,
     *   revenue_this_week: float,
     *   revenue_this_month: float,
     *   revenue_this_year: float,
     *   by_source: array{subscription: float, featured_listing: float, featured_3d_tour: float},
     *   by_source_percent: array{subscription: float, featured_listing: float, featured_3d_tour: float},
     *   last_30_days: array<string, float>,
     *   by_month_this_year: array<string, float>,
     *   recent_accepted: \Illuminate\Support\Collection,
     *   pending: array{pending_plan_upgrades: int, pending_featured_listing: int, pending_featured_3d: int},
     *   approved_counts: array{accepted_plan_upgrades: int, approved_featured_listing: int, approved_featured_3d: int}
     * }
     */
    public function getDashboardData(): array
    {
        $items = $this->getAcceptedRevenueItems();
        $total = $items->sum('amount');
        $today = now()->toDateString();
        $startWeek = now()->startOfWeek();
        $startMonth = now()->startOfMonth();
        $startYear = now()->startOfYear();
        $start30 = now()->subDays(29)->startOfDay();

        $revenueToday = $items->filter(fn ($i) => $i->date->toDateString() === $today)->sum('amount');
        $revenueThisWeek = $items->filter(fn ($i) => $i->date->gte($startWeek))->sum('amount');
        $revenueThisMonth = $items->filter(fn ($i) => $i->date->gte($startMonth))->sum('amount');
        $revenueThisYear = $items->filter(fn ($i) => $i->date->gte($startYear))->sum('amount');

        $bySource = [
            'subscription' => $items->where('source', 'subscription')->sum('amount'),
            'featured_listing' => $items->where('source', 'featured_listing')->sum('amount'),
            'featured_3d_tour' => $items->where('source', 'featured_3d_tour')->sum('amount'),
        ];
        $bySourcePercent = [
            'subscription' => $total > 0 ? round($bySource['subscription'] / $total * 100, 1) : 0,
            'featured_listing' => $total > 0 ? round($bySource['featured_listing'] / $total * 100, 1) : 0,
            'featured_3d_tour' => $total > 0 ? round($bySource['featured_3d_tour'] / $total * 100, 1) : 0,
        ];

        $last30 = [];
        for ($d = 0; $d < 30; $d++) {
            $date = now()->subDays(29 - $d)->toDateString();
            $last30[$date] = $items->filter(fn ($i) => $i->date->toDateString() === $date)->sum('amount');
        }
        $byMonthThisYear = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = now()->year . '-' . str_pad((string) $m, 2, '0', STR_PAD_LEFT);
            $byMonthThisYear[$key] = $items->filter(fn ($i) => $i->date->format('Y-m') === $key)->sum('amount');
        }

        $userIds = $items->take(30)->pluck('user_id')->unique()->filter()->values()->all();
        $users = \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id');
        $recentAccepted = $items->take(15)->map(function ($i) use ($users) {
            return (object)[
                'date' => $i->date,
                'user_name' => $users->get($i->user_id)?->name ?? '—',
                'type' => $i->type,
                'related' => $i->related,
                'amount' => $i->amount,
                'status' => 'accepted',
                'source' => $i->source,
                'currency' => $this->currency,
            ];
        });

        return [
            'currency' => $this->currency,
            'total_revenue' => $total,
            'revenue_today' => $revenueToday,
            'revenue_this_week' => $revenueThisWeek,
            'revenue_this_month' => $revenueThisMonth,
            'revenue_this_year' => $revenueThisYear,
            'by_source' => $bySource,
            'by_source_percent' => $bySourcePercent,
            'last_30_days' => $last30,
            'by_month_this_year' => $byMonthThisYear,
            'recent_accepted' => $recentAccepted,
            'pending' => $this->getPendingCounts(),
            'approved_counts' => $this->getApprovedCounts(),
        ];
    }
}
