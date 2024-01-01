<?php 
    $this->theme->add($this->url . 'assets/css/handsontable.full.min.css', '', 'handsontable-css');
    $this->theme->add($this->url . 'assets/js/handsontable.full.min.js', '', 'bootstrap-handsontable');
    echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-sm-12">
            <input id="table_data" type="hidden" name="table_data" value='<?php echo json_encode($this->data['products']); ?>'>
            <div id="preview-table"></div>
        </div>
    </div>
</div>

<?php echo $this->render('backend.preview.javascript'); ?>
