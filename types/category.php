<?php

$entries = $Site->getChildren([
    'type' => 'quiqqer/faq:types/entry'
]);

$faqTemplate = 'default';
$offset      = false;
$FAQControl  = null;

switch ($Site->getAttribute('quiqqer.faq.settings.template')) {
    case 'accordion':
        $faqTemplate = 'accordion';

        $FAQControl = new \QUI\FAQ\Controls\Accordion([
            'max'        => 50,
            'stayOpen'   => $Site->getAttribute('quiqqer.faq.settings.accordion.stayOpen'),
            'parentSite' => $Site
        ]);

        break;
    case 'default':
    default:
        $offset      = intval($Site->getAttribute('quiqqer.faq.settings.offset'));
        $faqTemplate = 'default';
        break;
}

$Engine->assign([
    'entries'     => $entries,
    'faqTemplate' => $faqTemplate,
    'offset'      => $offset,
    'FAQControl'  => $FAQControl
]);
