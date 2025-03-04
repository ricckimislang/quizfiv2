<!-- Profile Picture Modal -->
<div class="modal fade" id="changeWallpaperModal" tabindex="-1" aria-labelledby="changeWallpaperModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeWallpaperModalLabel">Choose Your Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ChangeWallpaperForm">
                    <div class="avatar-grid">
                        <?php
                        // Directory containing avatar images
                        $avatar_dir = 'assets/classroom-bg/';
                        $avatars = glob($avatar_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                        foreach ($avatars as $avatar) {
                            $avatar_name = basename($avatar);
                            echo "
                            <div class='avatar-item'>
                                <input type='radio' name='selected_avatar' id='$avatar_name' value='$avatar' class='avatar-input'>
                                <label for='$avatar_name' class='avatar-label'>
                                    <img src='$avatar' alt='Avatar' class='avatar-option'>
                                </label>
                            </div>";
                        }
                        ?>
                    </div>
                    <input type="hidden" name="classroom_id" id="classroom_id" value="<?php echo $classroom_id; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="ChangeWallpaperForm" id="save-profile-picture">Save Selection</button>
            </div>
        </div>
    </div>
</div>