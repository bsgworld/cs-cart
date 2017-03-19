<?php

$schema['central']['website']['items']['send_messages'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href' => 'amocrm.send',
    'position' => 900
);

$schema['central']['website']['items']['messages_report'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href' => 'amocrm.log',
    'position' => 901
);

return $schema;
