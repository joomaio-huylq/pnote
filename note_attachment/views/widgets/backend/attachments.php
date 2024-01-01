<div>
    <label class="form-label">Attachments</label>
    <div class="d-flex">
        <input type="file" name="file[]" id="file" class="form-control" multiple>
        <button class="btn btn-outline-success ms-2 btn-upload">
            Upload
        </button>
    </div>
    <div class="d-flex flex-wrap pt-4" id="list-attachments">
        <?php foreach($this->attachments as $item) : ?>
        <div class="card border shadow-none d-flex flex-column me-2 justify-content-center" style="width: auto;">
            <a href="<?php echo $this->url($item['path']) ?>" target="_blank" class="h-100 my-2 px-2 mx-auto" style="">
                <img style="height: 120px; max-width: 100%;" src="<?php echo $this->url($item['image']) ?>">
            </a>
            <div class="card-body d-flex">
                <p class="card-text fw-bold m-0 me-2"><?php echo $item['title']; ?></p>
                <a data-href="<?php echo $this->url($item['path']); ?>" class="ms-1 me-3 copy_attachment_item fs-4"><i class="fa-regular fa-copy"></i></a>
                <a data-id="<?php echo $item['id']; ?>" class="ms-auto remove_attachment_item fs-4"><i class="fa-solid fa-trash"></i></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<input type="hidden" id="item_id" value="<?php echo $this->id; ?>">
<?php echo $this->renderWidget('note_attachment::backend.javascript'); ?>