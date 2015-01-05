<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/27/14
 * Time: 3:19 PM
 */

for ($i = 0; $i < count($avatar_ids); $i++) {
    $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
    $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i])

    ?>
    <div class="prev-created-avatars col-sm-8" id="<?php echo $avatar_info['handle']; ?>">
        <div class="col-xs-12 post_display_block">
            <div class="imgLiquidFill imgLiquid default_profile_35 add_display_inline_block">
                <?php
                if ($picture_id == 0) {
                    ?>
                    <img src="img/pic.png" class="width_35"/>
                <?php
                } else {
                    ?>
                    <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
                <?php
                }
                ?>
            </div>
            <h4><?php echo $avatar_info['name']; ?></h4>
            <h5>@<?php echo $avatar_info['handle']; ?></h5>
        </div>
        <div class="col-xs-12 post_display_block">
        	<span class="add_word_wrap_break_word">
            <?php
            echo $avatar_info['bio'];
            ?>
            	</span>
        </div>
    </div>
<?php
}
?>