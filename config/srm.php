<?php
/**
 * User: mlawson
 * Date: 11/18/18
 * Time: 3:51 PM
 */

return [
	'env' => env('APP_ENV', 'local'),
	'version' => env('SRM_VERSION', '1.0'),
	'contact_email' => env('CONTACT_EMAIL', 'support@neubus.com'),
    // key matches a value in the User 'role' enum. If true, MFA is enforced on user creation, else it's optional
    'requires_mfa' => [
        'it' => true,
        'admin' => true,
        'client' => true,
        'neubus' => false
    ],
    'max_upload_size' => env('MAX_UPLOAD_SIZE'),
    'log_storage' => \NeubusSrm\Repositories\SrmLogRepository::class,
    'max_mfa_refresh_attempt' => env('MAX_MFA_REFRESH_ATTEMPT', 3),
    'neu_timeout_period' => env('NEU_TIMEOUT_PERIOD', 3600),
    'file_storage' => [
        'driver' => env('NEU_STORAGE_DRIVER', 'local'),
        'local' => [
            'binder_class' => \NeubusSrm\Lib\Builders\FileSystem\PathBinders\Impl\NeuSrmPathBinder::class,
            'dir' => env('BASE_STORAGE_DIR', '/var/www/html/files/')
        ]

    ],
	'index_type_names' => [
		'Text', 'Date', 'Date Range', 'Email', 'Numeric', 'Multi Select', 'Text Area', 'Single Select'
	],
	'database' => [
		'name' => env('DB_DATABASE', 'neubus_srm'),
		'connection' => env('DB_CONNECTION')
	],
	'url' => env('APP_URL', 'http://localhost'),
	'menu' => [
		// for the menu it's important that this structure be followed in order for hrefs to be shared correctly
		// prefixed by admin in the uri and name
		'admin' => [
			'indexing' => [
				// prefixed by indexing in the uri and name
				'icon' => 'fas fa-list',
				'title' => 'Indexing Configuration',
				'href' => 'indexing'
			],
            'log' => [
                'icon' => 'fas fa-scroll',
                'title' => 'Logs',
                'href' => 'log',
            ],
		],
        'request' => [
            'request' => [
                'icon' => 'fas fa-shopping-basket',
                'title' => 'Requests',
                'href' => '',
            ],
            'dataentry' => [
                'icon' => 'fas fa-database',
                'title' => 'Data Entry',
                'href' => 'dataentry'
            ],
	        'todo' => [
		        'icon' => 'fas fa-clipboard-list',
		        'title' => 'Request To Do',
		        'href' => 'todo'
	        ],
            'completed' => [
                'icon' => 'fas fa-award',
                'title' => 'Completed Requests',
                'href' => 'todo/completed',
            ],
            'review' => [
                'icon' => 'fas fa-check',
                'title' => 'Request Review',
                'href' => 'review'
            ]
        ],
        'user' => [
            'user' => [
                'icon' => 'fas fa-users',
                'title' => 'Manage Users',
                'href' => 'users'
            ]
        ],
        'project' => [
            'project' => [
                'icon' => 'fas fa-project-diagram',
                'title' => 'Project Management',
                'href' => 'projects'
            ]
        ],
        'company' => [
            'company' => [
                'icon' => 'fas fa-building',
                'title' => 'Company Configuration',
                'href' => 'companies'
            ]
        ],
		'settings' => [
			'settings' => [
				'icon' => 'fas fa-cogs',
				'title' => 'Global Settings',
				'href' => 'settings'
			]
		],
        'box' => [
            'location' => [
                'icon' => 'fas fa-map-marker-alt',
                'title' => 'Update Location',
                'href' => 'updatelocation',
            ],
        ],
	],
    'menuRoots' => [
        'admin' => 'admin',
        'request' => 'requests',
        'user' => 'admin',
        'project' => 'it',
        'company' => 'it',
	    'settings' => 'it',
        'box' => 'box'
    ],
    'dwt_product_key' => env('DWT_PRODUCT_KEY'),
    'upload_packet_size' => env('UPLOAD_PACKET_SIZE', 10)
];
