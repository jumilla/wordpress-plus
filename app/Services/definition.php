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
        'upgrade.php' => [
        ],
        'admin.php' => [
        ],
        'admin-ajax.php' => [
        ],

        //--- Themes ---//

        'themes.php' => [
            /* 4.2.2: wp-admin/themes.php(100) */
            'theme', 'search',
        ],
        'theme-install.php' => [
            /* 4.2.2: wp-admin/theme-install.php(13) */
            'tab',
        ],
        'customize.php' => [
            /* 4.2.2: wp-admin/customize.php(19) */
            'url', 'return',
        ],
        'widgets.php' => [
        ],
        'nav-menus.php' => [
            'current_user',
        ],
        'theme-editor.php' => [
            /* 4.2.2: wp-admin/theme-editor.php(46) */
            'action', 'error', 'file', 'theme',
        ],

        //--- Plugins ---//

        'plugins.php' => [
            'plugins', 'status', 'page', 'user_ID',
        ],
        'plugin-install.php' => [
            'tabs', 'tab', 'paged', 'wp_list_table',
            /* 4.2.2: wp-admin/plugin-install.php(55) */
            'body_id',
        ],
        'plugin-editor.php' => [
            /* 4.2.2: wp-admin/plugin-editor.php(23) */
            'action', 'error', 'file', 'plugin',
        ],

        //--- Users ---//

        'users.php' => [
        ],
        'user-new.php' => [
            /* 4.2.2: wp-admin/user-new.php(131) */
            'wpdb',
        ],
        'user-edit.php' => [
            /* 4.2.2: wp-admin/user-edit.php(12) */
            'action', 'user_id', 'wp_http_referer',
            /* 4.2.2: wp-admin/user-edit.php(153) */
            'wpdb',
        ],
        'profile.php' => [
        ],

        //--- Posts ---//

        'edit.php' => [
//			'typenow',
        ],
        'revision.php' => [
            /* 4.2.2: wp-admin/revision.php(23) */
            'revision', 'action', 'from', 'to',
        ],
        'post-new.php' => [
            /* 4.2.2: wp-admin/post-new.php(48) */
            'title',
            /* 4.2.2: ??? */
            'is_IE',
        ],
        'post.php' => [
            /* 4.2.2: wp-admin/post.php(17) */
            'action',
            /* 4.2.2: ??? */
            'is_IE',
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
            'action', 'cat_id', 'link_id',
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
            /* 4.2.2: wp-admin/options.php(25) */
            'action', 'option_page',

            /* 4.2.2: wp-admin/options.php(231) */
            'wpdb',
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
