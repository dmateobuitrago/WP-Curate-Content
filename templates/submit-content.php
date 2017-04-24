<?php get_header();

/*
Template Name: Content submission
*/

$user_id = get_current_user_id();
?>

<?php if ( is_user_logged_in() ): ?>
    <div>Aquí puedes guardar tu contenido:</div>

    <form action="">
        <input type="text" name="content-url" id="content-url-input" placeholder="Pega la url aquí">
    </form>
    <a id="preview-content" href="javascript:void(0);">Previsualizar</a>

    <div id="show-content">
    </div>
<?php  else: ?>
    <div>Entra a tu cuenta o registrate para poder guardar tu contenido.</div>
<?php  endif; ?>
	
<?php get_footer(); ?>