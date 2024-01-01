<?php use SPT\Theme;

static $mermaidJS;
if(!isset($mermaidJS))
{
    $this->theme->add( $this->url.'assets/mermaidjs/js/mermaid.js', '', 'mermaid_js', 'top');
    $this->theme->add( $this->url.'assets/mermaidjs/js/main.js', '', 'main_mermaid_js');
    $this->theme->add( $this->url.'assets/mermaidjs/css/style.css', '', 'mermaid_style');
} 

if($this->field->showLabel): ?>
<label for="<?php echo $this->field->name ?>" class="form-label"><?php echo $this->field->label ?><?php echo $this->field->required ? ' * ':''?></label>
<?php endif; ?>
<div class="<?php echo $this->field->formClass?> mermaid-section">
    <pre id="<?php echo $this->field->id ?>" ><?php echo $this->field->value?></pre>
</div>
<script>
    $(document).ready(function(){
        <?php if($this->field->value): ?>
        mermaid.run({
            nodes: document.querySelectorAll('#<?php echo $this->field->id ?>'),
        });
        <?php endif; ?>
    });
</script>

