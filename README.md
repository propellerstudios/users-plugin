# Users plugin for CakePHP

To use this plugin simply require it with Composer.

    $ composer require propellerstudios/users-plugin

## Configuration

In order for this plugin to work properly, you must set the `bootstrap`
directive on the plugin load command in your applications `bootstrap` file.
The purpose of the plugin's bootstrap file is to load the configuration
properly. The plugin also sets and handles routes.

    Plugin::load('Propeller/Users', ['bootstrap' => true, 'routes' => true]);

There are six configuration directives that can be overwritten with this
plugin. These keys are accessible from the 'Users' group of configuration.

1.  `useEmailAsUsername` - Assures that the 'username' and 'email' fields are
    synonymous. By default this setting is set to `false`.
2.  `firstUserIsAdmin` - Assures that the first registered user is automatically
    determined as an `admin`. By default this setting is set to `true`.
3.  `useMainRouteScope` - Assures that you do not need to prefix any of the
    routes with `propeller/` to get to the actions of the Controllers. By
    default this setting is set to `true`.
4.  `openRegistration` - Assures that anybody in the world can register for an
    account. By default this setting is set to `true`.
5.  `sendEmailVerification` - Assures that newly registered users are verified
    via email. You must have email transport set up for this to work properly.
    By default this setting is set to `false`.
6.  `whiteList` - A list of actions in the `Users` controller that are
    accessible to users who are not logged in. By default all `index` and `view`
    actions are accessible. Note to append to this list, instead of overwriting
    it. The default white-listed actions are:
    * `login` - To log a user in
    * `verify` - Verifies a user by their `personal_key`
    * `reset` - Request to reset password