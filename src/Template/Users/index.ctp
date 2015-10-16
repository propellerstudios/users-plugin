<section>
    <ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user->username ?></li>
    <?php endforeach; ?>
    </ul>
</section>