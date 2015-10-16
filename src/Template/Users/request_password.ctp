<section>
    <?= $this->Form->create('Users') ?>
    <?= $this->Form->input('email') ?>
    <?= $this->Form->submit('Request New Password') ?>
    <?= $this->Form->end() ?>
</section>