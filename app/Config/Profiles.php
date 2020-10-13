<?php
namespace Config;

/**
 * DocTypes
 *
 * @package Config
 */
class Profiles
{

    public $assets = array();
    public $sidebar_nav = false;

    public function __construct($profile)
    {
  
        $this->inboxModel = model('InboxModel', true, $this->db);
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
       

        $this->assets = $this->assets($profile);
        $this->sidebar_nav = $this->sidebar_nav($profile);

        helper(['permission']);
    }

    public function assets($profile)
    {

        $theme_profile = ASSETS . 'css/themes/' . $profile;
        //If exist a theme for this profile
        $theme = file_exists(ROOTPATH . 'public/' . $theme_profile . '.css') ? $theme_profile : ASSETS . 'css/themes/cardata';
        $assets = array(
            'default' => [
                'css' => [
                    //Library
                    LIBRARY . 'fontawesome-free/css/all.min',
                    LIBRARY . 'flag-icon-css/css/flag-icon.min',
                    LIBRARY . 'select2/css/select2.min',
                    LIBRARY . 'toastr/toastr.min',
                    LIBRARY . 'select2-bootstrap4-theme/select2-bootstrap4.min',
                    LIBRARY . 'icheck-bootstrap/icheck-bootstrap.min',
                    LIBRARY . 'bootstrap4-toggle/css/bootstrap4-toggle.min',
                    //General
                    $theme,
                    ASSETS . 'css/global',
                ],
                'js' => [
                    //Library
                    LIBRARY . 'jquery/jquery.min',
                    LIBRARY . 'popper/popper.min',
                    LIBRARY . 'bootstrap/js/bootstrap.bundle.min',
                    LIBRARY . 'toastr/toastr.min',
                    LIBRARY . 'theme-lte/js/adminlte',
                    LIBRARY . 'select2/js/select2.min',
                    LIBRARY . 'bootstrap4-toggle/js/bootstrap4-toggle.min',
                    //General
                    ASSETS . 'js/functions'
                ]
            ]
        );

        return $assets;
    }

    public function sidebar_nav($profile)
    {

        $adm = [
                    'icon' => '<i class="nav-icon fas fa-cogs"></i>',
                    'name' => 'Administration',
                    'sub_nav' => [
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Users',
                            'url' => route_to('user_list'),
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Permissions',
                            'url' => route_to('groups_permissions_list'),
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Profiles',
                            'url' => '/'
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Traductions',
                            'url' => route_to('traductions_list')
                        ]
                        
                    ]
                ];

        if(isAdmin()) {
            array_push($adm['sub_nav'], [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Pricing',
                            'url' => route_to('pricing')
                        ]);
        }

        $countUnread = $this->inboxModel->getUnreadMessagesCount();
        $nav = ['cardata' => [
                'new_campaign' => [
                    'icon' => '<i class="nav-icon fas fa-plus"></i>',
                    'name' => 'New campaign',
                    'url' => route_to('create_campaign'),
                    'class' => 'bg-success mb-3'
                ],
                'dashboard' => [
                    'icon' => '<i class="nav-icon fas fa-tachometer-alt"></i>',
                    'name' => 'Dashboard',
                    'url' => '/',
                ],
                'campaigns' => [
                    'icon' => '<i class="nav-icon fas fa-bullhorn"></i>',
                    'name' => 'Campaigns',
                    'url' => '/',
                    'sub_nav' => [
                        [
                            'icon' => '<i class="nav-icon fas fa-plus"></i>',
                            'name' => 'List',
                            'url' => route_to('campaign_list'),
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Create',
                            'url' => route_to('create_campaign')
                        ],
                        [
                            'icon' => '<i class="nav-icon fas fa-plus"></i>',
                            'name' => 'BlackList file',
                            'url'  => route_to('blacklistfile_list'),
                        ],
                    ]
                ],
                'statistics' => [
                    'icon' => '<i class="nav-icon fas fa-chart-pie"></i>',
                    'name' => 'Statistics',
                    'url' => '/',
                ],
                'communication_plan' => [
                    'icon' => '<i class="nav-icon far fa-calendar-alt"></i>',
                    'name' => 'Communication plan',
                    'url' => route_to('communicationplan_list'),
                ],
                'visualslib' => [
                    'icon' => '<i class="nav-icon far fa-images"></i>',
                    'name' => 'Visuals Library',
                    'sub_nav' => [
                        [
                            'icon' => '<i class="nav-icon fas fa-plus"></i>',
                            'name' => 'Add visual',
                            'url' => route_to('visual_manage'),
                            'class' => 'bg-success'
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Library',
                            'url' => '/visualslib'
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Manage Categories',
                            'url' => route_to('visual_categories'),
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Manage features',
                            'url' => route_to('visual_features'),
                        ],
                    ]
                ],
                'companies' => [
                    'icon' => '<i class="nav-icon far fa-building"></i>',
                    'name' => 'Companies',
                    'sub_nav' => [
                        [
                            'icon' => '<i class="nav-icon fas fa-plus"></i>',
                            'name' => 'List',
                            'url' => route_to('company_list'),
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Create',
                            'url' => route_to('company_create')
                        ],
                    ]
                ],
                'messaging' => [
                    'icon' => '<i class="nav-icon far fa-envelope"></i>',
                    'name' => 'Messaging <span id="badgeUnread" class="badge badge-danger right">' . $countUnread . '</span>',
                    'url' => '/inbox',
                ],
                'administration' => $adm,
                'billing' => [
                    'icon' => '<i class="nav-icon fas fa-file-invoice"></i>',
                    'name' => 'Billing',
                    'sub_nav' => [
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Order',
                            'url' => route_to('order_list')
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Invoice',
                            'url' => '/invoice'
                        ],
                        [
                            'icon' => '<i class="fas fa-angle-right"></i>',
                            'name' => 'Payment method',
                            'url' => '/payment_method'
                        ]
                    ]
                ],
                'contact' => [
                    'icon' => '<i class="nav-icon fas fa-edit"></i>',
                    'name' => 'Contact',
                    'url' => '/',
                ],
                'help' => [
                    'icon' => '<i class="nav-icon far fa-life-ring"></i>',
                    'name' => 'Help',
                    'url' => '/',
                ],
//            'dashboard' => [
//                    'icon' => '<i class="nav-icon fas fa-tachometer-alt"></i>',
//                    'name' => 'Dashboard',
//                    'sub_nav' => [
//                        [
//                            'icon' => '<i class="nav-icon fas fa-tachometer-alt"></i>',
//                            'name' => 'Dashboard',
//                            'url' => '/'
//                        ],
//                    ]
//                ],
            ]
        ];
        $retour = array_key_exists($profile, $nav) ? $nav[$profile] : false;
        return $retour;
    }
}
