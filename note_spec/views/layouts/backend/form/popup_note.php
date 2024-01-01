<div class="modal fade" id="popupNote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="popupNoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0">
                <h4 class="modal-title w-100 fw-bold d-flex" id="specNoteLabel">
                    <span class="index-text fs-2 me-1"></span>
                    <input name="title" type="text" id="title_note" required="" 
                        placeholder="New Title" 
                        value="" 
                        class="form-control px-1 border-0 border-bottom fs-2 py-0">
                </h4>
                <input type="hidden" id="index_note">
                <input type="hidden" id="form_edit">
                <div class="button-actions d-flex ms-3">
                    <button type="button" class="btn btn-primary" id="spec_save_note">Save</button>
                    <button type="button" class="btn ms-2 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="note_ajax_load" class="w-100" style="height: 60vh" src=""></iframe>
            </div>
        </div>
    </div>
</div>