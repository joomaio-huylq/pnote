
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-sm-12">
            <div>
                <?php $this->ui->field('data'); ?>
            </div>
        </div>
    </div>
</div>
<?php echo $this->render('backend.preview.javascript'); ?>

