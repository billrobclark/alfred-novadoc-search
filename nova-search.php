<?php

use Alfred\Workflows\Workflow;
use AlgoliaSearch\Client as Algolia;

require __DIR__ . '/vendor/autoload.php';

$query = $argv[1];

$workflow = new Workflow;
$algoliaClient = new Algolia('6EVZSN4WQD', 'bf1eceffbd547a1443da21aab145c2d5');

$index = $algoliaClient->initIndex('nova-docs-1.0');

$search = $index->search($query);
$results = $search['hits'];

foreach ($results as $hit) {
    $title = strip_tags(html_entity_decode($hit['_highlightResult']['title']['value'], ENT_QUOTES, 'UTF-8'));

    $workflow->result()
        ->uid($hit['objectID'])
        ->title($title)
        ->autocomplete($title)
        ->subtitle($hit['subtext'])
        ->arg($hit['url'])
        ->quicklookurl($hit['url'])
        ->valid(true);
}

echo $workflow->output();
