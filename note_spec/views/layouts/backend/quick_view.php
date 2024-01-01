<?php if($this->detail) : ?>
    <?php foreach($this->detail['list_tree'] as $item) : ?>
        <?php if($item['id']) : ?>
            <<?php echo $item['tree_level'] < 2 ? 'h3' : 'h4'?>><?php echo $item['index'] . ' '. $item['note']['title'] ?></<?php echo $item['tree_level'] < 2 ? 'h3' : 'h4'?>>
            <?php 
                $this->_view->setVar('currentId', $item['note']['id']);
                echo $this->renderWidget('note_'. $item['note']['type'].'::preview'); ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
