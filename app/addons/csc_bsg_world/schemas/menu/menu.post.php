<?php

$schema['central']['website']['items']['send_messages'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href' => 'bsg.send',
    'position' => 900
);

$schema['central']['website']['items']['messages_report'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href' => 'bsg.log',
    'position' => 901
);

return $schema;
