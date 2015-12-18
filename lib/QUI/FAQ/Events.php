<?php

/**
 * This file contains \QUI\FAQ\Events
 */

namespace QUI\FAQ;

/**
 * FAQ Events
 *
 * @author www.pcsg.de (Henning Leutz)
 */

class Events
{
    /**
     * event on child create
     *
     * @param integer $newId
     * @param \QUI\Projects\Site\Edit $Parent
     */
    static function onChildCreate($newId, $Parent)
    {
        $type = $Parent->getAttribute( 'type' );

        if ( $type != 'quiqqer/faq:types/list' && $type != 'quiqqer/faq:types/category' ) {
            return;
        }

        $Project = $Parent->getProject();
        $Site    = new \QUI\Projects\Site\Edit( $Project, $newId );

        if ( $type == 'quiqqer/faq:types/list' ) {
            $Site->setAttribute( 'type', 'quiqqer/faq:types/category' );
        }

        if ( $type == 'quiqqer/faq:types/category' ) {
            $Site->setAttribute( 'type', 'quiqqer/faq:types/entry' );
        }

        $Site->save();
    }
}
