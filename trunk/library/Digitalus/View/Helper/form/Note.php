<?php
class Digitalus_View_Helper_Form_Note
{

    /**
     * pretty simple stuff.  just wraps the note.
     */
    public function Note($note)
    {
        $xhtml = '<p class="note">' . $note . '</p>';
        return $xhtml;
    }
}