<?php
function successAlert($title)
{
    if (isset($_GET['status'])) : ?>
        <script>
            <?php if ($_GET['status'] == 'save'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'New <?php echo $title; ?>',
                    text: "New <?php echo $title; ?> Added Successfully",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Done'
                });

            <?php elseif ($_GET['status'] == 'save_course'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'New Course ',
                    text: "New Course Added Successfully",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Done'
                });

            <?php elseif ($_GET['status'] == 'delete'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Delete <?php echo $title; ?>',
                    text: "<?php echo $title; ?> Successfully Deleted",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Okay'
                });

            <?php elseif ($_GET['status'] == 'update'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Update <?php echo $title; ?>',
                    text: "<?php echo $title; ?> Successfully Updated",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Okay'
                });
            <?php elseif ($_GET['status'] == 'update_course'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Update Course',
                    text: "  Course Successfully Updated",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Okay'
                });
            <?php elseif ($_GET['status'] == 'login'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Login ',
                    text: "Login Successfully",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Okay'
                });
            <?php elseif ($_GET['status'] == 'change_password'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Change Password',
                    text: "Password Changed Successfully",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Okay'
                });

            <?php elseif ($_GET['status'] == 'payment_success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Donation',
                    text: "Payment Successfully",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Okay'
                });
            <?php elseif ($_GET['status'] == 'incorrect_password'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login ',
                    text: "Unable to login \n\ Check Username or Password",

                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'incorrect_password'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login ',
                    text: "Unable to login \n\ Check Username or Password",

                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'incorrect_old_password'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'User',
                    text: "Incorrect Old Password",

                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });

            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error Occured with <?php echo $title; ?>, try again',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'assigned'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Instructor already has an account!',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'emailexists'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Email already been used by another user!',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'duplicate'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This Lab already exist, avoid duplicates',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'lab_fk_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... can\'t delete',
                    text: 'Lab has been assigned to an instructor ',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'course_fk_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... can\'t delete',
                    text: 'Course has been assigned to an instructor ',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php endif; ?>

            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
        </script>
<?php endif;
}
?>