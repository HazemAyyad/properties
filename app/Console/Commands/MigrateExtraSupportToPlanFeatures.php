<?php

namespace App\Console\Commands;

use App\Models\Dashboard\Plan;
use App\Models\Dashboard\PlanFeature;
use Illuminate\Console\Command;

/**
 * One-way data migration: copies existing plans.extra_support (JSON en/ar)
 * into plan_features rows. Safe to run multiple times (idempotent).
 *
 * Duplicate prevention: for each plan we only create a plan_feature if there
 * is no existing feature for that plan with the same title (en and ar).
 * So re-running the command will not create duplicate feature rows.
 */
class MigrateExtraSupportToPlanFeatures extends Command
{
    protected $signature = 'plans:migrate-extra-support-to-features';

    protected $description = 'Migrate plans.extra_support data into plan_features (idempotent, no duplicates)';

    public function handle(): int
    {
        $this->info('Migrating extra_support from plans to plan_features...');

        $plans = Plan::with('features')->get();
        $created = 0;
        $skipped = 0;

        foreach ($plans as $plan) {
            $extraSupport = $plan->getRawOriginal('extra_support');
            if (is_string($extraSupport)) {
                $extraSupport = json_decode($extraSupport, true) ?: [];
            }
            if (! is_array($extraSupport)) {
                $skipped++;
                continue;
            }
            $en = isset($extraSupport['en']) ? trim((string) $extraSupport['en']) : '';
            $ar = isset($extraSupport['ar']) ? trim((string) $extraSupport['ar']) : '';
            if ($en === '' && $ar === '') {
                $skipped++;
                continue;
            }
            if (strtolower($en) === 'none' && (trim((string) ($ar ?? '')) === '' || trim((string) $ar) === 'لا يوجد')) {
                $en = 'none';
                $ar = $ar ?: 'لا يوجد';
            }

            // Duplicate prevention: same plan_id + same title (en & ar) => skip
            $alreadyExists = $plan->features->contains(function (PlanFeature $f) use ($en, $ar) {
                return trim((string) $f->getTranslation('title', 'en')) === $en
                    && trim((string) $f->getTranslation('title', 'ar')) === $ar;
            });
            if ($alreadyExists) {
                $skipped++;
                continue;
            }

            PlanFeature::query()->create([
                'plan_id' => $plan->id,
                'title' => [
                    'en' => $en,
                    'ar' => $ar,
                ],
                'status' => 1,
            ]);
            $created++;
        }

        $this->info("Done. Created: {$created}, Skipped (empty or duplicate): {$skipped}.");

        return self::SUCCESS;
    }
}
