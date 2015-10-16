<section>
    <?= $this->Form->create() ?>
    <?= $this->Form->input('username') ?>
    <?= $this->Form->input('password') ?>
    <?= $this->Form->submit('Login') ?>
    <?= $this->Form->end() ?>
</section>