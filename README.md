# README

## overview

This is a WordPress [must-use plugin](https://wordpress.org/documentation/article/must-use-plugins/) containing constant definitions and general configuration settings used across my development environments.

## installation

First, create the must-use plugin folder inside `wp-content`:

```shell
$ mkdir path/to/wordpress/wp-content/mu-plugins
```

### option 1: copy

To install the plugin, download the repository and copy `baizman-design-mu.php` to `path/to/wordpress/wp-content/mu-plugins`. Note that it must be manually updated due to the nature of must-use plugins.

### option 2: symlink

Download or clone the repository and create a symbolic link to `baizman-design-mu.php`:

```shell
$ ln -s path/to/repository/baizman-design-mu.php path/to/wordpress/wp-content/mu-plugins/baizman-design-mu.php
```

If you clone the repository, the plugin can be updated easily via `git pull`.

### option 3: composer

Require the package in `composer.json`:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:baizman-design/baizman-design-mu.git"
        }
    ],
    "require-dev": {
        "baizman-design/baizman-design-mu": "dev-production"
    },
    "scripts": {
        "post-package-install": [
          "$(composer config vendor-dir)/baizman-design/baizman-design-mu/bin/make-symlink.sh"
        ]
    }
}
```

Install the package:
```
$ composer install
```

The post-install script will automatically create the `wp-content/mu-plugins` folder, if needed, and a symbolic link to `baizman-design-mu.php`.

## configuration

Create a file named `.baizman-design-mu.ini` in the root directory of your WordPress instance.

### disable plugins

The plugin can forcibly disable other plugins. This is useful for plugins that should only be run in a production environment, such as backup, firewall, and caching plugins.

To disable a plugin, add the following section and key/value pairs to `.baizman-design-mu.ini`:

```
[disabled_plugins]
plugin[] = slug1
plugin[] = slug2
...
```

#### disable plugins configuration file (deprecated)

Add a slug, one per line, to a file named `.baizman-design-mu-disabled-plugins` in the root directory of your WordPress instance.

This is supported for historical reasons.

### auto-login

An "Autologin" link is added to the WordPress login screen right after "Lost your password?" To set the account to automatically log into, add the following section and key/value pair to `.baizman-design-mu.ini`:

```
[autologin]
email = user@domain.com
```

You may also visit https://domain.test/?auto=user@domain.com, where "user@domain.com" is an email address that corresponds to an account.
