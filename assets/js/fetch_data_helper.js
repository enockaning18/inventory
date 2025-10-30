$(document).ready(function() {
    // Get the initially selected module (for edit mode)
    let initialModuleId = $('#module').data('selected') || '';

    // When course or semester dropdown changes
    function loadModules() {
        let courseId = $('#course').val();
        let semester = $('#batch_semester').val(); // read from batch_semester dropdown

        if(courseId !== '' && semester !== '') {
            $.ajax({
                url: 'actions/fetch_modules.php',
                type: 'POST',
                data: { 
                    course_id: courseId, 
                    semester: semester 
                },
                success: function(data) {
                    $('#module').html(data);

                    // If editing, set the previously selected module
                    if(initialModuleId) {
                        $('#module').val(initialModuleId);
                        initialModuleId = ''; // reset after applying
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#module').html('<option value="">Select Module</option>');
                }
            });
        } else {
            $('#module').html('<option value="">Select Module</option>');
        }
    }

    $('#course, #batch_semester').change(loadModules);

    // Trigger change on page load to populate modules if editing
    if($('#course').val() !== '' && $('#batch_semester').val() !== '') {
        loadModules();
    }
});
