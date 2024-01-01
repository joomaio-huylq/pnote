
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-sm-12">
            <div class="text-center mb-2">
                <a href="<?php echo $this->url($this->data['path']);?>">
                    <?php if($this->isImage) : ?>
                        <img class="img-fuild" src="<?php echo $this->url .'/'. $this->data['path']; ?>" alt="<?php echo basename($this->data['path'])?>">
                    <?php else : ?>
                        <img class="img-fuild" src="<?php echo $this->url .'/media/default_file.png'; ?>" alt="<?php echo basename($this->data['path'])?>">
                    <?php endif; ?>
                </a>
            </div>
            <div class="text-center">
                <a href="<?php echo $this->url($this->data['path']);?>"><?php echo basename($this->data['path'])?></a>
            </div>
        </div>
    </div>
</div>
