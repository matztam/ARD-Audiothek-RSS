<?php
/*
Dependencies:
    php-curl

Usage:
    Simply pass the id of the show with the show parameter

    Show url: https://www.ardaudiothek.de/sendung/kalk-und-welk/10777871/
    Id: 10777871

    Feed url: https://example.com/ardaudiothek-rss.php?show=10777871
*/

header('Content-Type: text/xml; charset=utf-8');

$showId = $_GET['show'];

if(!is_numeric($showId)){
    exit;
}
$show = getShowJsonGraphql($showId);


print('<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">');
print('<channel>');

printf('<title>%s</title>', escapeString($show->title));
printf('<link>https://www.ardaudiothek.de/sendung/%s</link>', $show->path);


print('<image>');
printf('<url>%s</url>', escapeString(str_replace("{width}", "448", $show->image->url1X1)));
printf('<title>%s</title>', escapeString($show->title));
printf('<link>https://www.ardaudiothek.de/sendung/%s</link>', $show->path);
print('</image>');

printf('<description>%s</description>', escapeString($show->synopsis));
printf('<atom:link href="%s" rel="self" type="application/rss+xml"/>', "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");


foreach ($show->items->nodes as $item) {
    $length = getFileLength($item->audios[0]->downloadUrl);

    print('<item>');
    printf('<title>%s</title>', escapeString($item->title));
    printf('<description>%s</description>', escapeString($item->synopsis));
    printf('<guid>%s</guid>', escapeString($item->sharingUrl));
    printf('<link>%s</link>', escapeString($item->audios[0]->downloadUrl));
    printf('<enclosure url="%s" length="%d" type="audio/mpeg"/>', escapeString($item->audios[0]->downloadUrl), $length);
    printf('<media:content url="%s" medium="audio" duration="%d" type="audio/mpeg"/>', escapeString($item->audios[0]->downloadUrl), $item->duration);
    printf('<pubDate>%s</pubDate>', (new DateTime($item->publicationStartDateAndTime))->format(DATE_RSS));
    printf('<itunes:duration>%d</itunes:duration>', $item->duration);
    print('</item>');
}


print('</channel>');
print('</rss>');


function getShowJson($showId) {
    $url = sprintf('https://api.ardaudiothek.de/programsets/%d', $showId);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    $output = curl_exec($ch);

    $obj = json_decode($output);

    return $obj->data->programSet;
}

function getShowJsonGraphql($showId){
	$url = 'https://api.ardaudiothek.de/graphql';
	
	$query = '{"query":"{
	  programSet(id:%d){
		title,
		synopsis,
		image{
		  url,
		  url1X1,
		},
		items(orderBy:PUBLISH_DATE_DESC, filter: { isPublished: { equalTo: true }}){ 
		  nodes{
			title,
			summary,
			synopsis,
			sharingUrl,
			publicationStartDateAndTime: publishDate,
			url,
			episodeNumber,
			duration,
			isPublished,
			audios{
			  url,
			  downloadUrl,
			  size,
			  mimeType,
			}
		  }
		}
	  }
	}"}';

	$query = sprintf($query, $showId);
	$query = preg_replace("/\n/m", '\n', $query);

	
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$output = curl_exec($ch);
	
	$obj = json_decode($output);
	
	return $obj->data->programSet;
}


function escapeString($string) {
    return htmlspecialchars($string, ENT_XML1, 'UTF-8');
}

function getFileLength($url) {
    $headers = get_headers($url, 1);
    $filesize = $headers['Content-Length'];

    return $filesize;
}
