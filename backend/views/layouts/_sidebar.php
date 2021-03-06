<?php
/**
 * @var $this yii\web\View
 */
use backend\assets\BackendAsset;
use backend\models\SystemLog;
use backend\widgets\Menu;
use common\models\TimelineEvent;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\log\Logger;
use yii\widgets\Breadcrumbs;

?>
<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <?php echo Menu::widget([
                'options' => ['class' => 'sidebar-menu'],
                'linkTemplate' => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                'activateParents' => true,
                'items' => [
                    [
                        'label' => Yii::t('backend', 'ICO'),
                        'icon' => '<i class="fa fa-empire"></i>',
                        'url' => ['/ico'],
                    ],         
                    [
                        'label' => Yii::t('backend', 'Wallets'),
                        'icon' => '<i class="fa fa-google-wallet"></i>',
                        'url' => ['/wallet'],
                    ],
                    [
                        'label' => Yii::t('backend', 'Event Logs'),
                        'icon' => '<i class="fa fa-calendar"></i>',
                        'url' => ['/event'],
                        //'badge' => TimelineEvent::find()->today()->count(),
                        //'badgeBgClass' => 'label-success',
                    ],          
                    [
                        'label' => Yii::t('backend', 'Team'),
                        'icon' => '<i class="fa fa-users"></i>',
                        'url' => ['/team'],
                        //'badge' => TimelineEvent::find()->today()->count(),
                        //'badgeBgClass' => 'label-success',
                    ],                        
                    [
                        'label' => Yii::t('backend', 'Settings'),
                        'icon' => '<i class="fa fa-cogs"></i>',
                        'url' => ['/setting'],
                    ],
                    [
                        'label' => Yii::t('backend', 'Security(2FA)'),
                        'icon' => '<i class="fa fa-expeditedssl"></i>',
                        'url' => ['/security'],
                    ],     
                    [
                        'label' => Yii::t('backend', 'Affilicate Tools'),
                        'icon' => '<i class="fa fa-handshake-o"></i>',
                        'url' => ['/tool/index'],
                    ],
                    [
                        'label' => Yii::t('backend', 'Support'),
                        'icon' => '<i class="fa fa-life-ring" aria-hidden="true"></i>',
                        'url' => ['/support/ticket'],
                    ],
                    [
                        'label' => Yii::t('backend', 'Logout'),
                        'icon' => '<i class="fa fa-sign-out"></i>',
                        'url' => ['/logout'],
                        'template' => "<a href=\"{url}\" data-method=\"post\">\n{icon}\n{label}\n{right-icon}\n{badge}</a>",
                    ],

                    [
                        'label' => Yii::t('backend', 'Admin'),
                        'options' => ['class' => 'header'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'List wallet'),
                        'icon' => '<i class="fa fa-list"></i>',
                        'url' => ['/wallet/list'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Transaction'),
                        'icon' => '<i class="fa fa-line-chart"></i>',
                        'url' => ['/deposit'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Withdraw'),
                        'icon' => '<i class="fa fa-arrow-circle-o-down"></i>',
                        'url' => ['/withdraw'],
                        'badge' => \common\models\Withdraw::find()->pending()->count(),
                        'badgeBgClass' => 'label-danger',
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Support'),
                        'icon' => '<i class="fa fa-life-ring"></i>',
                        'url' => ['/support/admin'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Member'),
                        'icon' => '<i class="fa fa-users"></i>',
                        'url' => ['/member/index'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Landing'),
                        'icon' => '<i class="fa fa-globe"></i>',
                        'url' => ['/site/landing'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Send mail'),
                        'icon' => '<i class="fa fa-envelope"></i>',
                        'url' => ['/site/sendmail'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Roadmap'),
                        'icon' => '<i class="fa fa-list"></i>',
                        'url' => ['/roadmap'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Config'),
                        'icon' => '<i class="fa fa-cog"></i>',
                        'url' => ['/site/settings'],
                        'visible' => Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('backend', 'Notification'),
                        'icon' => '<i class="fa fa-comment"></i>',
                        'url' => ['/notifi/index'],
                        'visible' => Yii::$app->user->can('administrator')
                    ],
                    [
                        'label' => Yii::t('backend', 'Admin Team'),
                        'icon' => '<i class="fa fa-list"></i>',
                        'url' => ['/admin-team/index'],
                        'visible' => Yii::$app->user->can('administrator')
                    ],
                    [
                        'label' => Yii::t('backend', 'Admin Wallet'),
                        'icon' => '<i class="fa fa-list"></i>',
                        'url' => ['/admin-wallet/index'],
                        'visible' => Yii::$app->user->can('administrator')
                    ],
                    [
                        'label' => Yii::t('backend', 'Users'),
                        'icon' => '<i class="fa fa-users"></i>',
                        'url' => ['/user/index'],
                        'visible' => Yii::$app->user->can('administrator')
                    ],
                    [
                        'label' => Yii::t('backend', 'Content'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-edit"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Roadmap'), 'url' => ['/roadmap/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Static pages'), 'url' => ['/page/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Articles'), 'url' => ['/article/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Article Categories'), 'url' => ['/article-category/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Text Widgets'), 'url' => ['/widget-text/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Menu Widgets'), 'url' => ['/widget-menu/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Carousel Widgets'), 'url' => ['/widget-carousel/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                        ],
                        'visible' => Yii::$app->user->can('administrator')
                    ],
                    [
                        'label' => Yii::t('backend', 'Other'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-cogs"></i>',
                        'options' => ['class' => 'treeview'],
                        'visible' => Yii::$app->user->can('administrator'),
                        'items' => [
                            [
                                'label' => Yii::t('backend', 'i18n'),
                                'url' => '#',
                                'icon' => '<i class="fa fa-flag"></i>',
                                'options' => ['class' => 'treeview'],
                                'items' => [
                                    ['label' => Yii::t('backend', 'i18n Source Message'), 'url' => ['/i18n/i18n-source-message/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                    ['label' => Yii::t('backend', 'i18n Message'), 'url' => ['/i18n/i18n-message/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ]
                            ],
                            ['label' => Yii::t('backend', 'Key-Value Storage'), 'url' => ['/key-storage/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'File Storage'), 'url' => ['/file-storage/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Cache'), 'url' => ['/cache/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'File Manager'), 'url' => ['/file-manager/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            [
                                'label' => Yii::t('backend', 'System Information'),
                                'url' => ['/system-information/index'],
                                'icon' => '<i class="fa fa-angle-double-right"></i>'
                            ],
                            [
                                'label' => Yii::t('backend', 'Logs'),
                                'url' => ['/log/index'],
                                'icon' => '<i class="fa fa-angle-double-right"></i>',
                                'badge' => SystemLog::find()->count(),
                                'badgeBgClass' => 'label-danger',
                            ],
                        ]
                    ]
                ]
            ]) ?>
        </section>
        <!-- /.sidebar -->
    </aside>
