
<?php if($this->data['status'] != -2)  : ?>
<div class="container align-items-center preview-mode mx-auto pt-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div>
                <?php $this->ui->field('data_'. $this->id); ?>
            </div>
        </div>
    </div>
</div>
<script>
     $(document).ready(function(e) {
        var objects = canvas.getObjects();
        for (var i = 0; i < objects.length; i++) {
            objects[i].selectable = false;
        }
        $('#fabric_tool_menu').remove();
        $('#fabric_slide_menu .add-button').remove();
        $('#fabric_slide_menu .remove-button').remove();
    });
</script>
<?php endif; ?>
