<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Vehicle Parking Management System</title>

  <?php
    session_start();
    if (!isset($_SESSION['login_id'])) {
      header('location:login.php');
      exit();
    }
    include('./header.php');
  ?>
</head>

<style>
  body {
    background: #8FABD4;
  }

  /* Modal Sizes */
  .modal-dialog.large {
    width: 80% !important;
    max-width: unset;
  }

  .modal-dialog.mid-large {
    width: 50% !important;
    max-width: unset;
  }

  /* Viewer Modal */
  #viewer_modal .btn-close {
    position: absolute;
    right: -4rem;
    top: 0;
    font-size: 27px;
    color: white;
    background: none;
    border: none;
    z-index: 9999;
  }

  #viewer_modal .modal-dialog {
    width: 80%;
    height: 90%;
    max-width: unset;
  }

  #viewer_modal .modal-content {
    background: #000;
    border: none;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #viewer_modal img,
  #viewer_modal video {
    max-width: 100%;
    max-height: 100%;
  }
</style>

<body>

  <?php include 'topbar.php' ?>
  <?php include 'navbar.php' ?>

  <!-- Toast Notification -->
  <div class="toast" id="alert_toast" role="alert">
    <div class="toast-body text-white"></div>
  </div>

  <!-- Page Content -->
  <main id="view-panel">
    <?php 
      $page = isset($_GET['page']) ? $_GET['page'] : 'home';
      include $page . '.php';
    ?>
  </main>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Back to top -->
  <a href="#" class="back-to-top">
    <i class="icofont-simple-up"></i>
  </a>

  <!-- Confirm Modal -->
  <div class="modal fade" id="confirm_modal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
        </div>
        <div class="modal-body">
          <div id="delete_content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="confirm">Continue</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Universal Modal -->
  <div class="modal fade" id="uni_modal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button class="btn btn-primary" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Image/Video Viewer Modal -->
  <div class="modal fade" id="viewer_modal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <button class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
      </div>
    </div>
  </div>

</body>

<script>
  // Loader Controls
  window.start_load = function () {
    $('body').prepend('<div id="preloader2"></div>');
  }

  window.end_load = function () {
    $('#preloader2').fadeOut('fast', function () {
      $(this).remove();
    });
  }

  // Viewer Modal (Image/Video)
  window.viewer_modal = function ($src = '') {
    start_load();
    let extension = $src.split('.').pop().toLowerCase();
    let view = (extension === 'mp4')
      ? $("<video src='assets/uploads/" + $src + "' controls autoplay></video>")
      : $("<img src='assets/uploads/" + $src + "' />");

    $('#viewer_modal .modal-content video, #viewer_modal .modal-content img').remove();
    $('#viewer_modal .modal-content').append(view);

    $('#viewer_modal').modal({
      show: true,
      backdrop: 'static',
      keyboard: false
    });

    end_load();
  }

  // Universal Modal Loader
  window.uni_modal = function ($title = '', $url = '', $size = '') {
    start_load();
    $.ajax({
      url: $url,
      error: () => alert("An error occurred"),
      success: function (resp) {
        $('#uni_modal .modal-title').html($title);
        $('#uni_modal .modal-body').html(resp);

        let dialog = $('#uni_modal .modal-dialog');
        dialog.removeClass().addClass("modal-dialog " + ($size || "modal-md"));

        $('#uni_modal').modal({
          show: true,
          backdrop: 'static',
          keyboard: false
        });

        end_load();
      }
    });
  }

  // Confirm Modal
  window._conf = function ($msg = '', $func = '', $params = []) {
    $('#confirm_modal #confirm').attr('onclick', `${$func}(${ $params.join(',') })`);
    $('#confirm_modal .modal-body').html($msg);
    $('#confirm_modal').modal('show');
  }

  // Toast Alerts
  window.alert_toast = function ($msg = 'TEST', $bg = 'success') {
    $('#alert_toast')
      .removeClass('bg-success bg-danger bg-info bg-warning')
      .addClass('bg-' + $bg);

    $('#alert_toast .toast-body').html($msg);
    $('#alert_toast').toast({ delay: 3000 }).toast('show');
  }

  // On Page Load
  $(document).ready(function () {
    $('#preloader').fadeOut('fast', function () {
      $(this).remove();
    });
  });

  // Plugins
  $('.datetimepicker').datetimepicker({
    format: 'Y/m/d H:i',
    startDate: '+3d'
  });

  $('.select2').select2({
    placeholder: "Please select here",
    width: "100%"
  });
</script>

</html>
