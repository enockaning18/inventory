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
            <?php elseif ($_GET['status'] == 'incorrect_password'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login ',
                    text: "Error! Invalid username or password, try again",

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
            <?php elseif ($_GET['status'] == 'exam_status'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo $title; ?> status updated successfully!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Done'
                });
            <?php elseif ($_GET['status'] == 'past_date'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Exams date must be a future date!',
                    confirmButtonColor: '#3085d6',
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
                    confirmButtonText: 'Okay, retry'
                });
            <?php elseif ($_GET['status'] == 'exists'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo $title; ?> already exist, try again',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'not_allowed'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error! No <?php echo $title; ?> can be scheduled on thursdays, try again',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'date_time_exists'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error! <?php echo $title; ?> already exist on the selected date and batch time, try again',
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
            <?php elseif ($_GET['status'] == 'instructor_fk_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... can\'t delete',
                    text: 'Instructor has been assigned to exams/account',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'computer_fk_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... can\'t delete',
                    text: 'Computer has been assigned to an issue',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'user_not_found'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... ',
                    text: ' User not found',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'module_fk_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... can\'t delete',
                    text: 'Module has been assigned to a course ',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Retry'
                });
            <?php elseif ($_GET['status'] == 'course_fk_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... can\'t delete',
                    text: 'Course has been assigned to a lab ',
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