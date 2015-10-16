<section>
    <?= $this->Form->create($user) ?>
    <?= $this->Form->input('username') ?>
    
    <?php if (!$useEmailAsUsername): ?>
        <?= $this->Form->input('email') ?>
    <?php endif; ?>
    
    <?= $this->Form->input('first_name') ?>
    <?= $this->Form->input('last_name') ?>
    
    <?= $this->Form->submit('Update') ?>
    <?= $this->Form->end() ?>
</section>