<?php
namespace App\pnote\pnote\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminPNote extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'pnote.dashboard',
            ]
        ];
    }

    public function dashboard()
    {
        $this->session->set('link_back_note', '');
        $shortcuts = $this->ShortcutModel->getShortcut();
        $countMyNote = $this->PNoteModel->countMyNote();
        $countMyShared = $this->PNoteModel->countMyShared();
        $countMyFilter = $this->PNoteModel->countMyFilter();
        $countShare = $this->PNoteModel->countShare();

        return [
            'url' => $this->router->url(),
            'shortcuts' => $shortcuts,
            'link_form_filter' => $this->router->url('collection/edit/0'),
            'link_filter' => $this->router->url('collections'),
            'link_notes' => $this->router->url('notes'),
            'link_mynote' => $this->router->url('collection/my-notes'),
            'link_sharenote' => $this->router->url('collection/my-shares'),
            'countShare' => $countShare,
            'countMyNote' => $countMyNote,
            'countMyShared' => $countMyShared,
            'countMyFilter' => $countMyFilter,
            'link_shortcut_form' => $this->router->url('shortcut'),
            'link_shortcut_list' => $this->router->url('shortcuts'),
        ];
    }
}
