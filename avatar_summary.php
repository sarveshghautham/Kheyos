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
    $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i]);
    $cover_picture_id = $objPictures->GetCoverPictureId($avatar_ids[$i]);
    $pic_timestamp = $objPictures->GetPicTimeStamp($picture_id);

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

    $statusMonth = date("F", mktime(0, 0, 0, $date[1], 10));
    $monthName = date("F", mktime(0, 0, 0, $pic_date[1], 10));

    $followersCount = $objFollow->MyFollowersCount($avatar_ids[$i]);
    $followingCount = $objFollow->MyFollowingCount($avatar_ids[$i]);

    ?>

    <div class="col-sm-8 avatar-summary" id="<?php echo $avatar_info['handle']; ?>">
        <div class="row">
            <div class="col-xs-12 post_display_block text-center">
                <div class="width_35 default_profile_35 add_display_inline_block">
                    <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>" class="width_35"/>
                </div>
                <h4><?php echo $avatar_info['name']; ?></h4>
                <h5><?php echo "@" . $avatar_info['handle']; ?></h5>
            </div>
            <div class="col-xs-12 post_display_block">
                <ul class="nav nav-pills nav-justified">
                    <li><a href="history.php?avatar_id=<?php echo $avatar_ids[$i]; ?>" class="text-center">Complete <br
                                class="fillers_max_780"/>Profile</a></li>
                    <li><a href="followers.php?avatar_id=<?php echo $avatar_ids[$i]; ?>" class="text-center">Followers:
                            <br class="fillers_max_780"/><?php echo $followersCount; ?></a></li>
                    <li><a href="followers.php?avatar_id=<?php echo $avatar_ids[$i]; ?>" class="text-center">Following:
                            <br class="fillers_max_780"/><?php echo $followingCount; ?></a></li>
                    <li><a href="#" class="text-center">Update <br class="fillers_max_780"/>Status</a></li>
                    <li><a href="#" class="text-center">Update <br class="fillers_max_780"/>Cover</a></li>
                    <li><a href="edit_avatars.php?avatar_id=<?php echo $avatar_ids[$i]; ?>" class="text-center">Edit <br
                                class="fillers_max_780"/>Avatar</a></li>
                </ul>
            </div>
            <div class="col-xs-12 post_display_block">
            </div>

            <?php
            if ($status_info != null) {
                ?>
                <div class="col-xs-12 post_display_block">

                    <a><?php echo $time[0] . ":" . $time[1] . " - " . $date[2] . " " . $statusMonth . " " . $date[0]; ?></a>
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
            if ($cover_picture_id != 0) {
                ?>

                <div class="col-xs-12 post_display_block">
                    <a><?php echo $pic_time[0] . ":" . $pic_time[1] . " - " . $pic_date[2] . " " . $monthName . " " . $pic_date[0]; ?></a>

                    <div class="item active">
                        <img src="get_profile_pic.php?picture_id=<?php echo $cover_picture_id; ?>" class="width_100pc">

                        <div class="carousel-caption">
                            <p class="text-right">
                                <span class="label label-transparent" style="font-size:130%;">Chennai, India</span>
                            </p>
                        </div>
                    </div>
                    <a href="#">Like</a>
                &middot;
                <a href="#">Comment</a>
            </div>
            <?php
            }
            ?>
        </div>
    </div>


<?php

}
?>
