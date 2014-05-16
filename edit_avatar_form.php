<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/11/14
 * Time: 12:44 AM
 */

for ($i = 0; $i < count($avatar_ids); $i++) {
    $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
    $picture_id = $objPictures->GetPictureId($avatar_ids[$i])
    ?>

    <div class="edit-avatar-form col-sm-8" id="<?php echo $avatar_info['handle']; ?>">

        <form class="form-horizontal" role="form" enctype="multipart/form-data" name="EditAvatarForm" method="POST"
              action="edit_avatars.php">

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"></label>

                <div class="col-sm-10">
                    <div class="width_50 default_profile_50 add_display_inline_block">
                        <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>" class="width_50"/>
                    </div>
                    <input type="file" id="exampleInputFile" name="profPicture">
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

            <br/>
            <input type="hidden" name="avatar_id" value="<?php echo $avatar_ids[$i]; ?>"/>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button name="btnEdit" type="submit" class="btn btn-lg btn-primary btn-block">Save Changes</button>
                </div>
            </div>

        </form>
    </div>
<?php
}