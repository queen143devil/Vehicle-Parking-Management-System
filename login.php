<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Admin | Vehicle Parking Management System</title>

    <?php include('./header.php'); ?>
    <?php include('./db_connect.php'); ?>
    <?php 
        session_start();
        if (isset($_SESSION['login_id']))
            header("location:index.php?page=home");
    ?>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        background: #1e293b;
        overflow: hidden;
    }

    main#main {
        width: 100%;
        height: 100%;
        display: flex;
    }

    /* Left panel */
    #login-left {
        width: 60%;
        background: linear-gradient(135deg, #3b5998, #4A70A9);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    /* Logo styling */
    .logo {
        font-size: 7rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.6em 0.8em;
        border-radius: 50%;
        box-shadow: 0 0 20px #00000040;
    }

    /* Right panel */
    #login-right {
        width: 40%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f8fafc;
        position: relative;
    }

    .card {
        width: 80%;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        border-radius: 20px;
    }
</style>

<body>

<main id="main">

    <!-- Left Panel With Logo -->
    <div id="login-left">
        <div class="logo">
            <span class="fa fa-car"></span>
        </div>
    </div>

    <!-- Right Panel With Login -->
    <div id="login-right">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center mb-4">Admin Login</h4>

                <form id="login-form">
                    <div class="form-group mb-3">
                        <label class="control-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control"
                            placeholder="Enter username">
                    </div>

                    <div class="form-group mb-4">
                        <label class="control-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter password">
                    </div>

                    <center>
                        <button type="submit" class="btn btn-primary btn-block col-md-5 py-2">Login</button>
                    </center>
                </form>
            </div>
        </div>
    </div>

</main>

</body>

<script>
    $('#login-form').submit(function (e) {
        e.preventDefault();

        let btn = $('#login-form button[type="submit"]');
        btn.attr('disabled', true).html('Logging in...');

        $('.alert-danger').remove();

        $.ajax({
            url: 'ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.log(err);
                btn.removeAttr('disabled').html('Login');
            },
            success: function (resp) {
                if (resp == 1) {
                    location.href = 'index.php?page=home';
                } else if (resp == 2) {
                    location.href = 'voting.php';
                } else {
                    $('#login-form').prepend(`
                        <div class="alert alert-danger mb-2">
                            Username or password is incorrect.
                        </div>
                    `);
                    btn.removeAttr('disabled').html('Login');
                }
            }
        });
    });
</script>

</html>
