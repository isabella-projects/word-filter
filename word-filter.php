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
        require('templates/main-menu.template.php');
    }

    public function wordFilterSubPage()
    {
        require('templates/sub-menu.template.php');
    }

    public function mainPageAssets()
    {
        wp_enqueue_style('filterAdminCSS', plugin_dir_url(__FILE__) . 'styles.css');
    }
}

$wordFilter = new WordFilter();
