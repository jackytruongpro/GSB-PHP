
<br />
<div class="container">
    <div class="alert alert-info" role="alert">
        <?php
        foreach ($_REQUEST['messageInfo'] as $messageInfo) {
            echo '<p>' . htmlspecialchars($messageInfo) . '</p>';
        }
        ?>
    </div>
</div>
