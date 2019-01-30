<?php
return [
    //'adminEmail' => 'admin@example.com',
    'frontendMenuItems' => [
        [
            'link_name' => Yii::t('app', 'Profile'),
            'url' => '/profile/view',
            'roles' => ['lender', 'borrower', 'digital-collector']
        ],
        [
            'link_name' => Yii::t('app', 'New Loan'),
            'url' => '/loan/create',
            'roles' => ['lender', 'borrower']
        ],
        [
            'link_name' => Yii::t('app', 'Loans Overdue'),
            'url' => '/loan/loans-overdue',
            'roles' => ['digital-collector']
        ],
        [
            'link_name' => Yii::t('app', 'Offers'),
            'url' => '/loan/offers',
            'roles' => ['borrower']
        ],
        [
            'link_name' => Yii::t('app', 'Requests'),
            'url' => '/loan/requests',
            'roles' => ['lender']
        ],
        [
            'link_name' => Yii::t('app', 'My Loans'),
            'url' => '/loan/my-loans',
            'roles' => ['lender', 'borrower', 'digital-collector']
        ],

    ]
];
