Twitter Lists Exporter
======================

A command-line tool to export Twitter lists to JSON or HTML.

## Installation

```
git clone https://github.com/viniciuspinto/twitter-lists-exporter.git
cd twitter-lists-exporter 
composer install
cp config.php.default config.php
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

## JSON structure

```
[
  {
    "id_str":"1",
    "name":"Companies",
    "owner":{
      "id_str":"99",
      "screen_name":"alice",
      "name":"Alice"
    },
    "members":[
      {
        "id_str":"72",
        "screen_name":"twitter",
        "name":"Twitter"
      },
      {
        "id_str":"23",
        "screen_name":"apple",
        "name":"Apple"
      },
      ...
    ]
  }
]
```

## License

The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
