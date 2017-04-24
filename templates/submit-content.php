<?php get_header();

/*
Template Name: Content submission
*/

$user_id = get_current_user_id();
?>

<?php if ( is_user_logged_in() ): ?>
    <div>Bienvenido usuario número <?php echo $user_id; ?></div>
<?php  else: ?>
    <div>Entra a tu cuenta o registrate para poder guardar tu contenido.</div>
<?php  endif; ?>
	
<?php get_footer(); ?>