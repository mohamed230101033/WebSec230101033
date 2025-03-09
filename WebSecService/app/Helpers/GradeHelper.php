<?php

if (!function_exists('calculateGPA')) {
    function calculateGPA($grades) {
        $totalPoints = 0;
        $totalCreditHours = 0;
        foreach ($grades as $grade) {
            $points = match($grade->grade) {
                'A' => 4,
                'B' => 3,
                'C' => 2,
                'D' => 1,
                'F' => 0,
                default => 0
            };
            $totalPoints += $points * $grade->credit_hours;
            $totalCreditHours += $grade->credit_hours;
        }
        return $totalCreditHours ? round($totalPoints / $totalCreditHours, 2) : 0;
    }
}
