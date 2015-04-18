<?php

/**
 * FAQ Entry
 */

$Parent = $Site->getParent();
$parentType = $Parent->getAttribute('type');

if ($parentType == 'quiqqer/faq:types/list'
    || $parentType == 'quiqqer/faq:types/category'
) {
    QUI::getRewrite()->showErrorHeader(
        303,
        URL_DIR.$Parent->getUrlRewrited().'#faq'.$Site->getId()
    );

    exit;
}
