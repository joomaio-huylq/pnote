
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row pt-0 justify-content-center mx-auto">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-sm-12">
                <input id="input_title" type="hidden" name="title" required>
                <div class="mermaid-code">
                    <?php $this->ui->field('data'); ?>
                </div>
            </div>
            <div class="col-lg-7 col-sm-12">
                <div class="mermaid-container position-relative">
                    <div class="position-absolute">
                        <div class="alert d-none w-100 alert-danger alert-mermaid" role="alert">
                        </div>
                    </div>
                    <?php $this->ui->field('mermaid'); ?>
                </div>
            </div>
        </div>
    </form>
</div>
<?php echo $this->render('backend.history.javascript'); ?>

