<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $primaryKey = 'Loyalty_ID';

    protected $fillable = [
        'Customer_ID',
        'points_earned',
        'points_used',
        'current_balance',
        'tier_level',
        'last_activity_date',
        'notes',
    ];

    protected $casts = [
        'last_activity_date' => 'date',
        'points_earned' => 'integer',
        'points_used' => 'integer',
        'current_balance' => 'integer',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'Customer_ID', 'Customer_ID');
    }

    // Loyalty tier methods
    public function getTierColorAttribute()
    {
        $colors = [
            'bronze' => 'bg-amber-100 text-amber-800',
            'silver' => 'bg-gray-100 text-gray-800',
            'gold' => 'bg-yellow-100 text-yellow-800',
            'platinum' => 'bg-purple-100 text-purple-800',
        ];

        return $colors[$this->tier_level] ?? 'bg-gray-100 text-gray-800';
    }

    public function getTierIconAttribute()
    {
        $icons = [
            'bronze' => '🥉',
            'silver' => '🥈',
            'gold' => '🥇',
            'platinum' => '💎',
        ];

        return $icons[$this->tier_level] ?? '⭐';
    }

    // Business logic methods
    public function addPoints($points, $notes = null)
    {
        // Ensure points is a positive number
        $points = max(0, (int) $points);
        
        $this->points_earned += $points;
        $this->current_balance += $points;
        $this->last_activity_date = now();
        if ($notes) {
            $this->notes = $notes;
        }
        $this->updateTier();
        $this->save();
    }

    public function usePoints($points, $notes = null)
    {
        // Ensure points is a positive number
        $points = max(0, (int) $points);
        
        if ($this->current_balance >= $points) {
            $this->points_used += $points;
            $this->current_balance -= $points;
            $this->last_activity_date = now();
            if ($notes) {
                $this->notes = $notes;
            }
            $this->save();
            return true;
        }
        return false;
    }

    public function updateTier()
    {
        // Ensure points_earned is a valid number
        $pointsEarned = max(0, (int) $this->points_earned);
        
        $newTier = 'bronze';
        
        if ($pointsEarned >= 1000) {
            $newTier = 'platinum';
        } elseif ($pointsEarned >= 500) {
            $newTier = 'gold';
        } elseif ($pointsEarned >= 100) {
            $newTier = 'silver';
        }

        if ($this->tier_level !== $newTier) {
            $this->tier_level = $newTier;
        }
    }

    public function getNextTierProgressAttribute()
    {
        $tierThresholds = [
            'bronze' => 100,
            'silver' => 500,
            'gold' => 1000,
            'platinum' => null,
        ];

        $nextThreshold = $tierThresholds[$this->tier_level];
        if (!$nextThreshold) {
            return 100; // Platinum is max tier
        }

        $currentProgress = $this->points_earned;
        
        // Set the correct previous threshold based on current tier
        if ($this->tier_level === 'bronze') {
            $previousThreshold = 0; // Start from 0 for bronze
        } elseif ($this->tier_level === 'silver') {
            $previousThreshold = 100;
        } elseif ($this->tier_level === 'gold') {
            $previousThreshold = 500;
        } else {
            $previousThreshold = 0; // Fallback
        }

        $range = $nextThreshold - $previousThreshold;
        
        // Prevent division by zero
        if ($range <= 0) {
            return 0;
        }
        
        $progress = $currentProgress - $previousThreshold;
        
        // Ensure progress is not negative
        if ($progress < 0) {
            $progress = 0;
        }
        
        return min(100, max(0, ($progress / $range) * 100));
    }
}

