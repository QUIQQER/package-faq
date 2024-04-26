<?php

/**
 * This file contains \QUI\FAQ\EventsHandler
 */

namespace QUI\FAQ;

use QUI\Exception;
use QUI\Interfaces\Projects\Site;
use QUI\Projects\Site\Edit;

/**
 * FAQ Events
 *
 * @author www.pcsg.de (Henning Leutz)
 */
class EventsHandler
{
    /**
     * event on child create
     *
     * @param integer $newId
     * @param Site $Parent
     * @throws Exception
     */
    public static function onChildCreate(int $newId, Site $Parent): void
    {
        $type = $Parent->getAttribute('type');

        if (
            $type != 'quiqqer/faq:types/list'
            && $type != 'quiqqer/faq:types/category'
        ) {
            return;
        }

        $Project = $Parent->getProject();
        $Site = new Edit($Project, $newId);

        if ($type == 'quiqqer/faq:types/list') {
            $Site->setAttribute('type', 'quiqqer/faq:types/category');
        }

        if ($type == 'quiqqer/faq:types/category') {
            $Site->setAttribute('type', 'quiqqer/faq:types/entry');
        }

        $Site->save();
    }
}
