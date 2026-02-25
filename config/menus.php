<?php

/**
 * Menu and Feature Configuration
 * Defines which roles can access which menus and features
 */

return [
    'menu_items' => [
        // Dashboard
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard',
            'roles' => ['admin', 'direktur', 'manager', 'supervisor', 'staff'],
            'permissions' => [], // If empty, only roles check is used
        ],

        // My Assets
        [
            'id' => 'my_assets',
            'label' => 'Asset Saya',
            'route' => 'assets.my-assets',
            'icon' => 'backpack',
            'roles' => ['admin', 'direktur', 'manager', 'supervisor', 'staff'],
            'permissions' => ['view_asset'],
        ],

        // Asset Management
        [
            'id' => 'asset_management',
            'label' => 'Manajemen Asset',
            'route' => 'assets.index',
            'icon' => 'inventory_2',
            'roles' => ['admin', 'direktur', 'manager', 'supervisor'],
            'permissions' => ['view_asset'],
        ],

        // Asset Transfer
        [
            'id' => 'asset_transfer',
            'label' => 'Transfer Asset',
            'route' => 'asset-transfers.index',
            'icon' => 'compare_arrows',
            'roles' => ['admin', 'direktur', 'manager', 'supervisor', 'staff'],
            'permissions' => ['view_asset'],
        ],

        // Maintenance
        [
            'id' => 'maintenance',
            'label' => 'Pemeliharaan',
            'route' => 'maintenance.index',
            'icon' => 'build',
            'roles' => ['admin', 'direktur', 'manager', 'supervisor', 'technician'],
            'permissions' => [],
        ],

        // Maintenance Schedule
        [
            'id' => 'maintenance_schedule',
            'label' => 'Jadwal Pemeliharaan',
            'route' => 'maintenance-schedule.index',
            'icon' => 'event_repeat',
            'roles' => ['admin', 'direktur', 'manager', 'supervisor'],
            'permissions' => [],
        ],

        // Technician Area (submenu group)
        [
            'id' => 'technician_area',
            'label' => 'Area Teknisi',
            'icon' => 'handyman',
            'roles' => ['admin', 'technician'],
            'permissions' => [],
            'submenu' => [
                [
                    'id' => 'technician_dashboard',
                    'label' => 'Dashboard',
                    'route' => 'technician.dashboard',
                    'icon' => 'dashboard',
                    'roles' => ['admin', 'technician'],
                ],
                [
                    'id' => 'technician_work',
                    'label' => 'Pekerjaan Saya',
                    'route' => 'technician.maintenance',
                    'icon' => 'task_alt',
                    'roles' => ['admin', 'technician'],
                ],
                [
                    'id' => 'technician_statistics',
                    'label' => 'Statistik',
                    'route' => 'technician.statistics',
                    'icon' => 'trending_up',
                    'roles' => ['admin', 'technician'],
                ],
            ],
        ],

        // Master Data (submenu group)
        [
            'id' => 'master_data',
            'label' => 'Master Data',
            'icon' => 'database',
            'roles' => ['admin', 'direktur', 'manager'],
            'permissions' => [],
            'submenu' => [
                [
                    'id' => 'user_management',
                    'label' => 'Manajemen User',
                    'route' => 'users.index',
                    'icon' => 'person',
                    'roles' => ['admin', 'direktur'],
                    'permissions' => ['view_user'],
                ],
                [
                    'id' => 'role_management',
                    'label' => 'Manajemen Role',
                    'route' => 'roles.index',
                    'icon' => 'security',
                    'roles' => ['admin'],
                    'permissions' => [],
                ],
                [
                    'id' => 'permission_management',
                    'label' => 'Manajemen Permission',
                    'route' => 'permissions.index',
                    'icon' => 'admin_panel_settings',
                    'roles' => ['admin'],
                    'permissions' => [],
                ],
                [
                    'id' => 'department_management',
                    'label' => 'Departemen',
                    'route' => 'departments.index',
                    'icon' => 'apartment',
                    'roles' => ['admin', 'direktur', 'manager'],
                    'permissions' => ['view_department'],
                ],
                [
                    'id' => 'location_management',
                    'label' => 'Lokasi',
                    'route' => 'locations.index',
                    'icon' => 'location_on',
                    'roles' => ['admin', 'direktur', 'manager'],
                    'permissions' => ['view_location'],
                ],
                [
                    'id' => 'category_management',
                    'label' => 'Kategori',
                    'route' => 'categories.index',
                    'icon' => 'category',
                    'roles' => ['admin', 'direktur', 'manager'],
                    'permissions' => ['view_category'],
                ],
            ],
        ],
    ],

    /**
     * Feature-level permissions mapping
     * Map features to permissions that should be checked
     */
    'features' => [
        'asset.create' => ['create_asset'],
        'asset.edit' => ['edit_asset'],
        'asset.delete' => ['delete_asset'],
        'asset.import' => ['create_asset'],
        'asset.assign' => ['edit_asset'],
        'asset.transfer' => ['edit_asset'],
        
        'user.create' => ['create_user'],
        'user.edit' => ['edit_user'],
        'user.delete' => ['delete_user'],
        
        'maintenance.create' => [],
        'maintenance.edit' => [],
        'maintenance.complete' => [],
        'maintenance.assign' => ['admin', 'direktur', 'manager'],
        
        'department.create' => ['create_department'],
        'department.edit' => ['edit_department'],
        'department.delete' => ['delete_department'],
        
        'location.create' => ['create_location'],
        'location.edit' => ['edit_location'],
        'location.delete' => ['delete_location'],
        
        'category.create' => ['create_category'],
        'category.edit' => ['edit_category'],
        'category.delete' => ['delete_category'],

        'role.create' => [],
        'role.edit' => [],
        'role.delete' => [],
        'role.manage_permissions' => [],
    ],
];
