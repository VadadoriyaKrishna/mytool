$(document).ready(function() {
    $('{$this->controllerName}_formd').submit(function(event) {
        var is_valid = true;
        var countOptionError = 0;

        // Get column names from the hidden input field
        var columnNames = JSON.parse($('#column_names').val());

        // Loop through each column name and validate its corresponding input
        columnNames.forEach(function(columnName) {
            var input = $('#' + columnName);
            var inputValue = input.val().trim();

            if (input.length > 0) { // Check if the input element exists
                if (inputValue == "") {
                    is_valid = false;
                    countOptionError++;
                    input.notify("Please enter " + columnName.replace('_', ' '), {position: "bottom"});
                }

                // Additional validation rules can be added here
                // Example: Check if the input is a number
                // if (input.attr('type') === 'number' && isNaN(inputValue)) {
                //     is_valid = false;
                //     countOptionError++;
                //     input.notify("Please enter a valid number for " + columnName.replace('_', ' '), {position: "bottom"});
                // }
            }
        });

        // Prevent form submission if validation fails
        if (!is_valid) {
            event.preventDefault();
        }
    });
});
