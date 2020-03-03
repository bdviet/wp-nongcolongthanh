<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<b>Be sure to backup your database before use this plugin !</b>
<form action="" method="post" id="search-and-replace">
	<?php wp_nonce_field( 'search_replace' ) ?>
	<label for="s">Search :</label><input type="text" name="s" id="s" /><br />
	<label for="r">Replace by :</label><input type="text" name="r" id="r" /> (replace both title and content)<br />
	<label for="in">In : </label>
	<input type="checkbox" value="post" name="post" /> Posts 
	<input type="checkbox" value="page" name="page" /> Pages<br />
	<input type="submit" value="Go !" />
</form>

<p>
	<a href="http://www.info-d-74.com/produit/search-and-replace-pro-plugin-wordpress/" target="_blank">
		Need more options ? Look at Search and Replace Pro <a href="https://www.facebook.com/infod74/" target="_blank"><img src="<?php echo plugins_url( 'images/fb.png', dirname(__FILE__)) ?>" alt="" /></a><br />
		<img src="<?= plugins_url( 'search-replace/images/search-and-replace-pro.png' ); ?>" />
	</a>
</p>

<script>

	jQuery(document).ready(function(){

		jQuery('#search-and-replace input[type=submit]').click(function(){

			return confirm('Are you sure to do that?');

		});

	});

</script>