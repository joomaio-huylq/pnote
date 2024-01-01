<?php 
$this->theme->add($this->url . 'assets/treephp/css/style.css', '', 'treephp-css');
echo $this->renderWidget('core::notification'); 
?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <input id="input_title" type="hidden" class="d-none" name="title">
                <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                    <div id="tree_root" class="overflow-auto">
                        <table class="table">
                            <thead>
                                <tr id="item_0" data-level="0" class="item-tree active open" data-id="0">
                                    <th>Top Level</th>
                                    <th width="35px"></th>
                                    <th width="50px">
                                        <button data-id="0" data-position="last" type="button" class="btn btn-outline-success open-note-form" type="button" data-bs-toggle="modal" data-bs-target="#popupNoteType"><i class="fa-solid fa-plus"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($this->data && isset($this->data['list_tree'])) {
                                    foreach ($this->data['list_tree'] as $item) {
                                ?>
                                <tr data-text="<?php echo $item['title'];?>" id="item_<?php echo $item['note_id'] ?>" data-level="<?php echo $item['tree_level'] ?>" class="item-tree" data-id="<?php echo $item['note_id'] ?>" data-parent="<?php echo $item['parent_id'] ?>" data-position="<?php echo $item['tree_position'] ?>">
                                    <td ><?php echo str_repeat('&nbsp; &nbsp; &nbsp; &nbsp;', (int) $item['tree_level']-1). '<span class="title">|&mdash; ' .$item['title'] ?></span></td>
                                    <td> 
                                        <div class="d-flex justify-content-end">
                                            <a class="open-note-form me-3" data-note-id="<?php echo isset($item['note']['id']) ? $item['note']['id'] : 0; ?>" data-id="<?php echo $item['note_id'] ?>" type="button" data-bs-toggle="modal" data-bs-target="#popupNote"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a class="up-note me-2 d-none" data-id="<?php echo $item['note_id'] ?>"><i class="fa-solid fa-arrow-up"></i></a>
                                            <a class="down-note me-2 d-none" data-id="<?php echo $item['note_id'] ?>"><i class="fa-solid fa-arrow-down"></i></a>
                                            <a class="remove-note" data-note-id="<?php echo isset($item['note']['id']) ? $item['note']['id'] : 0 ?>" data-id="<?php echo $item['note_id'] ?>"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </td>
                                    <td width="50px">
                                        <button type="button" data-id="0" class="btn btn-outline-success open-note-form" type="button" data-bs-toggle="modal" data-bs-target="#popupNoteType"><i class="fa-solid fa-plus"></i></button>
                                    </td>
                                </tr>
                                <?php } 
                                }?>
                            </tbody>
                            <tfoot>
                                <tr class="text-start border-0">
                                   
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 border-start col-sm-12" >
                <h3 class="text-center">
                    Quick view
                    <div class="spinner-border d-none loading-document ms-2" role="status" style="width: 1.5rem; height: 1.5rem">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </h3>
                <iframe id="document" class="w-100 h-100" src="<?php echo $this->link_load_document; ?>"></iframe>
            </div>
            <div class="col-lg-3 border-start col-sm-12 hide-modal">
                <div>
                    <?php $this->ui->field('notice'); ?>
                </div>
                <div class="mt-3">
                    <?php echo $this->renderWidget('tag::backend.tags'); ?>
                </div>
                <div class="mt-3">
                    <?php echo $this->renderWidget('share_note::backend.share_note'); ?>
                </div>
                <?php if ($this->history) : ?>
                <div class="mt-3 widget-history">
                    <label for="label">History:</label>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($this->history as $item) : ?>
                            <li class="list-group-item">
                                <a href="<?php echo $this->link_history.'/'. $item['id'] ?>" class="openHistory" data-id="<?php echo $item['id']; ?>" data-modified_at="<?php echo $item['created_at']; ?>">Modified at <?php echo $item['created_at']; ?> by <?php echo $item['user']; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="mt-3">
                    <?php echo $this->renderWidget('note_attachment::backend.attachments'); ?>
                </div>
            </div>
        </div>
        <input class="form-control rounded-0 border border-1" type="hidden" name="_method" value="<?php echo $this->id ? 'PUT' : 'POST' ?>">
        <?php $this->ui->field('structure'); ?>
        <?php $this->ui->field('removes'); ?>
        <input type="hidden" name="save_close" id="save_close">
    </form>
</div>
<?php echo $this->render('backend.form.popup_type'); ?>
<?php echo $this->render('backend.form.popup_note'); ?>
<?php echo $this->render('backend.form.javascript'); ?>
