<?php

return [
    /*
     * Minimum order value (in Naira) for free shipping.
     */
    'free_shipping_threshold' => env('FREE_SHIPPING_THRESHOLD', 50000),

    /*
     * Loyalty points awarded for leaving a product review.
     */
    'review_loyalty_points' => env('REVIEW_LOYALTY_POINTS', 50),
];
