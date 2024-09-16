<!DOCTYPE html>
<html>
<head>
    <title>Add JunctionVideos</title>
</head>
<body>
<div class="container">
    <h1>Add JunctionVideos</h1>
    <div class="form-inner pt-4" id="JunctionVideos_form_div" style="display: none;">
        <form method="post" action="" id="JunctionVideos_form" enctype="multipart/form-data">
            <input type="hidden" id="JunctionVideos_id" name="JunctionVideos_id" value='["id","name","filename"]' />
                <div class='form-group'>
        <label for='name'>Name:</label>
        <input type='text' id='name' name='name' class='form-control'>
    </div>
    <div class='form-group'>
        <label for='filename'>Filename:</label>
        <input type='text' id='filename' name='filename' class='form-control'>
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
    $('#filename').val('');

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
    if ($('#filename').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#filename').notify('Please enter Filename', {position: 'bottom'});
    }


        return is_valid;
    }

    $(document).ready(function() {
        $('#JunctionVideos_form').submit(function(event) {
            if (!CheckRequiredValues()) {
                event.preventDefault();
            }
        });
    }); 
</script>
</body>
</html>