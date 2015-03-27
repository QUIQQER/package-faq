<?php

$categories = $Site->getChildren(array(
    'type' => 'quiqqer/faq:types/category'
));

$Engine->assign(array(
    'categories' => $categories
));
