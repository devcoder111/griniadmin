<header class="header header-sticky mb-4">
        <div class="container-fluid">
          <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <svg class="icon icon-lg">
              <use xlink:href="assets/coreui/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
            </svg>
          </button><a class="header-brand d-md-none" href="#">
            <svg width="118" height="46" alt="CoreUI Logo">
              <use xlink:href="coreui.svg"></use>
            </svg></a>
          <ul class="header-nav d-none d-md-flex">
            <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
            
          </ul>
          <ul class="header-nav ms-auto">
            <li class="nav-item"></li>
            <li class="nav-item"></li>
            <li class="nav-item"></li>
            
          </ul>
          <ul class="header-nav ms-3">
            <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-md"><img class="avatar-img" src="assets/coreui/img/avatars/user-dummy-pic.png" alt="Admin"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="dropdown-header bg-light py-2">
                  <div class="fw-semibold">Shopify</div>
                </div>

                <a class="dropdown-item" href="fetch-new-update-order" target="_blank">
                  <svg class="icon me-2">
                    <use xlink:href="assets/coreui/vendors/@coreui/icons/svg/free.svg#cil-bell"></use>
                  </svg>Fetch Orders</a>


                  <a class="dropdown-item" href="fetch-new-update-product" target="_blank">
                  <svg class="icon me-2">
                    <use xlink:href="assets/coreui/vendors/@coreui/icons/svg/free.svg#cil-file"></use></svg>
                  Fetch Products</a>

          
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                  <a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="assets/coreui/vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                  </svg> Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </header>