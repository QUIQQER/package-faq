<?php

$entries = $Site->getChildren([
    'type' => 'quiqqer/faq:types/entry'
]);

$faqTemplate = 'default';
$offset = false;
$FAQControl = null;
$useFaqStructuredData = $Site->getAttribute('quiqqer.faq.settings.useFaqStructuredData');
$faqStructuredData = ''; // html string

switch ($Site->getAttribute('quiqqer.faq.settings.template')) {
    case 'accordion':
        $faqTemplate = 'accordion';

        $FAQControl = new \QUI\FAQ\Controls\Accordion([
            'max' => 50,
            'stayOpen' => $Site->getAttribute('quiqqer.faq.settings.accordion.stayOpen'),
            'parentSite' => $Site,
            'useFaqStructuredData' => $useFaqStructuredData
        ]);

        break;
    case 'default':
    default:
        $offset = intval($Site->getAttribute('quiqqer.faq.settings.offset'));
        $faqTemplate = 'default';
        if ($useFaqStructuredData) {
            $jsonSchemaEntries = [];

            foreach ($entries as $FaqSite) {
                $short = $FaqSite->getAttribute('short');
                $content = $FaqSite->getAttribute('content');

                if ($short) {
                    $short = '<div class="quiqqer-faqAccordion-item-content-pageShort text-muted">' . $short . '</div>';
                }

                if ($content) {
                    $content = '<div class="quiqqer-faqAccordion-item-content-pageContent">' . $content . '</div>';
                }

                $entryContent = $short . $content;

                $entry = [
                    'entryTitle' => $FaqSite->getAttribute('title'),
                    'entryContent' => $entryContent,
                ];

                $jsonSchemaEntries[] = $entry;
            }

            $FAQControl = new QUI\Bricks\Controls\Accordion([
                'entries' => $jsonSchemaEntries
            ]);

            $faqStructuredData = $FAQControl->createJSONLDFAQSchemaCode();
        }
        break;
}

$Engine->assign([
    'entries' => $entries,
    'faqTemplate' => $faqTemplate,
    'offset' => $offset,
    'FAQControl' => $FAQControl,
    'faqStructuredData' => $faqStructuredData
]);
