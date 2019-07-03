<?php

use common\config\constant\Blockchain;

return [
    //'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'image' => ['width' => 160, 'height' => 160],
    'paginationOptions' => [10 => 10, 20 => 20, 30 => 30, 40 => 40, 50 => 50],
    'readOnlyMode' => false,
    'loan.feeManualPercent' => 15,
    'loan.feeJoosPercent' => 10,
    Blockchain::PARAM_BLOCKCHAIN => [
        Blockchain::PARAM_BITCOIN => [
            Blockchain::PARAM_HUB_ADDRESS => 'mx7ad8afsfBoYyB1TAdus6bv3Wz3e2SqCX',
            Blockchain::PARAM_HUB_WIF => 'cSzR52mn3ZG4XnuyQwspGYEu14ZecYErYpF2kks4s8pSLEwq2MaV',
        ],
        Blockchain::PARAM_ETHEREUM => [
            Blockchain::PARAM_HUB_ADDRESS => '0x2ba6415b8e06bcbaace060318839285ad9352da6',
            Blockchain::PARAM_HUB_PRIVATE_KEY => 'ffdfd77b43d71d70e38722a3d15af48a0529561276a3025cf6a0ad320404bbb3',
        ],
        Blockchain::PARAM_ETHEREUM_USDT => [
            Blockchain::PARAM_CONTRACT_ADDRESS => '0xc29d73460d4fc8fe79c6c45d63ced24f61848ea1',
            Blockchain::PARAM_HUB_ADDRESS => '0xc3e954803e3c8504a55cc947afa8fc2e0509232e',
            Blockchain::PARAM_HUB_PRIVATE_KEY => '2fba5048ee1685c0663f5a17908452c6719b47f9e82107bec9d95caa8ac3a305',
        ]
    ],
    'adminMenuItems' => [
        [
            'name' => \Yii::t('app', 'Users'),
            'slug' => 'user',
            'items' => [
                ['label' => Yii::t('app', 'Users'), 'url' => ['/user/index']],
                ['label' => Yii::t('app', 'Personal'), 'url' => ['/user-personal/index']]
            ]
        ],
        [
            'name' => \Yii::t('app', 'Pages'),
            'slug' => 'page',
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
                ['label' => Yii::t('app', 'Loans'), 'url' => ['/loan/index']],
                ['label' => Yii::t('app', 'Status Histories'), 'url' => ['/loan-status-history/index']],
            ]
        ],
        [
            'name' => \Yii::t('app', 'Collateral'),
            'slug' => 'loan',
            'items' => [
                ['label' => Yii::t('app', 'Collateral'), 'url' => ['/collateral/index']],
                ['label' => Yii::t('app', 'Loan'), 'url' => ['/collateral-loan/index']],
            ]
        ],
        [
            'name' => \Yii::t('app', 'Notification'),
            'slug' => 'notification',
            'url' => ['/notification/index'],
            'items' => []
        ],
    ]
];
