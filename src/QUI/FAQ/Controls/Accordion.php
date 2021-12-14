<?php

/**
 * This file contains QUI\FAQ\Controls\Accordion
 */

namespace QUI\FAQ\Controls;

use QUI;
use QUI\Projects\Site\Utils;

/**
 * Class Listing
 *
 * @author Michael Danielczok (www.pcsg.de)
 * @package QUI\FAQ\Controls\Accordion
 */
class Accordion extends QUI\Control
{
    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        // default options
        $this->setAttributes([
            'class'          => 'quiqqer-faqAccordion',
            'order'          => 'order_field',
            'stayOpen'       => true, // if true make accordion items stay open when another item is opened
            'openFirst'      => true, // the first entry is initially opened
            'listMaxWidth'   => 0, // positive numbers only, 0 disabled this option.
            'max'            => 10, // max entries
            'parentSite'     => null,
            'siteType'       => 'quiqqer/faq:types/entry',
            'showMoreButton' => false,
            'moreSite'       => ''
        ]);

        parent::__construct($attributes);

        $this->addCSSFile(
            dirname(__FILE__).'/Accordion.css'
        );

        $this->setAttribute('cacheable', 0);
    }

    /**
     * Return the inner body of the element
     * Can be overwritten
     *
     * @return String
     */
    public function getBody()
    {
        $FAQParentSite = null;
        $Engine        = QUI::getTemplateManager()->getEngine();

        if ($this->getAttribute('parentSite')) {
            try {
                if ($this->getAttribute('parentSite') instanceof \QUI\Projects\Site) {
                    $FAQParentSite = $this->getAttribute('parentSite');
                } else {
                    $FAQParentSite = \QUI\Projects\Site\Utils::getSiteByLink($this->getAttribute('parentSite'));
                }
            } catch (QUI\Exception $Exception) {
                QUI\System\Log::addInfo($Exception->getMessage());

                return '';
            }
        }

        $faqSites = $FAQParentSite->getChildren([
            'where' => [
                'active' => 1,
                'type'   => $this->getAttribute('siteType'),
            ],
            'limit' => $this->getAttribute('max'),
            'order' => $this->getAttribute('order')
        ]);

        // show "more faq" link
        $showMoreButton = $this->getAttribute('showMoreButton');
        $MoreSite       = $FAQParentSite;

        if ($showMoreButton || $this->getAttribute('moreSite')) {
            if ($this->getAttribute('moreSite')) {
                try {
                    $MoreSite = \QUI\Projects\Site\Utils::getSiteByLink($this->getAttribute('moreSite'));
                    $showMoreButton = true;
                } catch (QUI\Exception $Exception) {
                    QUI\System\Log::addInfo($Exception->getMessage());
                    $MoreSite = null;
                }
            } else {
                $countFaqEntries = $FAQParentSite->getChildren([
                    'where' => [
                        'active' => 1,
                        'type'   => $this->getAttribute('siteType'),
                    ],
                    'count' => 1
                ]);

                if ($countFaqEntries <= $this->getAttribute('max')) {
                    $showMoreButton = false;
                }
            }
        }

        $entries = [];

        foreach ($faqSites as $FaqSite) {
            $short   = $FaqSite->getAttribute('short');
            $content = $FaqSite->getAttribute('content');

            if ($short) {
                $short = '<div class="quiqqer-faqAccordion-item-content-pageShort text-muted">'.$short.'</div>';
            }

            if ($content) {
                $content = '<div class="quiqqer-faqAccordion-item-content-pageContent">'.$content.'</div>';
            }

            $entryContent = $short.$content;

            $entry = [
                'entryTitle'   => $FaqSite->getAttribute('title'),
                'entryContent' => $entryContent,
            ];

            $entries[] = $entry;
        }

        $Accordion = new QUI\Bricks\Controls\Accordion([
            'stayOpen'     => \boolval($this->getAttribute('stayOpen')),
            'openFirst'    => $this->getAttribute('openFirst'),
            'listMaxWidth' => $this->getAttribute('listMaxWidth'),
            'entries'      => $entries
        ]);

        $this->addCSSFiles($Accordion->getCSSFiles());

        $Engine->assign([
            'this'           => $this,
            'Accordion'      => $Accordion,
            'showMoreButton' => $showMoreButton,
            'MoreSite'       => $MoreSite
        ]);

        return $Engine->fetch(dirname(__FILE__).'/Accordion.html');
    }
}
