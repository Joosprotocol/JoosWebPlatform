<?php
return [
    //'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'image' => ['width' => 160, 'height' => 160],
    'paginationOptions' => [10 => 10, 20 => 20, 30 => 30, 40 => 40, 50 => 50],
    'readOnlyMode' => false,
    'adminMenuItems' => [
        [
            'name' => \Yii::t('app', 'Users'),
            'slug' => 'user',
            'items' => []
        ],
        [
            'name' => \Yii::t('app', 'Roles'),
            'slug' => 'role',
            'items' => []
        ],
        [
            'name' => \Yii::t('app', 'Countries'),
            'slug' => 'country',
            'items' => []
        ],
        [
            'name' => \Yii::t('app', 'Menus'),
            'slug' => 'menu',
            'items' => []
        ],
        [
            'name' => \Yii::t('app', 'Pages'),
            'slug' => 'page',
            'items' => []
        ],
        [
            'name' => \Yii::t('app', 'Categories'),
            'slug' => 'category',
            'items' => []
        ],
        [
            'name' => \Yii::t('app', 'Settings'),
            'slug' => 'setting',
            'items' => []
        ],
        [
            'name' => \Yii::t('app', 'Loan'),
            'slug' => 'loan',
            'items' => [
                ['label' => Yii::t('app', 'Loans'), 'url' => ['/loan']],
                ['label' => Yii::t('app', 'Status Histories'), 'url' => ['/loan-status-history']],
            ]
        ],
        [
            'name' => \Yii::t('app', 'Payments'),
            'slug' => 'loan',
            'items' => []
        ]
    ]
];
