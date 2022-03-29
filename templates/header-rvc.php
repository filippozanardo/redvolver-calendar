<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->

    <?php wp_head(); ?>
    <script>
      var ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>";
    </script>


</head>
<body <?php body_class(); ?>>
  <?php RVC()->template_loader->get_template_part( 'page' ,'loader',true ); ?>

  <?php if (is_user_logged_in()) { ?>

    <?php RVC()->template_loader->get_template_part( 'header/header' ,'mobile',true ); ?>

    <!--begin::Main-->

		<div class="d-flex flex-column flex-root">

			<!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">

				<?php RVC()->template_loader->get_template_part( 'header/header' ,'aside',true ); ?>

				<!--begin::Wrapper-->
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

					<?php RVC()->template_loader->get_template_part( 'header/header' ,'menu',true ); ?>

					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

						<!--[html-partial:include:{"file":"partials/_subheader/subheader-v7.html"}]/-->



<?php } ?>
