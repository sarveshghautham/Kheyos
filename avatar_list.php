<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/10/14
 * Time: 11:22 PM
 */
if (!isset ($_SESSION['user_id'])) {
    header('Location: login.php');
}
?>

<div class="list-group avatars_page_avatars_list">
    <?php
    for ($i = 0; $i < count($avatar_ids); $i++) {
        $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
        $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i]);
        ?>
        <a href="#<?php echo $avatar_info['handle']; ?>" class="avatar-list list-group-item
            <?php
        if ($avatar_id != 0) {

            if ($avatar_ids[$i] == $avatar_id)
                echo "active";
        } else {
            if ($i == 0)
                echo "active";
        }
        ?>

            ">
            <div class="imgLiquidFill imgLiquid default_profile_20 add_display_inline_block">
                <img alt="<?php echo "@" . $avatar_info['handle']; ?>"
                     src="/get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
            </div>
            <?php echo $avatar_info['name']; ?>
        </a>
    <?php
    }
    ?>
    <a href="/create_new_avatar.php" class="list-group-item">
        Create New Avatar
    </a>
</div>