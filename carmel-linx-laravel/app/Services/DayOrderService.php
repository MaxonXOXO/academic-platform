<?php

namespace App\Services;

use Carbon\Carbon;

class DayOrderService
{
    /**
     * Supported Day Orders in order
     */
    const DAY_ORDERS = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];

    /**
     * Get active Day Order for a target date (defaults to today).
     *
     * @param string|null $targetDate Format: Y-m-d
     * @return string E.g. 'Day 1', 'Day 2'
     */
    public static function getActiveDayOrder(?string $targetDate = null): string
    {
        $targetDate = $targetDate ? Carbon::parse($targetDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $anchor = self::getAnchorData();

        $anchorDate = $anchor['anchor_date'];
        $anchorDayOrder = $anchor['anchor_day_order'];

        if (!in_array($anchorDayOrder, self::DAY_ORDERS)) {
            $anchorDayOrder = 'Day 1';
        }

        $anchorIndex = array_search($anchorDayOrder, self::DAY_ORDERS);

        if ($targetDate === $anchorDate) {
            return $anchorDayOrder;
        }

        $targetCarbon = Carbon::parse($targetDate);
        $anchorCarbon = Carbon::parse($anchorDate);

        if ($targetCarbon->greaterThan($anchorCarbon)) {
            $workingDays = 0;
            $curr = $anchorCarbon->copy()->addDay();
            while ($curr->lessThanOrEqualTo($targetCarbon)) {
                if ($curr->isWeekday()) { // Monday through Friday
                    $workingDays++;
                }
                $curr->addDay();
            }

            $newIndex = ($anchorIndex + $workingDays) % count(self::DAY_ORDERS);
            return self::DAY_ORDERS[$newIndex];
        } else {
            $workingDays = 0;
            $curr = $anchorCarbon->copy()->subDay();
            while ($curr->greaterThanOrEqualTo($targetCarbon)) {
                if ($curr->isWeekday()) {
                    $workingDays++;
                }
                $curr->subDay();
            }

            $count = count(self::DAY_ORDERS);
            $newIndex = ($anchorIndex - ($workingDays % $count) + $count) % $count;
            return self::DAY_ORDERS[$newIndex];
        }
    }

    /**
     * Update the institutional Day Order anchor.
     *
     * @param string $dayOrder E.g. 'Day 1', 'Day 2'
     * @param string|null $targetDate Format: Y-m-d
     * @param string|null $updatedBy User identifier
     * @return bool
     */
    public static function setDayOrder(string $dayOrder, ?string $targetDate = null, ?string $updatedBy = null): bool
    {
        if (!in_array($dayOrder, self::DAY_ORDERS)) {
            return false;
        }

        $targetDate = $targetDate ? Carbon::parse($targetDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $payload = [
            'anchor_date' => $targetDate,
            'anchor_day_order' => $dayOrder,
            'updated_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => $updatedBy ?? (function_exists('session') ? session('userId') : 'system')
        ];

        $path = storage_path('app/active_day_order.json');
        return file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Read anchor configuration from JSON file.
     * Fallback to default if missing.
     *
     * @return array
     */
    public static function getAnchorData(): array
    {
        $path = storage_path('app/active_day_order.json');
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            $anchorDate = $data['anchor_date'] ?? $data['date'] ?? null;
            $anchorDayOrder = $data['anchor_day_order'] ?? $data['day_order'] ?? null;

            if ($data && !empty($anchorDate) && !empty($anchorDayOrder)) {
                return [
                    'anchor_date' => $anchorDate,
                    'anchor_day_order' => $anchorDayOrder,
                    'updated_at' => $data['updated_at'] ?? null,
                    'updated_by' => $data['updated_by'] ?? null,
                ];
            }
        }

        // Default fallback anchor: 2026-08-10 (Monday) = Day 1
        return [
            'anchor_date' => '2026-08-10',
            'anchor_day_order' => 'Day 1',
            'updated_at' => null,
            'updated_by' => null,
        ];
    }
}
