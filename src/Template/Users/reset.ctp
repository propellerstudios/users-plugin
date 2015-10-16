<section>
    <?= $this->Form->create('Users', ['type' => 'put']) ?>
    <?= $this->Form->input('password') ?>
    <?= $this->Form->input('confirm_password', ['type' => 'password']) ?>
    
    <?= $this->Form->submit('Reset Password') ?>
    <?= $this->Form->end(); ?>
</section>