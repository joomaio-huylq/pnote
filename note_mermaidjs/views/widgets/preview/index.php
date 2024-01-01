<?php if($this->data['status'] != -2)  : ?>
<?php $this->ui->field('mermaid_'. $this->data['id']); ?>
<script>
    $(document).ready(function(){
        var code = `<?php echo $this->data['data'] ?>`;
        $('#mermaid_<?php echo $this->data['id'] ?>').html(code);
        mermaid.run({
            nodes: document.querySelectorAll('#mermaid_<?php echo $this->data['id'] ?>'),
        });
    })
</script>
<?php endif; ?>