
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row pt-0 justify-content-center mx-auto">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-sm-12">
            <div class="mermaid-container position-relative">
                <div class="position-absolute">
                    <div class="alert d-none w-100 alert-danger alert-mermaid" role="alert">
                    </div>
                </div>
                <?php $this->ui->field('mermaid'); ?>
            </div>
        </div>
    </div>
</div>
<?php echo $this->render('backend.preview.javascript'); ?>

