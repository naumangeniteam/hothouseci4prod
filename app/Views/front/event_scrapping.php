<style>
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        padding: 20px;
    }

    .event-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin: 10px;
        padding: 20px;
        width: 300px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .event-card img {
        max-width: 100%;
        border-radius: 10px 10px 0 0;
    }

    .event-card-details {
        padding: 10px;
    }

    .event-card-details h2 {
        font-size: 1.2em;
        margin: 10px 0;
    }

    .event-card-details p {
        margin: 5px 0;
        color: #555;
    }

    .event-card-details a {
        display: block;
        text-align: center;
        margin-top: 10px;
        padding: 10px;
        background-color: #fff;
        color: #000;
        text-decoration: none;
        border-radius: 5px;
    }

    .event-card-details a:hover {
        background-color: #fff;
    }
</style>
<?php

function scrapeEventbrite($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) {
        return "Failed to retrieve data.";
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $events = $xpath->query("//div[contains(@class, 'event-card__vertical')]");

    if ($events->length == 0) {
        return "No events found.";
    }

    $eventPosts = '<div class="container">';
    $seenEvents = array();

    foreach ($events as $event) {
        $eventIdNode = $xpath->query(".//a[contains(@class, 'event-card-link')]", $event);
        $eventId = $eventIdNode->item(0) ? $eventIdNode->item(0)->getAttribute('data-event-id') : '';

        if (empty($eventId) || isset($seenEvents[$eventId])) {
            continue;
        }

        $seenEvents[$eventId] = true;

        $titleNode = $xpath->query(".//h2[contains(@class, 'event-card__clamp-line--two')]", $event);
        $title = $titleNode->item(0) ? trim($titleNode->item(0)->textContent) : '';

        $timeNode = $xpath->query(".//p[contains(@class, 'event-card__clamp-line--one')][1]", $event);
        $time = $timeNode->item(0) ? trim($timeNode->item(0)->textContent) : '';

        $locationNode = $xpath->query(".//p[contains(@class, 'event-card__clamp-line--one')][2]", $event);
        $location = $locationNode->item(0) ? trim($locationNode->item(0)->textContent) : '';

        $priceNode = $xpath->query(".//p[contains(@class, 'Typography_body-md-bold__487rx')]", $event);
        $price = $priceNode->item(0) ? trim($priceNode->item(0)->textContent) : '';

        $imageNode = $xpath->query(".//img[contains(@class, 'event-card-image')]", $event);
        $image = $imageNode->item(0) ? $imageNode->item(0)->getAttribute('src') : '';

        $newUrl = getCurrentControllerPath('event_scrapping_detail/' . $eventId);

        $eventPosts .= "<div class='row'><div class='event-card'>";
        if ($image !== 'No Image') {
            $eventPosts .= "<a href='$newUrl' target='_blank'><img src='$image' alt='$title' class='event-card-image'></a>";
        }
        $eventPosts .= "<div class='event-card-details'>";
        $eventPosts .= "<h2><a href='$newUrl' target='_blank'>$title</a></h2>";
        $eventPosts .= "<p>$time</p>";
        $eventPosts .= "<p>$location</p>";
        $eventPosts .= "<p>$price</p>";
        $eventPosts .= "</div>";
        $eventPosts .= "</div></div>";
    }

    $eventPosts .= '</div>';
    return $eventPosts;
}

$url = 'https://www.eventbrite.com/o/mintons-playhouse-76715695933';
echo scrapeEventbrite($url);
?>