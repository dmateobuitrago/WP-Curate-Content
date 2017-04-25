<?php get_header();

/*
Template Name: Content submission
*/

$user_id = get_current_user_id();
?>
<div class="gp-container">
    <?php if ( is_user_logged_in() ): ?>
        <form action="" class="preview_curated_content_url">
            <div>Aquí puedes guardar tu contenido:</div>
            <input type="text" name="content-url" id="content-url-input" placeholder="Pega la url aquí">
            <a id="preview-content" href="javascript:void(0);">Previsualizar</a>
        </form>

        <div id="show-content">
        </div>
    <?php  else: ?>
        <div>Entra a tu cuenta o registrate para poder guardar tu contenido.</div>
    <?php  endif; ?>
</div>	
<?php get_footer(); ?>