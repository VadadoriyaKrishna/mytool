


    <div class="form-inner pt-4" id="CokeyBook_form_div" style="display: none;">
        <form method="post" action="" id="CokeyBook_form" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="0" />
            
           
                <div class='form-inner-group row'>
    <div class='col-sm-3'>
        <label class='field-title' for='name'>Name<sub>*</sub>:</label>
        <input type='text' id='name' name='name' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='description'>Description<sub>*</sub>:</label>
        <textarea id='description' name='description' class='form-control'></textarea>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='cover_img'>Cover img<sub>*</sub>:</label>
        <input type='file' id='cover_img' name='cover_img' class='form-control'>
    </div>
        <input type='hidden' id='old_image' name='old_image' value='' />
        <img  class='image_preview' src='' width='200' height='200' style=' display: none;'/>
    <div class='col-sm-3'>
        <label class='field-title' for='price'>Price<sub>*</sub>:</label>
        <input type='text' id='price' name='price' class='form-control'>
    </div>
    </div>
    <div class='form-inner-group row'>
    <div class='col-sm-3'>
        <label class='field-title' for='pages'>Pages<sub>*</sub>:</label>
        <input type='text' id='pages' name='pages' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='per_page_story_count'>Per page story count<sub>*</sub>:</label>
        <input type='text' id='per_page_story_count' name='per_page_story_count' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='story_days_count'>Story days count<sub>*</sub>:</label>
        <input type='text' id='story_days_count' name='story_days_count' class='form-control'>
    </div>
    <div class='col-sm-3'>
        <label class='field-title' for='book_languages'>Book languages<sub>*</sub>:</label>
        <input type='text' id='book_languages' name='book_languages' class='form-control'>
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
            $('#name').val('');
    $('#description').val('');
    $('#cover_img').val('');
    $('#price').val('');
    $('#pages').val('');
    $('#per_page_story_count').val('');
    $('#story_days_count').val('');
    $('#book_languages').val('');
    $('#status').val('');

    }
    function form_data_load(data)
    {
            $('#id').val(data.id);
    $('#name').val(data.name);
    $('#description').val(data.description);
    $('#cover_img').val('');
    $('.image_preview').attr('src', data.cover_img_url).show();
    $('#old_image').val(data.cover_img);
    $('.image_preview').show();
    $('#price').val(data.price);
    $('#pages').val(data.pages);
    $('#per_page_story_count').val(data.per_page_story_count);
    $('#story_days_count').val(data.story_days_count);
    $('#book_languages').val(data.book_languages);
    $('#status').val('0').trigger('change');
    $('.status_div').show();

    }
    $(document).off('click', '.add_new_data').on('click', '.add_new_data', function (e) {
        e.preventDefault();
        if ($(this).hasClass('open_form')) {
            $('#CokeyBook_form_div').css('display', 'none');
            $(this).removeClass('open_form');
            $(this).text('Add CokeyBook');
            form_reset();
        } else {
            $('#CokeyBook_form_div').css('display', 'block');
            $(this).addClass('open_form');
            $(this).text('Hide CokeyBook');
            form_reset();
        }
    });
      $('#cover_img').change(function() { 
      readURL(this); })


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

            if ($('#name').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#name').notify('Please enter Name', {position: 'bottom'});
    }
    if ($('#description').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#description').notify('Please enter Description', {position: 'bottom'});
    }
    if ($('#cover_img').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#cover_img').notify('Please enter Cover img', {position: 'bottom'});
    }
    if ($('#price').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#price').notify('Please enter Price', {position: 'bottom'});
    }
    if ($('#pages').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#pages').notify('Please enter Pages', {position: 'bottom'});
    }
    if ($('#per_page_story_count').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#per_page_story_count').notify('Please enter Per page story count', {position: 'bottom'});
    }
    if ($('#story_days_count').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#story_days_count').notify('Please enter Story days count', {position: 'bottom'});
    }
    if ($('#book_languages').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#book_languages').notify('Please enter Book languages', {position: 'bottom'});
    }
    if ($('#status').val() == '') {
        is_valid = false;
        countOptionError++;
        $('#status').notify('Please enter Status', {position: 'bottom'});
    }


        return is_valid;
    }


  
        $('#CokeyBook_form').submit(function(e) {

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

        formData.append("action", "add_cokey_book");

        $.ajax({
            type: 'POST',
            url: 'fluvina_index.php?broughtBy=CokeyBook',
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
                    notify('Successfully', 'CokeyBook data updated successfully.', 'success');

                    $(".add_new_data").trigger('click');
                    table.ajax.reload();
                }
            }
        });
        return false;
            
        });

        
      

</script>
