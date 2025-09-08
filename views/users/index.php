<?php
foreach ($users as $user):
?>
    <li>
        <?= $user->name ?> - <?= $user->email ?>
    </li>

<?php
endforeach;
?>