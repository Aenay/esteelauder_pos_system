<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\LoyaltyPoint;
use App\Models\Order;
use Carbon\Carbon;

class LoyaltyService
{
    /**
     * Calculate loyalty points based on purchase amount
     * 1 point for every $10 spent
     */
    public function calculatePointsForPurchase(float $amount): int
    {
        return (int) floor($amount / 10);
    }

    /**
     * Award loyalty points to a customer for a purchase
     * Only awards points to registered members (not walk-in customers)
     */
    public function awardPointsForPurchase(Order $order): ?int
    {
        // Only award points to registered members
        if ($order->customer_type === 'walk_in') {
            return null;
        }

        // Check if customer exists and is a member
        if (!$order->customer || $order->customer->Customer_Type === 'walk_in') {
            return null;
        }

        // Calculate points based on final amount
        $pointsToAward = $this->calculatePointsForPurchase($order->Final_Amount);
        
        if ($pointsToAward <= 0) {
            return 0;
        }

        // Get or create loyalty record for the customer
        $loyaltyPoint = LoyaltyPoint::firstOrCreate(
            ['Customer_ID' => $order->Customer_ID],
            [
                'points_earned' => 0,
                'points_used' => 0,
                'current_balance' => 0,
                'tier_level' => 'bronze',
                'last_activity_date' => now(),
                'notes' => 'Initial loyalty record created'
            ]
        );

        // Award points
        $loyaltyPoint->addPoints($pointsToAward, "Purchase reward for Order #{$order->Order_ID} - Amount: $" . number_format($order->Final_Amount, 2));

        return $pointsToAward;
    }

    /**
     * Get loyalty summary for a customer
     */
    public function getCustomerLoyaltySummary(int $customerId): array
    {
        $customer = Customer::with('loyaltyPoints')->find($customerId);
        
        if (!$customer) {
            return [
                'has_loyalty' => false,
                'points_balance' => 0,
                'tier_level' => 'none',
                'next_tier_progress' => 0
            ];
        }

        $loyaltyPoint = $customer->loyaltyPoints;
        
        if (!$loyaltyPoint) {
            return [
                'has_loyalty' => false,
                'points_balance' => 0,
                'tier_level' => 'none',
                'next_tier_progress' => 0
            ];
        }

        // Safely get the next tier progress with error handling
        try {
            $nextTierProgress = $loyaltyPoint->next_tier_progress;
        } catch (\Exception $e) {
            // Fallback to 0 if there's an error calculating progress
            $nextTierProgress = 0;
        }

        return [
            'has_loyalty' => true,
            'points_balance' => $loyaltyPoint->current_balance,
            'tier_level' => $loyaltyPoint->tier_level,
            'next_tier_progress' => $nextTierProgress,
            'total_earned' => $loyaltyPoint->points_earned,
            'total_used' => $loyaltyPoint->points_used
        ];
    }

    /**
     * Check if a customer is eligible for loyalty points
     */
    public function isCustomerEligibleForLoyalty(int $customerId): bool
    {
        $customer = Customer::find($customerId);
        
        if (!$customer) {
            return false;
        }

        // Only registered members are eligible (not walk-in customers)
        return $customer->Customer_Type !== 'walk_in';
    }

    /**
     * Get loyalty tier requirements
     */
    public function getTierRequirements(): array
    {
        return [
            'bronze' => ['min_points' => 0, 'max_points' => 99],
            'silver' => ['min_points' => 100, 'max_points' => 499],
            'gold' => ['min_points' => 500, 'max_points' => 999],
            'platinum' => ['min_points' => 1000, 'max_points' => null]
        ];
    }
}
