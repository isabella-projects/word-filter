<?php

/*
    Plugin Name: Word Filter
    Plugin URI: https://github.com/isabella-projects/word-filter
    Description: Replaces a list of words given in a comma separated order
    Version: 1.0
    Author: D. Minkov
    Author URI: https://github.com/isabella-projects
*/

if (!defined('ABSPATH'))
    exit;

class WordFilter
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'filterMenu']);
        add_action('admin_init', [$this, 'settings']);

        if (get_option('words_to_filter')) {
            add_filter('the_content', [$this, 'filterLogic']);
        }
    }

    public function filterMenu()
    {
        $svg_content = include 'assets/svg_content.php';

        $mainPage = add_menu_page(
            'Words To Filter',
            'Word Filter',
            'manage_options',
            'wordfilter',
            [
                $this,
                'wordFilterPage'
            ],
            'data:image/svg+xml;base64,' . base64_encode($svg_content),
            100
        );

        add_submenu_page(
            'wordfilter',
            'Words To Filter',
            'Words List',
            'manage_options',
            'wordfilter',
            [
                $this,
                'wordFilterPage'
            ]
        );

        add_submenu_page(
            'wordfilter',
            'Word Filter Options',
            'Options',
            'manage_options',
            'wordfilter-options',
            [
                $this,
                'wordFilterSubPage'
            ]
        );

        add_action(
            "load-{$mainPage}",
            [
                $this,
                'mainPageAssets'
            ]
        );
    }

    public function wordFilterPage()
    {
        require('templates/main-menu/main-menu.template.php');
    }

    public function wordFilterSubPage()
    {
        require('templates/sub-menu/sub-menu.template.php');
    }

    public function mainPageAssets()
    {
        wp_enqueue_style('filterAdminCSS', plugin_dir_url(__FILE__) . 'styles.css');
    }

    public function handleForm()
    {
        if (wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') && current_user_can('manage_options')) {
            update_option('words_to_filter', sanitize_text_field($_POST['words-filter']));
            include_once 'templates/main-menu/confirmation.template.php';
        } else {
            include_once 'templates/main-menu/error.template.php';
        }
    }

    public function filterLogic($content)
    {
        $bad_words = explode(',', get_option('words_to_filter'));
        $trim_bad_words = array_map('trim', $bad_words);
        return str_ireplace($trim_bad_words, esc_html(get_option('replacementText', '****')), $content);
    }

    public function settings()
    {
        add_settings_section('replacement-text-section', null, null, 'word-filter-options');
        register_setting('replacementFields', 'replacementText');

        add_settings_field(
            'replacement-text',
            'Filtered Text',
            [
                $this,
                'replacementFieldHTML',
            ],
            'word-filter-options',
            'replacement-text-section'
        );
    }

    public function replacementFieldHTML()
    {
        include_once 'templates/sub-menu/replacement.template.php';
    }
}

$wordFilter = new WordFilter();
