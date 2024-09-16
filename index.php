<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Interface</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            background-image: url(bg.avif);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
           
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f2f2f2;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], select, button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            max-width: 20%;
        }

        button:hover {
            background-color: #0056b3;
        }
        
        .text-center{
            text-align: center;
        }
        
    </style>
</head>
<body>

<div class="container">
    <h2>Database Connection Interface</h2>

    <div class="form-group">
        <label for="hostname">Hostname:</label>
        <input type="text" id="hostname" name="hostname">
    </div>

    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="text" id="password" name="password">
    </div>

    <div class="form-group text-center">
        <button id="connect">Connect</button>
    </div>

    <div class="form-group">
        <label for="databases">Databases:</label>
        <select id="databases" name="databases">
            <option value="">Select a database</option>
        </select>
    </div>

    <div class="form-group">
        <label for="tables">Tables:</label>
        <select id="tables" name="tables">
            <option value="">Select a table</option>
        </select>
    </div>

    <div class="form-group">
        <label for="controllerName">Controller Name:</label>
        <input type="text" id="controllerName" name="controllerName">
    </div>

    <div class="form-group">
        <label for="modelName">Model Name:</label>
        <input type="text" id="modelName" name="modelName">
    </div>

    <div class="form-group">
        <label for="viewDirName">View Directory Name:</label>
        <input type="text" id="viewDirName" name="viewDirName">
    </div>

    <div class="form-group text-center">
        <button id="generate">Generate</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#connect').on('click', function() {
            var hostname = $('#hostname').val();
            var username = $('#username').val();
            var password = $('#password').val();

            $.ajax({
                url: 'connect.php',
                type: 'POST',
                data: {
                    hostname: hostname,
                    username: username,
                    password: password
                },
                success: function(response) {
                    var databases = JSON.parse(response);
                    $('#databases').empty().append('<option value="">Select a database</option>');
                    $.each(databases, function(index, db) {
                        $('#databases').append('<option value="' + db + '">' + db + '</option>');
                    });
                }
            });
        });

        $('#databases').on('change', function() {
            var hostname = $('#hostname').val();
            var username = $('#username').val();
            var password = $('#password').val();
            var database = $('#databases').val();

            $.ajax({
                url: 'fetch_tables.php',
                type: 'POST',
                data: {
                    hostname: hostname,
                    username: username,
                    password: password,
                    database: database
                },
                success: function(response) {
                    var tables = JSON.parse(response);
                    $('#tables').empty().append('<option value="">Select a table</option>');
                    $.each(tables, function(index, table) {
                        $('#tables').append('<option value="' + table + '">' + table + '</option>');
                    });
                }
            });
        });
        $('#tables').on('change', function() {
    var tableName = $('#tables').val();
    
    // Check if the table name starts with 'en_' prefix
    var tableNameWithoutPrefix = tableName.startsWith('en_') ? tableName.slice(3) : tableName;

    if (tableNameWithoutPrefix) {
        // Capitalize the first letter of each word in the table name after removing the prefix
        var capitalizedTableName = tableNameWithoutPrefix.split('_').map(function(word) {
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join('');

        // Set the Controller Name
        $('#controllerName').val(capitalizedTableName);

        // Set the Model Name
        $('#modelName').val(capitalizedTableName + 'Model');

        // Set the View Directory Name (lowercase, keeping underscores)
        $('#viewDirName').val(tableNameWithoutPrefix.toLowerCase());
    } else {
        // Clear the input fields if no table name is selected
        $('#controllerName').val('');
        $('#modelName').val('');
        $('#viewDirName').val('');
    }
});


$('#generate').on('click', function() {
            var hostname = $('#hostname').val();
            var username = $('#username').val();
            var password = $('#password').val();
            var database = $('#databases').val();
            var tableName = $('#tables').val();
            var controllerName = $('#controllerName').val();
            var modelName = $('#modelName').val();
            var viewDirName = $('#viewDirName').val();

            $.ajax({
                url: 'file_generate.php',
                type: 'POST',
                data: {
                    hostname: hostname,
                    username: username,
                    password: password,
                    database: database,
                    tableName: tableName,
                    controllerName: controllerName,
                    modelName: modelName,
                    viewDirName: viewDirName
                },
                success: function(response) {
                    alert(response);
                }
            });
        });
    });
</script>

</body>
</html>
