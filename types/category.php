<?php

$entries = $Site->getChildren([
    'type' => 'quiqqer/faq:types/entry'
]);

$faqTemplate          = 'default';
$offset               = false;
$FAQControl           = null;
$useFaqStructuredData = $Site->getAttribute('quiqqer.faq.settings.useFaqStructuredData');
$faqStructuredData    = ''; // html string

switch ($Site->getAttribute('quiqqer.faq.settings.template')) {
    case 'accordion':
        $faqTemplate = 'accordion';

        $FAQControl = new \QUI\FAQ\Controls\Accordion([
            'max'                  => 50,
            'stayOpen'             => $Site->getAttribute('quiqqer.faq.settings.accordion.stayOpen'),
            'parentSite'           => $Site,
            'useFaqStructuredData' => $useFaqStructuredData
        ]);

        break;
    case 'default':
    default:
        $offset      = intval($Site->getAttribute('quiqqer.faq.settings.offset'));
        $faqTemplate = 'default';
        if ($useFaqStructuredData) {
            $FAQControl        = new QUI\Bricks\Controls\Accordion();
            $faqStructuredData = $FAQControl->createJSONLDFAQSchemaCode();
        }
        break;
}

$Engine->assign([
    'entries'           => $entries,
    'faqTemplate'       => $faqTemplate,
    'offset'            => $offset,
    'FAQControl'        => $FAQControl,
    'faqStructuredData' => $faqStructuredData
]);
