<?php
    $this->theme->add($this->url . 'assets/handsontable/css/handsontable.full.min.css', '', 'handsontable-css');
    $this->theme->add($this->url . 'assets/handsontable/js/handsontable.full.min.js', '', 'bootstrap-handsontable');
    echo $this->renderWidget('core::notification'); ?>
<style>
    .col-header-input:focus {
        border-radius: 0px !important;
        border: 2px solid #3b7ddd !important;
        outline: none;
    }
</style>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-12">
                <input id="input_title" type="hidden" name="title">
                <input id="_method" type="hidden" name="_method" value="<?php echo $this->id ? 'PUT' : 'POST' ?>">
                <input id="table_data" type="hidden" name="table_data" value='<?php echo (array_key_exists('products', $this->data) ? json_encode($this->data['products']) : ''); ?>'>
                <div id="note-table"></div>
                <input id="save_close" type="hidden" name="save_close">
            </div>
            <?php $this->ui->field('structure'); ?>
            <div class="col-lg-4 col-sm-12 hide-modal">
                <div>
                    <?php $this->ui->field('notice'); ?>
                </div>
                <div class="mt-3 widget-tag">
                    <?php echo $this->renderWidget('tag::backend.tags'); ?>
                </div>
                <div class="mt-3 widget-assignee">
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
    </form>
</div>
<div class="modal fade" id="popupFormCol" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="popupFormColLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fw-bold" id="popupFormColLabel">Edit Colum</h4>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
                <div class="container-fluid list-note-type py-4">
                    <div class="row justify-content-center">
                        <div class="col-12 px-5">
                            <input type="hidden" id="index_product">
                            <form action="" id="new-col-form" method="post">
                                <div class="">
                                    <div class="text-nowrap">
                                        <input name="name_product" required type="text" id="name_product" placeholder="Title" class="form-control mb-2"/>
                                        <input name="link_product" type="text" id="link_product" placeholder="Link" class="form-control"/>
                                    </div>
                                    <div class="text-end mt-2">
                                        <button type="submit" class="btn btn-primary">Insert</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="popupDesFeature" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="popupDesFeatureLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fw-bold" id="popupDesFeatureLabel"></h4>
                <div>
                    <button type="button" id="update_feature_des" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            <div class="modal-body">
                <input type="hidden" id="index_row">
                <input type="hidden" id="index_col">
                <div class="">
                    <?php $this->ui->field('data'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->render('backend.form.javascript'); ?>