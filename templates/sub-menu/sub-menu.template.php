<div class="wrap">
    <h1>Word Filter Options</h1>
    <form action="options.php" method="POST">
        <?php
        settings_errors();
        settings_fields('replacementFields');
        do_settings_sections('word-filter-options');
        submit_button();
        ?>
    </form>

</div>