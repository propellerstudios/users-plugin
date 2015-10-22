# Users plugin for CakePHP

A CakePHP 3.x plugin for users. The original concept is boilerplate user
management using the default CakePHP Auth component.

To use this plugin simply require it with Composer.

    $ composer require propellerstudios/users-plugin

Once Composer has the necessary files, run a migration to add the `users` table
to your database.

    $ bin/cake migrations migrate -p Propeller/Users

## Configuration

In order for this plugin to work properly, you must set the `bootstrap`
directive on the plugin load command in your applications `bootstrap` file.
The purpose of the plugin's bootstrap file is to load the configuration
properly. The plugin also sets and handles routes.

    Plugin::load('Propeller/Users', ['bootstrap' => true, 'routes' => true]);

There are several keys for configuring the Users plugin.

* `use_email_as_username`
* `first_user_is_admin`
* `use_main_route_scope`
* `use_dashboard_route`
* `open_registration`
* `send_email_verification`
* `white_list`

All of these keys are boolean values save for `white_list` which is an array of
actions that are available to non-authorized visitors of the site. To overwrite
any of these, *after* your `Plugin::load()` command, just rewrite the keys that
you wish like so:

    Configure::write('Users.white_list', ['index', 'view']);
    Configure::write('Users.use_email_as_username', false);