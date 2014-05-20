<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/11/14
 * Time: 12:44 AM
 */

for ($i = 0; $i < count($avatar_ids); $i++) {
    $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
    $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i])
    ?>

    <div class="edit-avatar-form col-sm-8" id="<?php echo $avatar_info['handle']; ?>">

        <form class="form-horizontal" role="form" enctype="multipart/form-data" name="EditAvatarForm" method="POST"
              action="edit_avatars.php">

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="form-group">
                <label class="col-lg-2 control-label">
                    <div class="width_30 default_profile_30 add_display_inline_block">
                        <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>" class="width_30"/>
                    </div>
                </label>

                <div class="col-lg-10">
                    <a class="btn btn-default btn-xs" href='javascript:;'>
                        Change Profile Picture
                        <input type="file" class="btn-file" name="profPicture" size="40"
                               onchange='$("#upload-file-info").html($(this).val());'>
                    </a>
                    <br/>

                    <a class='btn btn-default btn-xs'>
                        Remove Profile Picture
                    </a>

                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>

                <div class="col-sm-10">
                    <input class="form-control" name="txtName" value="<?php echo $avatar_info['name']; ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <span id="name_on_error" class="error_message">
                        <em>FirstName LastName or <br/>
                            FirstName MiddleName LastName</em> <br/><br/>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Handle</label>

                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">@</span>
                        <input class="form-control" name="txtHandle" value="<?php echo $avatar_info['handle']; ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <span id="handle_on_error" class="error_message">
                        <em>[username]@[domain].com</em> <br/>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Bio</label>

                <div class="col-sm-10">
                    <textarea name="txtBio" class="form-control" rows="2"><?php echo $avatar_info['bio']; ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <span id="bio_on_error" class="error_message">
                        <em>Password should be 8 characters or more.</em> <br/>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Birthday</label>

                <div class="col-sm-10">
                    <input type="date" name="txtDate" class="form-control" value="<?php echo $avatar_info['dob']; ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <span id="birthday_on_error" class="error_message">

                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Location</label>

                <div class="col-sm-10">
                    <input name="txtLocation" class="form-control" value="<?php echo $avatar_info['location']; ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <span id="location_on_error" class="error_message">
                        <em>City, Country</em> <br/>
                        <em>Chennai, India</em> <br/>
                        <em>Madurai, India</em> <br/>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Gender</label>

                <div class="col-sm-10">
                    <div class="radio" onFocus="Info_Over('#gender_on_focus_info')"
                         onBlur="Info_Out('#gender_on_focus_info')">
                        <label>
                            <input type="radio" name="radioGender" id="optionsRadios1"
                                   value="2" <?php if ($avatar_info['gender'] == 2) {
                                echo "checked";
                            } ?>> Female
                        </label>
                    </div>
                    <div class="radio" onFocus="Info_Over('#gender_on_focus_info')"
                         onBlur="Info_Out('#gender_on_focus_info')">
                        <label>
                            <input type="radio" name="radioGender" id="optionsRadios2"
                                   value="1" <?php if ($avatar_info['gender'] == 1) {
                                echo "checked";
                            } ?>> Male
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Account Type</label>

                <div class="col-sm-10">
                    <div class="radio" onFocus="Info_Over('#account_type_on_focus_info')"
                         onBlur="Info_Out('#account_type_on_focus_info')">
                        <label>
                            <input type="radio" name="radioType" id="optionsRadios_1"
                                   value="0" <?php if ($avatar_info['avatar_type'] == 0) {
                                echo "checked";
                            } ?>>
                            Public Account - Anyone can follow you (Default)
                        </label>
                    </div>
                    <div class="radio" onFocus="Info_Over('#account_type_on_focus_info')"
                         onBlur="Info_Out('#account_type_on_focus_info')">
                        <label>
                            <input type="radio" name="radioType" id="optionsRadios_2"
                                   value="1" <?php if ($avatar_info['avatar_type'] == 1) {
                                echo "checked";
                            } ?>>
                            Protected Account - You decide who follows you
                        </label>
                    </div>
                    <span class="help-block with-errors"></span>
                </div>
            </div>

            <input type="hidden" name="avatar_id" value="<?php echo $avatar_ids[$i]; ?>"/>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="btnEdit" class="btn btn-primary btn-lg">Save Changes</button>
                </div>
            </div>

        </form>
    </div>
<?php
}