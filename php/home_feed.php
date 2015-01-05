<?php

require_once 'app/Follow.php';
require_once 'app/Avatars.php';
require_once 'app/Activity.php';
require_once 'app/Status.php';
require_once 'app/Pictures.php';
require_once 'app/ProcessForm.php';

$objAvatar = new Avatars();
$objFollow = new Follow();
$objActivity = new Activity();
$objStatus = new Status();
$objPictures = new Pictures();
$objProcessForm = new ProcessForm();

$activities = $objActivity->AvatarListActivities();
$avatar_list = $objAvatar->AvatarList($_SESSION['user_id']);

$token = $objProcessForm->GenerateFormToken('FormUpdateStatus');


if (count($activities) == 0) {
    echo "Don't see anything. You probably have to follow people to see some posts. :)";
}

for ($i = 0; $i < count($activities); $i++) {

    if ($activities[$i]['type'] == 1) {
        $status_id = $activities[$i]['id'];
        $avatar_id = $objStatus->GetAvatarId($status_id);
        $avatar_info = $objAvatar->GetAvatarInfo($avatar_id);
        $picture_id = $objPictures->GetProfilePictureId($avatar_id);
        $status_info = $objStatus->GetStatusInfo($status_id);

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
        $follow_avatar_list = $objFollow->GetMyFollowingAvatars($avatar_list, $avatar_id);

        ?>

        <div class="row post_display_block">
            <div class="col-xs-12">
                <?php
                $avatar_id_from_handle = $objAvatar->GetAvatarIdFromHandle($avatar_info['handle']);

                if (in_array($avatar_id_from_handle, $avatar_list)) {

                ?>

                <a href="my_avatars/<?php echo $avatar_info['handle']; ?>">

                    <?php
                    }
                    else {
                    ?>
                    <a href="<?php echo $avatar_info['handle']; ?>">
                        <?php
                        }
                        ?>


                        <div class="imgLiquidFill imgLiquid default_profile_20 add_display_inline_block">
                            <?php
                            if ($picture_id == null) {
                                ?>
                                <img src="img/pic.png"/>
                            <?php
                            } else {
                                ?>
                                <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        echo $avatar_info['name'];
                        ?>
                    </a>
                    <br/>
                    <?php
                    if ($status_info != null) {
                        ?>
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
                                        src="get_profile_pic.php?picture_id=<?php echo $status_info['picture_id']; ?>"
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
                    <?php
                    }
                    ?>
            </div>
        </div> <!-- row post-display-block -->
        <hr/>
    <?php
    } else {

        $follow_id = $activities[$i]['id'];
        $follow_info = $objFollow->GetFollowInfo($follow_id);

        if (in_array($follow_info['avatar_id_1'], $avatar_list)) {

            $my_avatar_id = $follow_info['avatar_id_1'];
            $his_avatar_id = $follow_info['avatar_id_2'];
            $following = 1;

        } else {

            $my_avatar_id = $follow_info['avatar_id_2'];
            $his_avatar_id = $follow_info['avatar_id_1'];
            $following = 0;

        }

        $my_avatar_info = $objAvatar->GetAvatarInfo($my_avatar_id);
        $his_avatar_info = $objAvatar->GetAvatarInfo($his_avatar_id);
        $my_picture_id = $objPictures->GetProfilePictureId($my_avatar_id);
        $his_picture_id = $objPictures->GetProfilePictureId($his_avatar_id);

        $timestamp = $follow_info['time'];
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
        $follow_avatar_list = $objFollow->GetMyFollowingAvatars($avatar_list, $his_avatar_id);
        ?>


        <div class="row post_display_block">
            <div class="col-xs-12">
                <?php


                $his_avatar_id_from_handle = $objAvatar->GetAvatarIdFromHandle($his_avatar_info['handle']);

                if (in_array($his_avatar_id_from_handle, $avatar_list)) {
                $_SESSION['avatar_id'] = $his_avatar_id_from_handle;
                ?>

                <a href="my_avatars/<?php echo $his_avatar_info['handle']; ?>">

                    <?php
                    }
                    else {
                    ?>
                    <a href="<?php echo $his_avatar_info['handle']; ?>">
                        <?php
                        }
                        ?>

                        <div class="imgLiquidFill imgLiquid default_profile_20 add_display_inline_block">
                            <?php
                            if ($his_picture_id == null) {
                                ?>
                                <img src="img/pic.png"/>
                            <?php
                            } else {
                                ?>
                                <img src="get_profile_pic.php?picture_id=<?php echo $his_picture_id; ?>"/>
                            <?php
                            }
                            ?>
                        </div>

                        <?php echo $his_avatar_info['name'] ?>
                    </a>
                    <br/>
                    <a class="add_link_gray add_font_size_12"><?php echo $time[0] . ":" . $time[1] . " - " . $date[2] . " " . $statusMonth . " " . $date[0]; ?></a>
                    <br/>

                    <?php

                    if ($following == 1) {

                        echo "Followed By";

                    } else {

                        echo "Followed";
                    }
                    ?>
                    <br/>
                    <?php

                    $my_avatar_id_from_handle = $objAvatar->GetAvatarIdFromHandle($my_avatar_info['handle']);

                    if (in_array($my_avatar_id_from_handle, $avatar_list)) {

                    $_SESSION['avatar_id'] = $my_avatar_id_from_handle;

                    ?>

                    <a href="my_avatars/<?php echo $my_avatar_info['handle']; ?>">

                        <?php
                        }
                        else {
                        ?>


                        <a href="<?php echo $my_avatar_info['handle']; ?>">
                            <?php
                            }
                            ?>
                            <div class="imgLiquidFill imgLiquid default_profile_10 add_display_inline_block">
                                <?php
                                if ($my_picture_id == null) {
                                    ?>
                                    <img src="img/pic.png"/>
                                <?php
                                } else {
                                    ?>
                                    <img src="get_profile_pic.php?picture_id=<?php echo $my_picture_id; ?>"/>
                                <?php
                                }
                                ?>
                            </div>

                            <?php echo $my_avatar_info['name']; ?>
                        </a>
            </div>
        </div> <!-- row post-display-block -->
        <hr/>

    <?php
    }
}
?>