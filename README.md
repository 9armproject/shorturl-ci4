# Short URL CI4

View Demo https://adoring-wescoff.128-199-152-0.plesk.page

## Installation & Setup

* Clone the repo `git clone`
* install composer and run `composer install`
* Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.
* run `php spark migrate`

### Built With

* [Codeigniter 4](https://codeigniter.com)
* [Bootstrap](https://getbootstrap.com)
* [JQuery](https://jquery.com)


## Server Requirements

PHP version 7.3 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)
