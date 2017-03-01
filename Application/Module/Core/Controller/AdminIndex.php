<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Core\Model\Note;

class AdminIndexController extends Controller
{

    public function IndexAction()
    {
        $this->template()->setHeader(array(
            'admin_core.js' => 'Module_Core',
            'admin_core.css' => 'Module_Core',
        ));
        $oNoteModel = (new Note());
        $this->view->oNote = $oNote = $oNoteModel->getOne('system', 'note_type');
        $sAdminPath = $this->app->getConfig('system', 'admin_path');
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.dashboard'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sAdminPath => $this->language()->translate('core.dashboard'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
        //$aStats = (new )
        $this->view->aStats = $aResults = $this->helper->callback('getStatistic');
        $this->view->aSystemInformation = $this->app->getSystemInformation();
    }

    public function NoteAction()
    {
        $sNote = $this->request()->get('note_data');
        $oNoteModel = (new Note());
        $oNote = $oNoteModel->getOne('system', 'note_type');
        if ($oNote && $oNote->note_id)
        {
            $aUpdate = array(
                'note_description' => $sNote,
                'last_update' => time(),
            );
            $oNote->note_description = $sNote;
            $oNote->last_update = APP_TIME;
            $oNote->update();
        } else
        {
            $aInsert = array(
                'note_type' => 'system',
                'note_title' => 'core.note_title',
                'note_description' => $sNote,
                'last_update' => APP_TIME
            );
            $oNoteRow = $oNoteModel->getTable()->createRow($aInsert);
            $oNoteRow->save();
        }
        //echo $this->language()->get('core.done');
        system_display_result($this->language()->get('core.done'));
    }

}
