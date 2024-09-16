<!DOCTYPE html>
<html>
<head>
    <title>Add Camera</title>
</head>
<body>
<div class="container">
    <h1>Add Camera</h1>
    <div class="form-inner pt-4" id="Camera_form_div" style="display: none;">
        <form method="post" action="" id="Camera_form" enctype="multipart/form-data">
            <input type="hidden" id="Camera_id" name="Camera_id" value='["id","nvr_id","channel_id","name","location","latitude","longitude","address","on_off_status","status","created_by","created_on","updated_by","updated_on","deleted_by","deleted_on","is_deleted"]' />
                <div class='form-group'>
        <label for='nvr_id'>Nvr id:</label>
        <input type='text' id='nvr_id' name='nvr_id' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='channel_id'>Channel id:</label>
        <input type='text' id='channel_id' name='channel_id' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='name'>Name:</label>
        <input type='text' id='name' name='name' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='location'>Location:</label>
        <input type='text' id='location' name='location' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='latitude'>Latitude:</label>
        <input type='text' id='latitude' name='latitude' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='longitude'>Longitude:</label>
        <input type='text' id='longitude' name='longitude' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='address'>Address:</label>
        <input type='text' id='address' name='address' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='on_off_status'>On off status:</label>
        <input type='text' id='on_off_status' name='on_off_status' class='form-control'>
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
    $('#nvr_id').val('');
    $('#channel_id').val('');
    $('#name').val('');
    $('#location').val('');
    $('#latitude').val('');
    $('#longitude').val('');
    $('#address').val('');
    $('#on_off_status').val('');
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
    if ($('#nvr_id').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#nvr_id').notify('Please enter Nvr id', {position: 'bottom'});
    }
    if ($('#channel_id').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#channel_id').notify('Please enter Channel id', {position: 'bottom'});
    }
    if ($('#name').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#name').notify('Please enter Name', {position: 'bottom'});
    }
    if ($('#location').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#location').notify('Please enter Location', {position: 'bottom'});
    }
    if ($('#latitude').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#latitude').notify('Please enter Latitude', {position: 'bottom'});
    }
    if ($('#longitude').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#longitude').notify('Please enter Longitude', {position: 'bottom'});
    }
    if ($('#address').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#address').notify('Please enter Address', {position: 'bottom'});
    }
    if ($('#on_off_status').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#on_off_status').notify('Please enter On off status', {position: 'bottom'});
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
        $('#Camera_form').submit(function(event) {
            if (!CheckRequiredValues()) {
                event.preventDefault();
            }
        });
    }); 
</script>
</body>
</html>