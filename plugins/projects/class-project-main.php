<?php
/*
* Plugin Name: Projects
* Plugin URI: 
* Description: 
* Version: 1.0
* Author:
* Author URI: 
* License: GPL 3.0
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: proj
* Domain Path: /languages
*/

class Project_Main{

    public function __construct(){

        // Define Global Constants.
        $this->project_global_constants_define();

        // Create Custom post type Project.
        add_action('init', array( $this, 'create_project_post_type' ) );

        // Create Custom taxonomy Project Type.
        add_action('init',  array( $this, 'create_project_type_taxonomy' ) );  
        add_action('wp_ajax_get_architecture_projects', [$this,'get_architecture_projects']);
        add_action('wp_ajax_nopriv_get_architecture_projects', [$this,'get_architecture_projects']); 

        if( ! is_admin() ){

            include PROJECT_PLUGIN_DIR . 'class-project-front.php';
        }
        
    }
    public function get_architecture_projects() {
        $projects_count = is_user_logged_in() ? 6 : 3;
        $args = array(
            'post_type' => 'project',
            'posts_per_page' => $projects_count,
            'tax_query' => array(
                array(
                    'taxonomy' => 'project_type',
                    'field' => 'slug',
                    'terms' => 'architecture'
                )
            ),
            'post_status' => 'publish',
            'order' => 'DESC'
        );
        $project_query = new WP_Query($args);
        if ($project_query->have_posts()) {
            $projects = array();

            while ($project_query->have_posts()) {
                $project_query->the_post();
                $projects[] = array(
                    'id'    => get_the_ID(),
                    'title' => get_the_title(),
                    'link'  => get_permalink()
                );
            }

            wp_send_json_success(array('data' => $projects));
        } else {
            wp_send_json_error(array('message' => 'No projects found.'));
        }

        wp_die();
    }
    public function project_global_constants_define(){

        if ( ! defined( 'PROJECT_URL' ) ) {
            
            define( 'PROJECT_URL', plugin_dir_url( __FILE__ ) );
            
        }
        
        if ( ! defined( 'PROJECT_BASENAME' ) ) {
            
            define( 'PROJECT_BASENAME', plugin_basename( __FILE__ ) );
            
        }
        
        if ( ! defined( 'PROJECT_PLUGIN_DIR' ) ) {
            
            define( 'PROJECT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            
        }
    }

    public function create_project_post_type() {
        $labels = array(
            'name'                  => _x('Projects', 'Post type general name', 'proj'),
            'singular_name'         => _x('Project', 'Post type singular name', 'proj'),
            'menu_name'             => _x('Projects', 'Admin Menu text', 'proj'),
            'name_admin_bar'        => _x('Project', 'Add New on Toolbar', 'proj'),
            'add_new'               => __('Add New Project', 'proj'),
            'add_new_item'          => __('Add New Project', 'proj'),
            'new_item'              => __('New Project', 'proj'),
            'edit_item'             => __('Edit Project', 'proj'),
            'view_item'             => __('View Project', 'proj'),
            'all_items'             => __('All Projects', 'proj'),
            'search_items'          => __('Search Projects', 'proj'),
            'not_found'             => __('No Projects found.', 'proj'),
            'not_found_in_trash'    => __('No Projects found in Trash.', 'proj'),
            'set_featured_image'    => __('Set featured image', 'proj'),
            'remove_featured_image' => __('Remove featured image', 'proj'),
            'use_featured_image'    => __('Use as featured image', 'proj'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'              => array( 'slug' => 'project' ),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 5,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'show_in_rest'          => true, // Enable Gutenberg editor
        );
        
        register_post_type('project', $args);
    }

    function create_project_type_taxonomy() {
        $labels = array(
            'name'              => __('Project Type', 'taxonomy general name', 'proj'),
            'singular_name'     => __('Project Type', 'taxonomy singular name', 'proj'),
            'search_items'      => __('Search Project Type', 'proj'),
            'all_items'         => __('All Project Types', 'proj'),
            'parent_item'       => __('Project Type', 'proj'),
            'parent_item_colon' => __('Project Type:', 'proj'),
            'edit_item'         => __('Edit Project Type', 'proj'),
            'update_item'       => __('Update Project Type', 'proj'),
            'add_new_item'      => __('Add New Project Type', 'proj'),
            'new_item_name'     => __('New Project Type Name', 'proj'),
            'menu_name'         => __('Project Types', 'proj'),
        );
        
        $args = array(
            'hierarchical'      => true, // Set to true for categories-like taxonomy
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'project_type'),
            'show_in_rest'      => true, // Enable Gutenberg editor
        );
        
        register_taxonomy('project_type', array('project'), $args); // 'book' is the custom post type
    }
}

new Project_Main();