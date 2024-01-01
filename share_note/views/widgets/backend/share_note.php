<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
?>
<div>
    <label class="form-label">Assignee</label>
    <select name="assignee[]" class="select-tag" multiple id="assign_user">
        <optgroup label="User">
            <?php foreach($this->users as $user) :?>
            <option value="user_<?php echo $user['id']; ?>" <?php echo in_array($user['id'], $this->assign_user) ? 'selected' : ''; ?>><?php echo $user['name']; ?></option>
            <?php endforeach;?>
        </optgroup>
        <optgroup label="User Group">
            <?php foreach($this->user_groups as $group) :?>
                <option value="group_<?php echo $group['id']; ?>" <?php echo in_array($group['id'], $this->assign_user_group) ? 'selected' : ''; ?>><?php echo $group['name']; ?></option>
            <?php endforeach;?>
        </optgroup>
    </select>
</div>
<?php echo $this->renderWidget('share_note::backend.javascript'); ?>