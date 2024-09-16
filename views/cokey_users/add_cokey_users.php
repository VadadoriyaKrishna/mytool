


    <div class="form-inner pt-4" id="CokeyUsers_form_div" style="display: none;">
        <form method="post" action="" id="CokeyUsers_form" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="0" />
            
           
                <div class='form-inner-group row'>
    <div class='col-sm-3'>
        <label class='field-title' for='first_name'>First name<sub>*</sub>:</label>
        <input type='text' id='first_name' name='first_name' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='address'>Address<sub>*</sub>:</label>
        <textarea id='address' name='address' class='form-control'></textarea>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='last_name'>Last name<sub>*</sub>:</label>
        <input type='text' id='last_name' name='last_name' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='age'>Age<sub>*</sub>:</label>
        <input type='text' id='age' name='age' class='form-control'>
    </div>
    </div>
    <div class='form-inner-group row'>
    <div class='col-sm-3'>
        <label class='field-title' for='date_of_birth'>Date of birth<sub>*</sub>:</label>
        <input type='text' id='date_of_birth' name='date_of_birth' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='password'>Password<sub>*</sub>:</label>
        <input type='password' id='password' name='password' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='mobile_number'>Mobile number<sub>*</sub>:</label>
        <input type='text' id='mobile_number' name='mobile_number' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title'>Gender<sub>*</sub>:</label>
        <div class='form-check'>
            <input type='radio' id='gender_male' name='gender' value='male' class='form-check-input'>
            <label for='gender_male' class='form-check-label'>male</label>
        </div>
        <div class='form-check'>
            <input type='radio' id='gender_female' name='gender' value='female' class='form-check-input'>
            <label for='gender_female' class='form-check-label'>female</label>
        </div>
    </div>
    </div>
    <div class='form-inner-group row'>
    </div>

            <div class="form-inner-group row">
            <div class="col-md-3 status_div"  style=" display: none;">
                <label class="field-title">Status<sub>*</sub></label>
                <select name="status" id="status" class="selectpicker form-control">
                    <option value="0">Inactive</option>
                    <option value="1">Active</option>
                </select> 
            </div>
            <!--div class="col-sm-3" >
                <img  class="image_preview" src="" width='200' height="200" style=" display: none;"/>
            </div-->
            </div>

            <div class="form-inner-group row ">			
                <div class="col-md-5" ></div>
                <div class="col-md-4" >
                    <button type="button" class="btn btn-light red" onclick="form_reset();" >Clear</button>
                    <button type="submit" class="btn btn-light ladda-button blue"  id="submit-btn" data-style="expand-right" data-size="l">
                        <i class="fa fa-save"></i> Save 
                    </button>
                </div>
            </div>
        </form>
    </div>
<script>
     table = '';


    $('.selectpicker').selectpicker();

   function form_reset() {
            $('#first_name').val('');
    $('#address').val('');
    $('#last_name').val('');
    $('#age').val('');
    $('#date_of_birth').val('');
    $('#password').val('');
    $('#mobile_number').val('');
    $('#gender').val('');
    $('#status').val('');

    }
    function form_data_load(data)
    {
            $('#id').val(data.id);
    $('#first_name').val(data.first_name);
    $('#address').val(data.address);
    $('#last_name').val(data.last_name);
    $('#age').val(data.age);
    $('#date_of_birth').val(data.date_of_birth);
    $('#password').val(data.password);
    $('#mobile_number').val(data.mobile_number);
    $('#gender').val(data.gender);
    $('#status').val('0').trigger('change');
    $('.status_div').show();

    }
    $(document).off('click', '.add_new_data').on('click', '.add_new_data', function (e) {
        e.preventDefault();
        if ($(this).hasClass('open_form')) {
            $('#CokeyUsers_form_div').css('display', 'none');
            $(this).removeClass('open_form');
            $(this).text('Add CokeyUsers');
            form_reset();
        } else {
            $('#CokeyUsers_form_div').css('display', 'block');
            $(this).addClass('open_form');
            $(this).text('Hide CokeyUsers');
            form_reset();
        }
    });
    

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.image_preview').attr('src', e.target.result).css('display', 'block');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function CheckRequiredValues() {
        var is_valid = true;
        var countOptionError = 0;

            if ($('#first_name').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#first_name').notify('Please enter First name', {position: 'bottom'});
    }
    if ($('#address').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#address').notify('Please enter Address', {position: 'bottom'});
    }
    if ($('#last_name').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#last_name').notify('Please enter Last name', {position: 'bottom'});
    }
    if ($('#age').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#age').notify('Please enter Age', {position: 'bottom'});
    }
    if ($('#date_of_birth').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#date_of_birth').notify('Please enter Date of birth', {position: 'bottom'});
    }
    if ($('#password').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#password').notify('Please enter Password', {position: 'bottom'});
    }
    if ($('#mobile_number').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#mobile_number').notify('Please enter Mobile number', {position: 'bottom'});
    }
    if ($('#gender').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#gender').notify('Please enter Gender', {position: 'bottom'});
    }
    if ($('#status').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#status').notify('Please enter Status', {position: 'bottom'});
    }


        return is_valid;
    }


  
        $('#CokeyUsers_form').submit(function(e) {

        var l = Ladda.create($('#submit-btn')[0]);
        l.start();

        SetContentPublishApp();
            if (!CheckRequiredValues()) {
                //event.preventDefault();
                l.stop();



                return false;
            }
            e.preventDefault();
        var formData = new FormData(this);

        formData.append("action", "add_cokey_users");

        $.ajax({
            type: 'POST',
            url: 'fluvina_index.php?broughtBy=CokeyUsers',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                l.stop();
                if (response.status == false) {
                    if (response.msg != null && response.msg != '') {
                        notify('Error', response.msg, 'error');
                    } else {
                        notify('Error', 'Please enter valid data.', 'error');
                    }
                } else {
                    notify('Successfully', 'CokeyUsers data updated successfully.', 'success');

                    $(".add_new_data").trigger('click');
                    table.ajax.reload();
                }
            }
        });
        return false;
            
        });

        
      

</script>
