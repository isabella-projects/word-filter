<?php
$submenu_url = admin_url('admin.php?page=wordfilter-options');
?>

<div class="wrap">
    <h1>Word Filter</h1>
    <?php if ($_POST['submitted'] ?? null == 'true') $this->handleForm(); ?>
    <form method="POST">
        <input type="hidden" name="submitted" value="true">
        <?php wp_nonce_field('saveFilterWords', 'ourNonce'); ?>
        <label for="words-filter">
            <p>Enter a <strong>comma-separated</strong> list of words to filter from your site's content.</p>
        </label>
        <p>If you want to configure what text should be replaced for the bad words, go to <a class="options" href="<?php echo $submenu_url ?>">Options</a>.</p>
        <div class="word-filter__flex-container">
            <textarea name="words-filter" id="words-filter" placeholder="bad, mean, awful, horrible"><?php echo esc_textarea(get_option('words_to_filter')); ?></textarea>
        </div>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    </form>
</div>