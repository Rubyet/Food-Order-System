<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <style>
        body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: black;
        }

        * {
        box-sizing: border-box;
        }

        /* Add padding to containers */
        .container {
        padding: 16px;
        background-color: white;
        }

        /* Full-width input fields */
        input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
        }

        input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
        }

        /* Overwrite default styles of hr */
        hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
        }

        /* Set a style for the submit button */
        .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
        }

        .registerbtn:hover {
        opacity: 1;
        }

        /* Add a blue text color to links */
        a {
        color: dodgerblue;
        }

        /* Set a grey background color and center the text of the "sign in" section */
        .signin {
        background-color: #f1f1f1;
        text-align: center;
        }
    </style>
    <form action="user.php" method="POST">
        <div class="container">
            <h1>Register</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>

            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" id="username" required>

            <label for="number"><b>Number</b></label>
            <input type="text" placeholder="Enter Phone No" name="number" id="number" required>

            <label for="password_1"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password_1" id="password_1" required>

            <label for="password_2"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password" name="password_2" id="password_2" required>
            <hr>
            <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

            <button type="submit" name="reg_user" class="registerbtn">Register</button>
        </div>
        
        <div class="container signin">
            <p>Already have an account? <a href="#">Sign in</a>.</p>
        </div>
    </form>
</body>
</html>