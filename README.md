Twitter Lists Exporter
======================

A command-line tool to export Twitter lists to JSON or HTML.

## Installation

```
git clone https://github.com/viniciuspinto/twitter-lists-exporter.git
cd twitter-lists-exporter 
composer install
mv config.php.default config.php
```

## Configuration

Add your Twitter developer credentials to the `config.php` file. You can get those on https://apps.twitter.com/.

You can also change the output directory and format on that config file. Currently `json` and `html` (very basic) are supported.

## Usage

```
php task_export.php twitter_username [ owned | subscribed ]
```

For example, to get all the lists that the user @rasmus either own or follow:

```
php task_export.php rasmus
```

To get all the lists that @BarackObama subscribes to:

```
php task_export.php BarackObama subscribed
```

To get all the lists owned by @twitter:

```
php task_export.php twitter owned
```

## License

Do whatever you want.
