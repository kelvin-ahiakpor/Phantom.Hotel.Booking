<?php
session_start();
session_unset();
session_destroy();
header("Location: ../view/recipe_feed.php");
exit;
?>
