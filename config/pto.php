<?php

/*
|--------------------------------------------------------------------------
| PTO policy configuration (2B Environmental Employee Handbook 2025)
|--------------------------------------------------------------------------
| Accrual is tenure-based over 26 pay periods/year (bi-weekly). The cap is
| 1.5x the annual accrual. These values come straight from the handbook; the
| ambiguous rules (probation, tier boundaries) are settings here so they can
| be adjusted once the client confirms — without touching code.
*/

return [

    // Pay periods per year (bi-weekly payroll per the handbook table).
    'pay_periods_per_year' => 26,

    // Probation length; whether PTO accrues during it (default: accrue only after).
    'probation_days'          => 90,
    'accrue_during_probation' => false,

    // Tier boundary rule: at exactly 5 and 10 years, use the HIGHER tier.
    // (Set false to use the lower tier at the boundary — confirm with client.)
    'higher_tier_at_boundary' => true,

    /*
    | Tenure tiers. `min_years` is the completed-years threshold where the tier
    | begins. per_pay_period = hours earned each pay period; annual = handbook's
    | stated annual figure; cap = maximum balance (1.5x annual).
    */
    'tiers' => [
        [
            'key'            => 'probation_year',
            'label'          => 'Day 1 after 90-day probation (first year)',
            'min_years'      => 0,
            'per_pay_period' => 4.40,
            'annual'         => 88,    // 11 days — post-probation partial first year
            'cap'            => 132,   // 1.5 x 88
        ],
        [
            'key'            => 'year_1_5',
            'label'          => '1st – 5th year',
            'min_years'      => 1,
            'per_pay_period' => 4.92,
            'annual'         => 128,   // 16 days
            'cap'            => 192,   // 1.5 x 128
        ],
        [
            'key'            => 'year_5_10',
            'label'          => '5th – 10th year',
            'min_years'      => 5,
            'per_pay_period' => 6.46,
            'annual'         => 168,   // 21 days
            'cap'            => 252,   // 1.5 x 168
        ],
        [
            'key'            => 'year_10_plus',
            'label'          => '10th year and thereafter',
            'min_years'      => 10,
            'per_pay_period' => 8.31,
            'annual'         => 216,   // 27 days
            'cap'            => 324,   // 1.5 x 216
        ],
    ],
];
