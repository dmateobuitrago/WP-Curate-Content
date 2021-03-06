<?php get_header();

/*
Template Name: Content submission
*/

$user_id = get_current_user_id();
?>
<div id="loader">
    <div id="loader-spinner">
    </div>
</div>
<?php if(isset($_GET['comunidad_id'])): ?>
    <form action="">
        <input type="hidden" name="parent_comunidad" id="parent_comunidad" value="<?php echo $_GET['comunidad_id']; ?>">
    </form>
<?php endif; ?>
<div class="gp-container">
    <?php if ( is_user_logged_in() ): ?>
        <form action="" class="preview_curated_content_url" id="preview_curated_content_url">
            <div>Aquí puedes guardar tu contenido:</div>
            <input type="text" name="content-url" id="content-url-input" placeholder="Pega la url aquí">
            <input type="submit" id="preview-content" value="Previsualizar">
        </form>

        <div id="show-content">
        </div>
    <?php  else: ?>
        <div>Entra a tu cuenta o registrate para poder guardar tu contenido.</div>
    <?php  endif; ?>
</div>	
<?php get_footer(); ?>