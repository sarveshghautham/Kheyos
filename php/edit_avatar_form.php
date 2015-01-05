<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/11/14
 * Time: 12:44 AM
 */

if (!isset ($_SESSION['user_id'])) {
    header('Location: login.php');
}

for ($i = 0; $i < count($avatar_ids); $i++) {
    $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
    $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i]);
    $follow_avatar_list = $objFollow->GetMyFollowingAvatars($avatar_list, $avatar_ids[$i]);

    ?>

    <div class="edit-avatar-form col-sm-8" id="<?php echo $avatar_info['handle']; ?>">
        <h3 class="text-center">Edit Avatar: <span class="text-muted"><?php echo $avatar_info['name']; ?></span></h3>
        <br/>

        <form class="form-horizontal" role="form" enctype="multipart/form-data" name="EditAvatarForm" method="POST"
              action="edit_avatars.php">

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="form-group">
                <label class="col-sm-3 control-label">
                    <div class="imgLiquidFill imgLiquid default_profile_50 add_display_inline_block">
                        <img src="/get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
                    </div>
                </label>

                <div class="col-sm-9">
                    <br class="fillers_max_768"/>
                    <input type="file" name="profPicture" size="40"
                           onchange='$("#upload-file-info").html($(this).val());' class="Profile_Pic_Input_File">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Name</label>

                <div class="col-sm-9">
                    <input class="form-control" name="txtName" value="<?php echo $avatar_info['name']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Handle</label>

                <div class="col-sm-9">
                    <input class="form-control " name="txtHandle" value="<?php echo $avatar_info['handle']; ?>"
                           disabled>
                    <span class="help-block">You cannot edit Handle.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Bio</label>

                <div class="col-sm-9">
                    <textarea name="txtBio" class="form-control" rows="4"><?php echo $avatar_info['bio']; ?></textarea>
                </div>
            </div>

            <input type="hidden" name="my_avatar_count" value="<?php echo count($avatar_list); ?>">

            <div class="form-group">
                <label class="col-sm-3 control-label">Follow With</label>

                <div class="col-sm-9">
                    <?php
                    for ($j = 0; $j < count($avatar_list); $j++) {

                        if ($avatar_ids[$i] != $avatar_list[$j]) {

                            $my_avatar_info = $objAvatars->GetAvatarInfo($avatar_list[$j]);
                            $my_picture_id = $objPictures->GetProfilePictureId($avatar_list[$j]);

                            ?>
                            <div class="checkbox">
                                <label>
                                    <input type="hidden" name="my_avatar_id_<?php echo $j; ?>"
                                           value="<?php echo $avatar_list[$j]; ?>">
                                    <input type="checkbox" name="my_avatar_ids[]"
                                           value="<?php echo $avatar_list[$j]; ?>"
                                        <?php

                                        if (count($follow_avatar_list) != 0) {
                                            if (in_array($avatar_list[$j], $follow_avatar_list)) {
                                                echo "Checked";
                                            }
                                        }
                                        ?>

                                        >

                                    <div class="imgLiquidFill imgLiquid default_profile_20 add_display_inline_block">
                                        <img src="/get_profile_pic.php?picture_id=<?php echo $my_picture_id; ?>"/>
                                    </div>
                                    <?php
                                    echo $my_avatar_info['name'];
                                    ?>
                                </label>
                            </div>

                        <?php
                        }
                    }
                    ?>
                </div>
            </div>


            <input type="hidden" name="avatar_id" value="<?php echo $avatar_ids[$i]; ?>"/>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <br/>
                    <button type="submit" id="btnEdit" name="btnEdit" class="btn btn-primary btn-lg">Save Changes
                    </button>
                </div>
            </div>

        </form>
    </div>
<?php
}
?>

<script type='text/javascript'>
    $(".Profile_Pic_Input_File").change(function () {
        var fthis = this;
        var f = this.files[0];
        var ext = $(this).val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert('invalid extension!');
            reset_form_element($(this));
        }
        else {
            fSize = Math.round(f.fileSize / 1024);
            if (fSize > 4096) {
                alert("The image is larger than 4 MB. Choose a smaller image.");
                reset_form_element($(fthis));
            }
            else {
                var _URL = window.URL || window.webkitURL;
                var image, file;
                if ((file = this.files[0])) {
                    image = new Image();
                    image.onload = function () {
                        if (this.width > 250) {
                            if (this.height > 250) {

                            }
                            else {
                                alert("The image should be larger than 250 x 250!");
                                reset_form_element($(fthis));
                            }
                        }
                        else {
                            alert("The image should be larger than 250 x 250!");
                            reset_form_element($(fthis));
                        }
                    };
                    image.src = _URL.createObjectURL(file);
                }
            }
        }
    });

    function reset_form_element(e) {
        e.wrap('<form>').parent('form').trigger('reset');
        e.unwrap();
    }
</script>