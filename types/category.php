<?php

$entries = $Site->getChildren(array(
    'type' => 'quiqqer/faq:types/entry'
));

$Engine->assign(array(
    'entries' => $entries
));
