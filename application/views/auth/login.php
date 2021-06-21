<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-4 login-section-wrapper">
          <div class="brand-wrapper">
            <img src="<?php echo base_url("assets/images/logo.png")?>" alt="logo" class="logo">
          </div>
          <div class="login-wrapper my-auto">
            <h2 class="login-title">Log in</h2>
             <div class="message text-danger"><?php echo $message;?></div>
              <?php echo form_open("login");?>
              <div class="form-group">
                <input type="email" name="identity" id="email" class="form-control" placeholder="Email">
              </div>
              <div class="form-group mb-4">
                <input type="password" name="password" id="password" class="form-control" placeholder="Passsword">
              </div>
              <input name="login" id="login" class="btn btn-block login-btn" type="submit" value="Login">
            </form>
            <a href="#forgot" class="forgot-password-link">Forgot password?</a>
            <p class="login-wrapper-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
          </div>
          <footer class="footer">
            <div class="container-fluid">
              <div class="copyright ml-auto">
                2021 <a href="https://www.innovativetoll.com">Innovative Toll Solutions</a>
              </div>        
            </div>
        </footer>
        </div>
        <div class="col-sm-8 px-0 d-none d-sm-block" style="background-image: url(<?php echo base_url("assets/images/login_bg.png")?>);  display: block; background-size: cover; background-repeat: no-repeat; ">
          <div class="maintxt">
                <h3 class="">INNOVATIVE TOLL</h3>
                <span class="overlay-text">Form a relationship with a great toll management solution provider
                                <br> and set yourself for success in the changing Tolling space </span>
            </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>