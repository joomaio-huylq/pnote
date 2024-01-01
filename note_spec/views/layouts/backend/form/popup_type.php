<div class="modal fade" id="popupNoteType" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="popupNoteTypeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fw-bold" id="popupNoteTypeLabel">New Note</h4>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
                <div class="container-fluid list-note-type py-4">
                    <div class="row justify-content-center">
                        <div class="col-6 px-5">
                            <div class="d-flex">
                                <select name="note_type" id="note_type" class="form-select">
                                    <option value="">Select Type</option>
                                    <?php if ($this->note_types) : ?>
                                        <?php foreach ($this->note_types as $key => $type) :
                                            if ($key == 'spec' || $key == 'file') {
                                                continue;
                                            }
                                        ?>
                                            <option value="<?php echo $key; ?>"><?php echo $type['title']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="text-nowrap ms-2">
                                    <button class="select-note-type btn btn-primary" 
                                    >New Note</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 px-5 d-flex select-full">
                            <select name="note_select_id[]" id="note_select_id" class="d-none"></select>
                            <div class="text-nowrap ms-2">
                                <button class="select-new-note btn btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>