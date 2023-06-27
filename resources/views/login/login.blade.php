<!doctype html>
<html lang="en" class="light-theme">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <link rel="icon" href="/assets/images/Lambang-ITS-2-300x300.png" type="image/png" sizes="16x16">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- loader-->
  <link href="/assets/css/pace.min.css" rel="stylesheet" />
  <script src="/assets/js/pace.min.js"></script>

  <!--plugins-->
  <link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />

  <!-- CSS Files -->
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
  <link href="/assets/css/icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

  <title>Login</title>
</head>

<body>

  <div class="login-bg-overlay au-sign-in-basic"></div>

  <!--start wrapper-->
  <div class="wrapper">
    <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-white p-3">
        <div class="container-fluid">
          <a href="javascript:;"><img src="/assets/images/logo/assessment-logo-black.png" width="140" alt="" /></a>
        </div>
      </nav>
    </header>
    <div class="container">
        <div class="row mt-5">
          <div class="col-xl-4 col-lg-5 col-md-7 mx-auto mt-5">
              <div class="card radius-10">
                {{-- @if (session()->has('error'))
                    <div class="alert alert-dismissible fade show py-2 border-0 border-start border-4 border-danger">
                        <div class="d-flex align-items-center">
                        <div class="fs-3 text-danger"><ion-icon name="close-circle-sharp"></ion-icon>
                        </div>
                        <div class="ms-3">
                            <div class="text-danger">{{ session('error') }}</div>
                        </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> 
                @endif --}}
              <div class="card-body p-4">
                <div class="text-center">
                  <h4>Sign In</h4>
                  <p>Sign In to your account</p>
                </div>
                <form class="form-body row g-3" action="/login" method="POST">
                    @csrf
                  <div class="col-12">
                    <label for="inputUsername" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="inputUsername" name="username" autocomplete="off">
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="col-12">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="inputPassword" name="password">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="col-12 col-lg-12 mt-3">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary">Sign In</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <footer class="footer-login" style="margin-left: 0;">
    <div class="footer-text">
      <p class="my-4">Copyright © 2023 Institut Teknologi Sepuluh Nopember.</p>
    </div>
  </footer>
  <!--end wrapper-->
  <script src="/assets/js/jquery.min.js"></script>
  <script src="/assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>
  <script src="/assets/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script src="/assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @if (session()->has('error'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Username or Password is incorrect!',
        confirmButtonText: 'OK'
      });
    </script>
  @endif
</body>

</html>