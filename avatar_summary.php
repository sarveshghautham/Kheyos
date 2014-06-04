<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/10/14
 * Time: 8:21 PM
 */
if (!isset ($_SESSION['user_id'])) {
    header('Location: login.php');
}
for ($i = 0; $i < count($avatar_ids); $i++) {
    $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
    $status_info = $objStatus->GetStatus($avatar_ids[$i]);
    $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i]);
    $cover_picture_id = $objPictures->GetCoverPictureId($avatar_ids[$i]);
    $pic_timestamp = $objPictures->GetPicTimeStamp($cover_picture_id);

    $timestamp = $status_info['time'];
    $dateTime = explode(" ", $timestamp);
    $date = $dateTime[0];
    $date = explode("-", $date);
    $time = $dateTime[1];
    $time = explode(":", $time);
    if ($time[0] > 12) {
        $time[0] = $time[0] - 12;
        $time[1] .= " PM";
    } else {
        $time[1] .= " AM";
    }
    $statusMonth = date("F", mktime(0, 0, 0, $date[1], 10));

    $dateTime = explode(" ", $pic_timestamp);
    $pic_date = $dateTime[0];
    $pic_date = explode("-", $pic_date);
    $pic_time = $dateTime[1];

    $pic_time = explode(":", $pic_time);
    if ($pic_time[0] > 12) {
        $pic_time[0] = $pic_time[0] - 12;
        $pic_time[1] .= " PM";
    } else {
        $pic_time[1] .= " AM";
    }

    $monthName = date("F", mktime(0, 0, 0, $pic_date[1], 10));

    $followersCount = $objFollow->MyFollowersCount($avatar_ids[$i]);
    $followingCount = $objFollow->MyFollowingCount($avatar_ids[$i]);

    ?>

    <div class="col-sm-8 avatar-summary" id="<?php echo $avatar_info['handle']; ?>">
        <div class="row">
            <div class="col-xs-12 post_display_block">
                <div class="imgLiquidFill imgLiquid default_profile_35 add_display_inline_block">
                    <img alt="<?php echo "@" . $avatar_info['handle']; ?>"
                         src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
                </div>
                <h4><?php echo $avatar_info['name']; ?></h4>
                <h5><?php echo "@" . $avatar_info['handle']; ?></h5>
            </div>
            <div class="col-xs-12 post_display_block">
	        	<span class="add_word_wrap_break_word">
	            <?php echo $avatar_info['bio']; ?>
	            	</span>
            </div>
            <div class="col-xs-12 post_display_block">
                <ul class="nav nav-pills nav-justified">
                    <li><a href="/<?php echo $avatar_info['handle']; ?>/log" class="text-center">Activity <br
                                class="fillers_max_780"/>Log</a></li>
                    <li><a href="/<?php echo $avatar_info['handle']; ?>/followers" class="text-center">Followers:
                            <br class="fillers_max_780"/><?php echo $followersCount; ?></a></li>
                    <li><a href="/<?php echo $avatar_info['handle']; ?>/following" class="text-center">Following:
                            <br class="fillers_max_780"/><?php echo $followingCount; ?></a></li>
                    <li><a href="edit_avatars/<?php echo $avatar_info['handle']; ?>" class="text-center">Edit <br
                                class="fillers_max_780"/>Avatar</a></li>
                </ul>
            </div>
            <?php
            if ($status_info != null) {
                ?>

                <div class="col-xs-12 post_display_block">
                    <a class="add_link_gray add_font_size_12"><?php echo $time[0] . ":" . $time[1] . " - " . $date[2] . " " . $statusMonth . " " . $date[0]; ?></a>

                    <div class="item">
                        <div class="imgLiquidFill imgLiquid status_cover_photo"
                             style="background-color: <?php echo $status_info['bg_color']; ?>;">
                            <!-- Fill Background Color from the Form -->
                            <img alt="Woody"
                                <?php
                                if ($status_info['picture_id'] == 0) {


                                    ?>
                                    src=""
                                <?php
                                } else {
                                    ?>
                                    src="/get_profile_pic.php?picture_id=<?php echo $status_info['picture_id']; ?>"
                                <?php
                                }
                                ?>
                                /> <!-- If pic is chosen, fill source. Or else empty -->
                        </div>
                        <div class="container carousel-container">
                            <div class="carousel-caption">
                                <p class="text-center" style="color: <?php echo $status_info['font_color']; ?>;">
                                    <!-- Font Colour from form -->
                                    <?php echo $status_info['text']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>


<?php

}
?>