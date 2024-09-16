<!DOCTYPE html>
<html>
<head>
    <title>Add Cities</title>
</head>
<body>
<div class="container">
    <h1>Add Cities</h1>
    <div class="form-inner pt-4" id="Cities_form_div" style="display: none;">
        <form method="post" action="" id="Cities_form" enctype="multipart/form-data">
            <input type="hidden" id="Cities_id" name="Cities_id" value='["id","name","state_id","status","created_by","created_date","modified_by","modified_date","deleted_by","deleted_date","is_deleted"]' />
                <div class='form-group'>
        <label for='name'>Name:</label>
        <input type='text' id='name' name='name' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='state_id'>State id:</label>
        <input type='text' id='state_id' name='state_id' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='created_date'>Created date:</label>
        <input type='datetime-local' id='created_date' name='created_date' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='modified_by'>Modified by:</label>
        <input type='text' id='modified_by' name='modified_by' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='modified_date'>Modified date:</label>
        <input type='datetime-local' id='modified_date' name='modified_date' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='deleted_by'>Deleted by:</label>
        <input type='text' id='deleted_by' name='deleted_by' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='deleted_date'>Deleted date:</label>
        <input type='datetime-local' id='deleted_date' name='deleted_date' class='form-control'>
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
    $('#name').val('');
    $('#state_id').val('');
    $('#status').val('');
    $('#created_by').val('');
    $('#created_date').val('');
    $('#modified_by').val('');
    $('#modified_date').val('');
    $('#deleted_by').val('');
    $('#deleted_date').val('');
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
    if ($('#name').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#name').notify('Please enter Name', {position: 'bottom'});
    }
    if ($('#state_id').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#state_id').notify('Please enter State id', {position: 'bottom'});
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
    if ($('#created_date').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#created_date').notify('Please enter Created date', {position: 'bottom'});
    }
    if ($('#modified_by').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#modified_by').notify('Please enter Modified by', {position: 'bottom'});
    }
    if ($('#modified_date').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#modified_date').notify('Please enter Modified date', {position: 'bottom'});
    }
    if ($('#deleted_by').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#deleted_by').notify('Please enter Deleted by', {position: 'bottom'});
    }
    if ($('#deleted_date').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#deleted_date').notify('Please enter Deleted date', {position: 'bottom'});
    }
    if ($('#is_deleted').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#is_deleted').notify('Please enter Is deleted', {position: 'bottom'});
    }


        return is_valid;
    }

    $(document).ready(function() {
        $('#Cities_form').submit(function(event) {
            if (!CheckRequiredValues()) {
                event.preventDefault();
            }
        });
    }); 
</script>
</body>
</html>