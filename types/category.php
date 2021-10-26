<?php

$entries = $Site->getChildren(array(
    'type' => 'quiqqer/faq:types/entry'
));

$offset = intval($Site->getAttribute('quiqqer.faq.settings.offset'));

$Engine->assign(array(
    'entries' => $entries,
    'offset'     => $offset
));
