<?php 
$this->theme->add($this->url . 'assets/specs/css/style.css', '', 'spec-style-css');
$this->theme->add($this->url . 'assets/specs/css/theme.css', '', 'spec-theme-css');
$this->theme->add($this->url . 'assets/specs/plugins/simplelightbox/simple-lightbox.min.css', '', 'simple-lightbox.min.css');
$this->theme->add($this->url . 'assets/specs/plugins/popper.min.js', '', 'popper.min.js');
$this->theme->add($this->url . 'assets/specs/plugins/bootstrap/js/bootstrap.min.js', '', 'bootstrap.min.js');
$this->theme->add($this->url . 'assets/specs/plugins/smoothscroll.min.js', '', 'smoothscroll.min.js');
$this->theme->add($this->url . 'assets/specs/plugins/simplelightbox/simple-lightbox.min.js', '', 'simple-lightbox.min.js');
$this->theme->add($this->url . 'assets/specs/plugins/gumshoe/gumshoe.polyfills.min.js', '', 'gumshoe.polyfills.min.js');
$this->theme->add($this->url . 'assets/specs/js/docs.js', '', 'docs.js');
?>
<div class="button-action">
    <div class="d-flex w-100">
        <?php foreach($this->button_header as $button) : ?>
        <div class="me-2">
            <a href="<?php echo $button['link']; ?>">
                <button type="button" class="<?php echo $button['class'] ?>"><?php echo $button['title'] ?></button>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="docs-page pt-0">
    <div class="docs-wrapper">
        <div id="docs-sidebar" class="docs-sidebar no-top">
            <nav id="docs-nav" class="docs-nav navbar" >
                <ul class="section-items list-unstyled nav flex-column pb-3">
                    <li class="nav-item section-title"><a class="nav-link scrollto active" href="#section-1"><span
                                class="theme-icon-holder me-2"><i class="fas fa-map-signs"></i></span><?php echo $this->data['title'] ?></a>
                    </li>
                    <?php if(isset($this->data['list_tree']) && is_array($this->data['list_tree'])): ?>
                        <?php foreach($this->data['list_tree'] as $item) : ?>
                            <?php if ($item['tree_level'] == 1) : ?>
                                <li class="nav-item section-title mt-3">
                                    <a class="nav-link scrollto" href="#item_<?php echo $item['id']; ?>">
                                        <span class="theme-icon-holder me-2">
                                            <i class="fas fa-lightbulb"></i>
                                        </span>
                                        <?php echo $item['index'].'. '. $item['title']; ?>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li class="nav-item">
                                    <?php echo str_repeat('<div class="nav-item">', (int) $item['tree_level'] - 2) ?>
                                        <a class="nav-link scrollto" href="#item_<?php echo $item['id']; ?>">
                                            <?php echo $item['index'].'. '. $item['title']; ?>
                                        </a>
                                    <?php echo str_repeat('</div>', (int) $item['tree_level'] - 2) ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <div class="docs-content">
            <div class="container">
                <article class="docs-article pb-0" id="section-1">
                    <header class="docs-header">
                        <h1 class="docs-heading text-center mb-0">
                            <?php echo $this->data['title']; ?>
                        </h1>
                    </header>
                </article>
                <article class="docs-article pb-0" id="section-1">
                <?php if(isset($this->data['list_tree']) && is_array($this->data['list_tree'])): ?>
                    <?php foreach($this->data['list_tree'] as $item) : ?>
                        <section class="docs-section py-3 <?php echo isset($item['note']['data']) && $item['note']['data'] ? '' : 'border-0'?> " id="item_<?php echo $item['id']; ?>">
                            <?php if ($item['tree_level'] == 1) : ?>
                                <h2 class="section-heading mb-0"> <?php echo $item['index'].'. '. $item['title']; ?></h2>
                            <?php else : ?>
                                <h4 class="section-heading fs-4"> <?php echo $item['index'].'. '. $item['title']; ?></h4>
                            <?php endif; ?>
                            <?php 
                                $this->_view->setVar('currentId', $item['note']['id']);
                                echo $this->renderWidget('note_'. $item['note']['type'].'::preview'); ?>   
                        </section>
                    <?php endforeach; ?>
                <?php endif; ?>
                </article>
            </div>
        </div>
    </div>
    </div>
</div>