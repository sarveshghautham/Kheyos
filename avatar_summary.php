<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/10/14
 * Time: 8:21 PM
 */

for ($i = 0; $i < count($avatar_ids); $i++) {
    $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
    $status_info = $objStatus->GetStatus($avatar_ids[$i]);
    $picture_id = $objPictures->GetPictureId($avatar_ids[$i]);

    $timestamp = strtotime($status_info['time']);
    $date = date('d-m-Y', $timestamp);
    $time = date('Gi.s', $timestamp);


    ?>
    <div class="col-sm-8 avatar-summary" id="<?php echo $avatar_info['handle']; ?>">
        <h3 class="text-center">
            <a href="avatars_mithun_avatar_1.html">
                <div class="width_35 default_profile_35 add_display_inline_block">
                    <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>" class="width_35"/>
                </div>
                <?php echo $avatar_info['name']; ?>
            </a>
        </h3>
        <div class="col-xs-12 post_display_block text-center">
            <button class="btn btn-default btn-sm">
                Change Status
            </button>
            <button class="btn btn-default btn-sm">
                Change Cover
            </button>

            <form method="POST" action="edit_avatars.php">
                <input type="hidden" name="avatar_id" value="<?php echo $avatar_ids[$i]; ?>"/>
                <button type="submit" class="btn btn-default btn-sm">
                    Edit Avatar
                </button>

            </form>

        </div>
        <?php
        if ($status_info != null) {
            ?>
            <div class="col-xs-12 post_display_block">

                <a><?php echo $time . " - " . $date; ?></a>
                <br/>
                <?php
                echo $status_info['text'];
                ?>

                <br/>
                <a href="#">Like</a>
                &middot;
                <a href="#">Comment</a>
                <br/>
            </div>
        <?php
        }
        ?>
        <div class="col-xs-12 post_display_block">
            <a>10:50 AM - 9 March 2014</a>

            <div class="item">
                <img src="img/cover-photo1.jpg" alt="Chennai, India" class="width_100pc">

                <div class="carousel-caption">
                    <p class="text-right">
                        <span class="carousel_img_location">Chennai, India</span>
                    </p>
                </div>
            </div>
            <a href="#">Like</a>
            &middot;
            <a href="#">Comment</a>
        </div>
    </div>
<?php
}
?>
</div>