
<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<!--begin::Main-->
<div class="d-flex flex-column flex-root">
	<!--begin::Login-->
	<div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
		<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat">
			<div class="login-form text-center p-7 position-relative overflow-hidden">
				<!--begin::Login Header-->
				<div class="d-flex flex-center mb-15">
					<a href="#">
						<img src="<?php echo RVC_PLUGIN_URL; ?>img/logo.png" class="max-h-75px" alt="" />
					</a>
				</div>
				<!--end::Login Header-->
				<!--begin::Login Sign in form-->
				<div class="login-signin">
					<div class="mb-20">
						<h3>Sign In To Admin</h3>
						<div class="text-muted font-weight-bold">Enter your details to login to your account:</div>
					</div>
					<form class="form" id="kt_login_signin_form">
						<div class="form-group mb-5">
							<input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Email" name="rvuser" autocomplete="off" />
						</div>
						<div class="form-group mb-5">
							<input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password" name="rvpassword" />
						</div>
						<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
							<div class="checkbox-inline">
								<label class="checkbox m-0 text-muted">
								<input type="checkbox" name="remember" />
								<span></span>Remember me</label>
							</div>
						</div>
						<button id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Sign In</button>
					</form>
				</div>
				<!--end::Login Sign in form-->


			</div>
		</div>
	</div>
	<!--end::Login-->
</div>
<!--end::Main-->

<?php RVC()->template_loader->get_template_part( 'footer' ,'rvc',true ); ?>
