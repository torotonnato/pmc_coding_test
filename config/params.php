<?php

return [
    'adminEmail' => 'admin@email.org',
    'senderEmail' => 'noreply@email.org',
    'senderName' => 'email.org mailer',
    
    'username' => [
        'minLen' => 4,
        'maxLen' => 32
    ],
    'password' => [
        'strength' => [
            'enable' => true,
            'minLen' => 8,
            'maxLen' => 64,
            'minUpperCaseCPs' => 1,
            'minLowerCaseCPs' => 1,
            'minDigits' => 1,
            'minPuncts' => 1
        ],
        'hashCost' => 12      // Watchout, logarithmic scale
    ],
    'authKeyLen' => 32,
    'auto_login_expiration' => 3600 * 24 * 30,
    
    'cars' => [
        'modelRegex' => '/^[a-zA-Z0-9 ]{4,24}$/',
        'minYear' => 1999,
        'minPrice' => 1,
        'maxPrice' => 250000,
        'maxKm' => 500000
    ],

    'uploadPath' => '/var/www/html/pmc/uploads/',
    'servePath' => '/pmc/uploads/'
];
