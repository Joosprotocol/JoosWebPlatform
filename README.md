IT-Master's Platform on Yii Framework 2
=======================================

> **NOTICE**
>
> If you have ANY trouble please firstly refer to [troubleshooting](#troubleshooting) section.


There are three applications: front end, back end, and console, each of which
is a separate Yii application.

> **NOTE:**
> This installations requires minimum version of PHP 7.1

Repository
==========

Platform configured to use custom [repository](https://repository.webphpdev.site/)

> **NOTE** Contents of repository could be browsed manually. The access password located in `auth.json`

Installation
============

## Setup virtual host(s) ##
Application configured to run as single or multiple entry point.

### Single entry point configuration ###
Host root folder and working directory should be set to project root directory

### Multiple entry point configuration ###
Host root folder still should be set to project root directory, but 2 entry points should be configured
1. Frontend working directory routed to `./frontend/web/`
2. Backend working directory routed to `./backend/web/`

## Composer ##
This project support and depends on composer for smoothe installation and update.
You you have no experience with this tool than please consult the [official documentation](https://getcomposer.org/doc/).

You could find instructions for installation [here](https://getcomposer.org/doc/00-intro.md)
> **NOTE:** Install composer globally it will save you time and keypresses

> **NOTE:** This installation is using [asset-packagist](https://asset-packagist.org/) for managing bower and npm package dependencies through Composer.
> **None additional plugins required**. Though you can continue to use `fxp/composer-asset-plugin`, app experiense will stay the same.

### Create project ###
Project support easy start with create-project
`composer create-project itmaster/platform platform-project --repository-url=https://repository.webphpdev.site --prefer-dist`

This command will initialize development version of project.

> **WARNING:**
> Project uses custom repository and will ask developer for credentials. Composer will ask you to save this credentials and you probably should do so,
> because any composer operation will require them.
>
> User: composer
>
> Password: u@siGNf17CUA

> **NOTE:**
> if you face php versions conflict, but you sure, that your web server use php>=7.1
>
> you can run composer install with parameter: `composer install --ignore-platform-reqs`

> **NOTE:**
> Some CPanel servers have multiphp feature, this can lead to composer completely misuse php versions. This can be avoided by using absolute paths to binaries
>
> ex: `/usr/local/bin/ea-php7 /usr/local/bin/composer install`
>
> The path used above just an example and should not be used as-is

## Requirements ##
You should perform requirement check on your system, this will show are you good to procced with install and may help you to locate troubles
```
#!sh
$ php requirements.php
```

## Configure VAULT storage path ##
Storage extension allows to easy save uploaded files but it should be configured.
The required configuration is `vaultPath` which should be configured in `params-local.php`, as the default we suggest to configure it in common.
And it must point to valid writable by webserver directory outside project root.

For more details about configuration please refer to storage module documentation.

## Initialize project ##
### Database ###
Create database and configurate `common/config/main-local.php` 'db' setting accordingly

### Apply migrations ###
```
#!sh
$ php yii migrate-core
$ php yii migrate-storage
```

### Seeds ###
This project template supplies default seeds for most common uses e.x. users, roles, pages. This can be done with command
```
#!sh
$ php yii core/seed/index
```

### Modules config ###
There is some default modules to template project that should be initialized before start.

> **NOTE:** You can get list of all modules supplied with your installation using command
> `$ php yii manager/status all`

These modules handles functionality that is believed will help you
```
#!sh
$ php yii core/manager/register <moduleName[,moduleName]>
```

* site
* module
* mail
* media
* snippet
* slider
* i18n

> **NOTE:** You can enable all modules using alias 'all' in command
> `$ php yii core/manager/register all`

## Troubleshooting ##

### Storage ###

1. **Vault path cannot be inside or as child of project root folder**
File storage require full preferably absolute path to project secure storage. And this path cannot be inside project root as it should not be
accessible from web. Vault path should be specified in `params-local.php` in the next format
```
#!php
'storage' => [
	'vaultPath' => 'path/to/vault',
	... => ...
]
```
 **Apply storage migration**
```
#!sh
$ php yii migrate-storage
```

Please refer to Storage manual for more info.
> **NOTE:** Please confirm that web server do have write permissions to specified folder

### i18n ###

1. **Page not found when changing language**
Language change utilizes `site` module. Please ensure that you have this module enabled.
```
#!sh
$ php yii manager/register site
```


Additional settings:
--------------------
### You may require to install next php extencions if you haven't done this already ###
+ php-intl
+ php-memcache
+ php-apc
+ php-imagick ([help](http://firstwiki.ru/index.php/%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0_Imagemagick))

> **NOTE:** please refer to your php version for proper install command, the next example provided only as visualisation
>
> e.g. `$ apt-get install php5-intl`

### Using PHP_CodeSniffer ###
```
#!sh
$ vendor/bin/phpcs <file-name>
```

## Yii 2 documentation ##
If you find yourself struggling with Yii2 you should visit [official documentation](http://www.yiiframework.com/doc-2.0/guide-index.html).
> **NOTE:** please keep in mind that many parts of default Yii application was changed for required needs,
> so not all information may be accurate and there may be specific none default implementation in project.

## Install Joos roles and permissions ##

```
php yii roles/update
```

## Cron jobs ##
Add tasks to cron with command:

```
crontab -e
```
Tasks:
```
0 * * * * php yii loan-blockchain-extractor/update >> loan_blockchain_extractions.log 2>&1
* * * * * php yii collateral/make-loans-from-platform >> collateral_loans_autocreating.log 2>&1

```

## Deploying APP to stage server ##

#### Ethereum backend settings

1. For the platform to work correctly, you must first upload and configure the project "ethereum backend".
2. Setup common/config/main.php

```php
    'components' => [
        'ethereumAPI' => [
            'class' => 'common\library\ethereum\EthereumAPI',
            'url' => 'http://localhost:3000/',
        ],
    ]
``` 
> Project "ethereum backend" is separated service. Main feature is providing access to smart contracts. It's bridge between Joos platform and contracts on blockchain. "ethereum backend" listens port 3000. It configured there as default. If "ethereum backend" will be launched on other server or other port please make sure you change main.php and set correct ip:port. 
- Check that the "ethereum backend" is always running.

#### CryptoApi settings

[CryptoAPIs](https://api.cryptoapis.io) is a trusted API provider for crypto & blockchain applications. We provide live and historical data for both Crypto market and Blockchain protocols.
This service used for transactions of crypto currencies. So we don't need start p2p heavyweight node.

1. Setup common/config/main.php
    ```php
        'components' => [
            'cryptoApi' => [
    ...
                'apiUrl' => 'https://api.cryptoapis.io',
                'apiKey' => '00d38a3e19134323f9bcc750312915b895302462',
                'environment' => 'test',
                'networks' => [
                    'bitcoin' => [
                        'test' => 'testnet',
                        'prod' => 'mainnet',
                    ],
                    'ethereum' => [
                        'test' => 'rinkeby',
                        'prod' => 'mainnet'
                    ]
                ]
            ],
        ]
    ``` 
    Settings:
- ```'environment'``` Define what name of network will be used for some blockchain. Ex: on 'test' bitcoin uses 'testnet' network, but on 'prod' it uses 'mainnet'. Can be:
  - ```'test'``` or ```'prod'```
- ```'apiKey'``` Key for access. Provided by service. Has some limitations.

#### Hub wallets settings
Platform have to hold funds somewhere between certain transactions. So we use few own wallets for funds reservations.
> Some currencies (for ex. ETH & USDT) may belong to the same blockchain network (Ethereum). In this case can we use one wallet to both or more.
> > Now we use only Ethereum based USDT. It's ERC-20 contract. Upd:27.06.19
1. Setup common/config/params.php

```php
'blockchain' => [
    'bitcoin' => [
        'hubAddress' => 'mx7ad8afsfBoYyB1TAdus6bv3Wz3e2SqCX',
        'hubWif' => 'cSzR52mn3ZG4XnuyQwspGYEu14ZecYErYpF2kks4s8pSLEwq2MaV',
    ],
    'ethereum' => [
        'hubAddress' => '0x2ba6415b8e06bcbaace060318839285ad9352da6',
        'hubPrivateKey' => 'ffdfd77b43d71d70e38722a3d15af48a0529561276a3025cf6a0ad320404bbb3',
    ],
    'ethereumUsdt' => [
        'contractAddress' => '0xc29d73460d4fc8fe79c6c45d63ced24f61848ea1',
        'hubAddress' => '0xc3e954803e3c8504a55cc947afa8fc2e0509232e',
        'hubPrivateKey' => '2fba5048ee1685c0663f5a17908452c6719b47f9e82107bec9d95caa8ac3a305',
    ]
]
```
Settings:

- ```'hubAddress'``` Address of wallet for containing funds;
- ```'hubWif'``` / ```'hubPrivateKey'``` Secret key depends of blockchain;
- ```'contractAddress'``` Address of ERC-20 USDT token.

You should change ```'contractAddress'``` setting when you switch to 'prod'. These address is unexisted on main blockchain. You must set real Tether USDT contract address. Check it here: [USD₮ supported on Ethereum ](https://tether.to/usd%E2%82%AE-and-eur%E2%82%AE-now-supported-on-ethereum/)

## Mailer setup ##

Add settings to common/config/main-local.php

```
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'host.host',
                'username' => 'mail@host.com',
                'password' => 'password',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
        ],
```