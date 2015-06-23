<?php

return [
    'blog_admin_scripts' => [
        'index.php' => [
            'wp_db_version',
        ],
        'update-core.php' => [
        ],
        'update.php' => [
        ],
        'admin.php' => [
        ],
        'admin-ajax.php' => [
        ],

        //--- Themes ---//

        'themes.php' => [
        ],
        'theme-install.php' => [
        ],
        'customize.php' => [
            'url', 'return',
        ],
        'widgets.php' => [
        ],
        'nav-menus.php' => [
            'current_user',
        ],
        'theme-editor.php' => [
            'action', 'error', 'file', 'theme',
        ],

        //--- Plugins ---//

        'plugins.php' => [
            'plugins', 'status', 'page', 'user_ID',
        ],
        'plugin-install.php' => [
            'tabs', 'tab', 'paged', 'wp_list_table',
        ],
        'plugin-editor.php' => [
            'action', 'error', 'file', 'plugin',
        ],

        //--- Users ---//

        'users.php' => [
        ],
        'user-new.php' => [
        ],
        'user-edit.php' => [
        ],
        'profile.php' => [
        ],

        //--- Posts ---//

        'edit.php' => [
//			'typenow',
        ],
        'post-new.php' => [
            'is_IE', 'title',
        ],
        'post.php' => [
            'is_IE', 'action',
        ],
        'edit-tags.php' => [
            'title', 'taxonomy',
        ],
        'edit-comments.php' => [
            'title', 'post_id', 'comment', 'comment_status',
        ],
        'comment.php' => [
            'post_id', 'comment', 'comment_status',
        ],

        //--- Media ---//

        'upload.php' => [
        ],
        'async-upload.php' => [
        ],
        'media-new.php' => [
        ],

        //--- Tools ---//

        'tools.php' => [
        ],
        'press-this.php' => [
        ],
        'import.php' => [
        ],
        'export.php' => [
        ],
        'network.php' => [
            'wpdb',
        ],

        //--- Links ---//

        'link-manager.php' => [
        ],
        'link-add.php' => [
        ],
        'link.php' => [
            'action', 'cat_id', 'link_id',
        ],

        //--- Options ---//

        'options-general.php' => [
        ],
        'options-writing.php' => [
        ],
        'options-reading.php' => [
        ],
        'options-discussion.php' => [
            'user_email',
        ],
        'options-permalink.php' => [
            'wp_rewrite',
            'is_nginx',
        ],
        'options.php' => [
            'action', 'option_page',
        ],

        //--- About ---//

        'about.php' => [
        ],
        'credit.php' => [
        ],
        'freedom.php' => [
        ],

        //--- Sites ---//

        'my-sites.php' => [
            'wpdb',
            'current_site', 'current_blog', 'current_user',
        ],
    ],

    'site_admin_scripts' => [
        'setup.php' => [],
        'update-core.php' => [],
        'update.php' => [],
        'upgrade.php' => [],

        'index.php' => [],

        'admin.php' => [],

        'site-info.php' => [],
        'site-new.php' => [],
        'site-settings.php' => [],
        'site-themes.php' => [],
        'site-users.php' => [],
        'sites.php' => [],

        'theme-editor.php' => [],
        'theme-install.php' => [],
        'themes.php' => [],

        'plugin-editor.php' => [],
        'plugin-install.php' => [],
        'plugins.php' => [],

        'user-edit.php' => [],
        'user-new.php' => [],
        'users.php' => [],
        'profile.php' => [],

        'edit.php' => [],

        'settings.php' => [],

        'about.php' => [],
        'credits.php' => [],
        'freedoms.php' => [],
    ],
];
