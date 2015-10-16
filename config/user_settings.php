<?php

return [
    'Users' => [
        /**
         * When 'true', this key will make the username an email address.
         */
        'useEmailAsUsername' => false,
        
        /**
         * When 'true', this key will automatically assign the first registered
         * user as an admin.
         */
        'firstUserIsAdmin' => true,
        
        /**
         * When 'true', this key will add to the main scope of the routes,
         * removing all of the plugin dashed route nonesense.
         */
        'useMainRouteScope' => true,
        
        /**
         * When 'true', this key will assimilate 'dashboard' requests to the
         * 'index' action of the Users and Admins controllers.
         */
        'useDashboardRoute' => true,
        
        /**
         * When 'true', this key will provide the ability for anybody to register
         * as a user into the application.
         */
        'openRegistration' => true,
        
        /**
         * When 'true', this key will enable email confirmation of newly
         * registered users.
         */
        'sendEmailVerification' => false,
        
        /**
         * This is a list of white-listed actions for the 'Users' controller.
         */
        'whiteList' => [
            'login',
            'verify',
            'reset'
        ]
    ]
];