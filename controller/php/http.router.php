<?php

EnRouter::post($data, '=ajax(data=[returnValue|class])', function($data) {

    $class = $data['class'];

    $class::index($data);
});

EnRouter::request($data, '=thank-you', function($data) {

    Page::thankYou($data);
});

EnRouter::request($data, '/', function($data) {

    Page::index($data);
});