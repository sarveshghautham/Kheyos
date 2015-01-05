<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/26/14
 * Time: 11:46 AM
 */

?>

<div class="btn-group btn-group-justified" id="Settings">
    <div class="btn-group">
        <button type="button" class="btn btn-default">Account</button>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-default">Help</button>
    </div>
    <div class="btn-group">
        <form action="/logout.php" method="POST">
            <button type="submit" class="btn btn-default">Sign Out</button>
        </form>
    </div>
</div>