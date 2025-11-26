<style>
    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background: #ffffff;
        padding: 0.5rem 0.7rem;
        border-radius: 50%;
        color: #2C3E50;
        font-weight: bold;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .navbar-brand-text {
        font-weight: bold;
        font-size: 1.15rem;
        color: #ffffff;
    }

    .navbar-custom {
        min-height: 3.8rem;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .nav-link.text-white {
        font-weight: 500;
        transition: all 0.3s;
    }

    .nav-link.text-white:hover {
        color: #f1c40f;
    }

    /* Optional: add subtle hover effect on the navbar brand */
    .navbar-brand:hover .navbar-brand-text {
        color: #f1c40f;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <div class="logo me-2"><i class="fa fa-car"></i></div>
      <span class="navbar-brand-text">Vehicle Parking Management System</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-white" href="ajax.php?action=logout">
            <?php echo $_SESSION['login_name'] ?> <i class="fa fa-power-off"></i>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
