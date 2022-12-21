# ARD Audiothek rss feed generator

## Dependencies
 - php-curl

## Usage

Simply pass the id of the show with the show parameter

Show url: https://www.ardaudiothek.de/sendung/kalk-und-welk/10777871/

Id: 10777871

Feed url: https://example.com/ardaudiothek-rss.php?show=10777871

If you only want to receive the n newest episodes, pass the first parameter too:

Feed url with 10 newest episodes: https://example.com/ardaudiothek-rss.php?show=10777871&first=10