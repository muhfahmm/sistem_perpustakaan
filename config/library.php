<?php

return [
    'max_active_loans' => (int) env('LIBRARY_MAX_ACTIVE_LOANS', 3),
    'default_loan_days' => (int) env('LIBRARY_DEFAULT_LOAN_DAYS', 7),
];
