<header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>OMD</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>PayRoll</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo (!empty($user['photo'])) ? '../images/'.$user['photo'] : '../images/profile.jpg'; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $user['firstname'].' '.$user['lastname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo (!empty($user['photo'])) ? '../images/'.$user['photo'] : '../images/profile.jpg'; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $user['firstname'].' '.$user['lastname']; ?>
                  <small>Member since <?php echo date('M. Y', strtotime($user['created_on'])); ?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#profile" data-toggle="modal" class="btn btn-secondary btn-sm btn-flat" style='background-color: #ffd9b3;' id="admin_profile"><b style='color: Black'><strong>Update</strong></a>
                </div>
                <div class="pull-left">
                  <a href="logout.php" class="btn btn-secondary btn-sm btn-flat" style='background-color: #CCFFCC;' ><b style='color: Black'><strong>Sign out</strong></a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <?php include 'includes/profile_modal.php'; ?>
