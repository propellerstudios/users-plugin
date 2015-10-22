<?php

return [
    'Users' => [
        /**
         * When 'true', this key will make the username an email address.
         */
        'use_email_as_username' => false,
        
        /**
         * When 'true', this key will automatically assign the first registered
         * user as an admin.
         */
        'first_user_is_admin' => true,
        
        /**
         * When 'true', this key will add to the main scope of the routes,
         * removing all of the plugin dashed route nonesense.
         */
        'use_main_route_scope' => true,
        
        /**
         * When 'true', this key will assimilate 'dashboard' requests to the
         * 'index' action of the Users and Admins controllers.
         */
        'use_dashboard_route' => true,
        
        /**
         * When 'true', this key will provide the ability for anybody to register
         * as a user into the application.
         */
        'open_registration' => true,
        
        /**
         * When 'true', this key will enable email confirmation of newly
         * registered users.
         */
        'send_email_verification' => false,
        
        /**
         * This is a list of white-listed actions for the 'Users' controller.
         */
        'white_list' => [
            'login',
            'verify',           // used to verify a user based on a unique ID
            'reset',            // used to change the password
            'requestPassword',  // used to request a new pass word
            'vertifyNew'        // used to verify new users
        ],
    ]
];