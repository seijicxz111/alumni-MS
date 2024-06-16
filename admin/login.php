<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
  $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
  foreach($system as $k => $v){
    $_SESSION['system'][$k] = $v;
  }
}
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $_SESSION['system']['name'] ?></title>
  <?php include('./header.php'); ?>
  <?php 
  if(isset($_SESSION['login_id']))
  header("location:index.php?page=home");
  ?>
  <style>
    main#main {
      width: 100%;
      height: 100vh;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: flex-end; 
    }
    main#main::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>) no-repeat center center fixed;
      background-size: cover;
      filter: blur(5px); 
      z-index: 0;
    }
    #login-right {
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      z-index: 1; 
      height: 100%; 
      width: 50%; 
    }
    .card {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      padding: 20px;
      width: 100%;
      max-width: 500px;
    }
  </style>
</head>

<body>
  <main id="main">
    <div id="login-right">
      <div class="card">
        <div class="card-body">
          <form id="login-form">
            <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="text" id="username" name="username" class="form-control">
            </div>
            <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <input type="password" id="password" name="password" class="form-control">
            </div>
            <center><button class="btn btn-primary btn-block">Login</button></center>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $('#login-form').submit(function(e){
      e.preventDefault();
      $('#login-form button').attr('disabled', true).text('Logging in...');
      if($(this).find('.alert-danger').length > 0)
        $(this).find('.alert-danger').remove();
      $.ajax({
        url: 'ajax.php?action=login',
        method: 'POST',
        data: $(this).serialize(),
        error: function(err){
          console.log(err);
          $('#login-form button').removeAttr('disabled').text('Login');
        },
        success: function(resp){
          if(resp == 1){
            location.href = 'index.php?page=home';
          } else {
            $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
            $('#login-form button').removeAttr('disabled').text('Login');
          }
        }
      });
    });
  </script>
</body>
</html>