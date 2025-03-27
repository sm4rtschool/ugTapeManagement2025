
<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html lang="zxx">

<head>
	<title>Login Tape Management Mandiri Bank</title>
	<!-- Meta-Tags -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<meta name="keywords" content="Particles Login Form Form a Responsive Web Template, Bootstrap Web Templates, Flat Web Templates, Android Compatible Web Template, Smartphone Compatible Web Template, Free Webdesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design">
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!-- //Meta-Tags -->
	<!-- Stylesheets -->
	<link href="<?php echo base_url(); ?>assets/login3/css/style.css" rel='stylesheet' type='text/css' />
	<!-- online fonts-->
	<!--<link href="https://fonts.googleapis.com/css?family=Amiri:400,400i,700,700i" rel="stylesheet">-->
	<link href="<?php echo base_url(); ?>assets/login3/css/css.css" rel='stylesheet' type='text/css' />

	<script language="javascript">
	function validasi(form){
  		if (form.identity.value == ""){
    		alert("Username harus diisi!!");
    		form.identity.focus();
    		return (false);
  		}
     
  		if (form.password.value == ""){
    		alert("Password harus diisi!!");
    		form.password.focus();
    		return (false);
		}

		return (true);

	}
	</script>

</head>

<body>

<!--<script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>-->
<script src='<?php echo base_url(); ?>assets/login3/js/jquery.min.js'></script>
<!--<script src="//m.servedby-buysellads.com/monetization.js" type="text/javascript"></script>-->
<!--<script src="js/monetization.js" type="text/javascript"></script>-->

<script>
(function(){
	if(typeof _bsa !== 'undefined' && _bsa) {
  		// format, zoneKey, segment:value, options
  		_bsa.init('flexbar', 'CKYI627U', 'placement:w3layoutscom');
  	}
})();
</script>

<script>
(function(){
if(typeof _bsa !== 'undefined' && _bsa) {
	// format, zoneKey, segment:value, options
	_bsa.init('fancybar', 'CKYDL2JN', 'placement:demo');
}
})();
</script>

<script>
(function(){
	if(typeof _bsa !== 'undefined' && _bsa) {
  		// format, zoneKey, segment:value, options
  		_bsa.init('stickybox', 'CKYI653J', 'placement:w3layoutscom');
  	}
})();
</script>

<script>
	/*
	(function(v,d,o,ai){ai=d.createElement("script");ai.defer=true;ai.async=true;ai.src=v.location.protocol+o;d.head.appendChild(ai);})(window, document, "//vdo.ai/core/w3layouts/vdo.ai.js");
	*/
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125810435-1"></script>-->

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-125810435-1');
</script>

<script>
/*  
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-30027142-1', 'w3layouts.com');
  ga('send', 'pageview');
*/
</script>

<body>
<!---728x90--->

	<!--  particles  -->
	<div id="particles-js"></div>
	<!-- //particles -->
	<div class="w3ls-pos">

		<h1>Login Tape Management System</h1>

        <div style="text-align: center; margin-bottom: 25px;">
            <img src="<?php echo base_url(); ?>assets/images/logo_login.png" alt="Logo" class="logo-login">
            <style>
                .logo-login {
                    max-width: 100%;
                    height: auto;
                    width: 200px; /* Set a specific width */
                    margin: 0 auto; /* Center the image */
                }
            </style>
        </div>

		<div class="w3ls-login box">

			<!-- form starts here -->

            <?php if (isset($error) and !empty($error)) : ?>
                <div class="callout callout-error" style="color: red;">
                    <h4><?= cclang('error'); ?>!</h4>
                    <p><?= $error; ?></p>
                </div>
            <?php endif; ?>

            <?php
            $message = $this->session->flashdata('f_message');
            $type = $this->session->flashdata('f_type');

            if ($message) :
            ?>
                <div class="callout callout-<?= $type; ?>" style="color: red;">
                    <p><?= $message; ?></p>
                </div>
            <?php endif; ?>

			<!-- <form action="<?php echo site_url();?>login/do_login" method="post" onsubmit="return validasi(this)"> -->

            <?= form_open('', [
                'name'    => 'form_login',
                'id'      => 'form_login',
                'method'  => 'POST'
            ]); ?>

				<div class="agile-field-txt has-feedback <?= form_error('username') ? 'has-error' : ''; ?>">
					<input type="text" name="username" id="identity" placeholder="Isi username..." required="" />
				</div>
				
				<div class="agile-field-txt">
					<input type="password" name="password" placeholder="Isi password..." required="" id="myInput" />
					
                    <div class="agile_label">
						<input id="remember" name="remember" type="checkbox" value="1">
						<label class="check" for="remember">Remember me</label>
					</div>

				</div>

				<div class="w3ls-bot">
					<input type="submit" value="LOGIN">
				</div>

            <?= form_close(); ?>
			<!-- </form> -->

		</div>

		<!---728x90--->

		<!-- //form ends here -->
		<!--copyright-->
		<div class="copy-wthree">
			<p>© 2025 Version 1.0 | <a href="https://www.bankmandiri.co.id/" target="_blank">Bank Mandiri</a>. All Rights Reserved
				<!--<a href="http://w3layouts.com/" target="_blank">W3layouts</a>-->
			</p>
		</div>
		<!--//copyright-->
		<!---728x90--->

	</div>
	<!-- scripts required  for particle effect -->
	<script src='<?php echo base_url(); ?>assets/login3/js/particles.min.js'></script>
	<script src="<?php echo base_url(); ?>assets/login3/js/index.js"></script>
	<!-- //scripts required for particle effect -->
</body>

</html>