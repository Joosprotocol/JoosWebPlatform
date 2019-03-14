<?php
return [
    //'adminEmail' => 'admin@example.com',
    'paginationOptions' => [
        10 => Yii::t('app', 'SHOW') . ' ' . 10 . ' ' . Yii::t('app', 'RESULTS'),
        20 => Yii::t('app', 'SHOW') . ' ' . 20 . ' ' . Yii::t('app', 'RESULTS'),
        30 => Yii::t('app', 'SHOW') . ' ' . 30 . ' ' . Yii::t('app', 'RESULTS'),
        40 => Yii::t('app', 'SHOW') . ' ' . 40 . ' ' . Yii::t('app', 'RESULTS'),
        50 => Yii::t('app', 'SHOW') . ' ' . 50 . ' ' . Yii::t('app', 'RESULTS'),
    ],
    'frontendMenuSidebarItems' => [
        [
            'label' => Yii::t('app', 'New Loan'),
            'url' => '/loan/create',
            'visible' => ['lender', 'borrower'],
            'iconClass' => 'sidebar-icon-plus'
        ],
        [
            'label' => Yii::t('app', 'Loans Overdue'),
            'url' => '/loan/loans-overdue',
            'visible' => ['digital-collector'],
            'iconClass' => 'sidebar-icon-loans'
        ],
        [
            'label' => Yii::t('app', 'Offers'),
            'url' => '/loan/offers',
            'visible' => ['borrower'],
            'iconClass' => 'sidebar-icon-loans'
        ],
        [
            'label' => Yii::t('app', 'Requests'),
            'url' => '/loan/requests',
            'visible' => ['lender'],
            'iconClass' => 'sidebar-icon-loans'
        ],
        [
            'label' => Yii::t('app', 'My Loans'),
            'url' => '/loan/my-loans',
            'visible' => ['lender', 'borrower', 'digital-collector'],
            'iconClass' => 'sidebar-icon-pen'
        ],
        [
            'label' => Yii::t('app', 'Logout'),
            'url' => '/auth/logout',
            'visible' => '@',
            'iconClass' => 'sidebar-icon-login'
        ],
        [
            'label' => Yii::t('app', 'Login'),
            'url' => '/auth/login',
            'visible' => '?',
            'iconClass' => 'sidebar-icon-login'
        ]

    ],

    'frontendMenuNotifications' => [
        [
            'label' => Yii::t('app', 'Notifications'),
            'url' => '/notification/index',
            'visible' => '@',
            'iconClass' => 'glyphicon glyphicon-envelope'
        ]
    ],

    'frontendMenuProfile' => [
        [
            'label' => Yii::t('app', 'Profile'),
            'url' => '/profile/view',
            'type' => 'avatar',
            'visible' => '@',
            'items' => [
                [
                    'label' => Yii::t('app', 'View Profile'),
                    'url' => '/profile/view',
                    'visible' => '@'
                ],
                [
                    'label' => Yii::t('app', 'Update Profile'),
                    'url' => '/profile/update',
                    'visible' => '@'
                ],
                [
                    'label' => Yii::t('app', 'New Loan'),
                    'url' => '/loan/create',
                    'visible' => ['lender', 'borrower'],
                    'iconClass' => 'sidebar-icon-plus'
                ],
                [
                    'label' => Yii::t('app', 'Loans Overdue'),
                    'url' => '/loan/loans-overdue',
                    'visible' => ['digital-collector'],
                    'iconClass' => 'sidebar-icon-loans'
                ],
                [
                    'label' => Yii::t('app', 'Offers'),
                    'url' => '/loan/offers',
                    'visible' => ['borrower'],
                    'iconClass' => 'sidebar-icon-loans'
                ],
                [
                    'label' => Yii::t('app', 'Requests'),
                    'url' => '/loan/requests',
                    'visible' => ['lender'],
                    'iconClass' => 'sidebar-icon-loans'
                ],
                [
                    'label' => Yii::t('app', 'My Loans'),
                    'url' => '/loan/my-loans',
                    'visible' => ['lender', 'borrower', 'digital-collector'],
                    'iconClass' => 'sidebar-icon-pen'
                ],
                [
                    'label' => Yii::t('app', 'Logout'),
                    'url' => '/auth/logout',
                    'visible' => '@',
                    'iconClass' => 'sidebar-icon-login'
                ],
                [
                    'label' => Yii::t('app', 'Login'),
                    'url' => '/auth/login',
                    'visible' => '?',
                    'iconClass' => 'sidebar-icon-login'
                ]
            ]
        ]
    ],

    'customRoutes' => [
    '<controller:(auth)>/<action:(signup|login|request-password-reset)>' => "<controller>/<action>",
    '<controller:(profile)>/<action:(view|update|public)>' => "<controller>/<action>",
]
];
