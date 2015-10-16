<section>
    <div><?= $user->username ?></div>
    
    <?php if (!$useEmailAsUsername): ?>
        <div><?= $user->email ?></div>
    <?php endif; ?>
    
    <div><?= $user->name ?></div>
</section>