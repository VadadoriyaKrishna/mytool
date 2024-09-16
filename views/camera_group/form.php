<!DOCTYPE html>
<html>
<head>
    <title>Add CameraGroup</title>
</head>
<body>
<div class="container">
    <h1>Add CameraGroup</h1>
    <div class="form-inner pt-4" id="CameraGroup_form_div" style="display: none;">
        <form method="post" action="" id="CameraGroup_form" enctype="multipart/form-data">
            <input type="hidden" id="CameraGroup_id" name="CameraGroup_id" value='["id","nvr_camera_json","name","status","created_by","created_on","updated_by","updated_on","deleted_by","deleted_on","is_deleted"]' />
                <div class='form-group'>
        <label for='nvr_camera_json'>Nvr camera json:</label>
        <textarea id='nvr_camera_json' name='nvr_camera_json' class='form-control'></textarea>
    </div>
    <div class='form-group'>
        <label for='name'>Name:</label>
        <input type='text' id='name' name='name' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='deleted_by'>Deleted by:</label>
        <input type='text' id='deleted_by' name='deleted_by' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='deleted_on'>Deleted on:</label>
        <input type='datetime-local' id='deleted_on' name='deleted_on' class='form-control'>
    </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
<script>
   function form_reset() {
            $('#id').val('');
    $('#nvr_camera_json').val('');
    $('#name').val('');
    $('#status').val('');
    $('#created_by').val('');
    $('#created_on').val('');
    $('#updated_by').val('');
    $('#updated_on').val('');
    $('#deleted_by').val('');
    $('#deleted_on').val('');
    $('#is_deleted').val('');

    }

    function CheckRequiredValues() {
        var is_valid = true;
        var countOptionError = 0;

            if ($('#id').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#id').notify('Please enter Id', {position: 'bottom'});
    }
    if ($('#nvr_camera_json').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#nvr_camera_json').notify('Please enter Nvr camera json', {position: 'bottom'});
    }
    if ($('#name').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#name').notify('Please enter Name', {position: 'bottom'});
    }
    if ($('#status').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#status').notify('Please enter Status', {position: 'bottom'});
    }
    if ($('#created_by').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#created_by').notify('Please enter Created by', {position: 'bottom'});
    }
    if ($('#created_on').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#created_on').notify('Please enter Created on', {position: 'bottom'});
    }
    if ($('#updated_by').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#updated_by').notify('Please enter Updated by', {position: 'bottom'});
    }
    if ($('#updated_on').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#updated_on').notify('Please enter Updated on', {position: 'bottom'});
    }
    if ($('#deleted_by').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#deleted_by').notify('Please enter Deleted by', {position: 'bottom'});
    }
    if ($('#deleted_on').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#deleted_on').notify('Please enter Deleted on', {position: 'bottom'});
    }
    if ($('#is_deleted').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#is_deleted').notify('Please enter Is deleted', {position: 'bottom'});
    }


        return is_valid;
    }

    $(document).ready(function() {
        $('#CameraGroup_form').submit(function(event) {
            if (!CheckRequiredValues()) {
                event.preventDefault();
            }
        });
    }); 
</script>
</body>
</html>