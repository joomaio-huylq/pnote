<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace App\pnote\note_attachment\models;

use App\pnote\note_upload\models\NoteFileModel;

class NoteAttachmentModel extends NoteFileModel
{ 
    public function attachmentOfNote($id)
    {
        if (!$id)
        {
            $where = ['status' => -1, 'created_by' => $this->user->get('id'), 'type' => 'file'];
        }
        else
        {
            $where = ['note_ids LIKE "%('. $id .')%"' ];
        }

        $notes = $this->FileEntity->list(0, 0, $where);
        
        return $notes;
    }
}
