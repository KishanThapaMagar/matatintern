<?php

    add_action('wp_enqueue_scripts','kalkiautomation_enqueue_assets');
    function kalkiautomation_enqueue_assets()
    {
        wp_enqueue_style('kalki-custom',get_template_directory_uri().'/assets/css/custom.css');
    

    } 
function my_theme_setup() {
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'my_theme_setup' );


class Projects {
    public function __construct() {
        add_action('init', [$this, 'register_projects_cpt']);
        add_action('add_meta_boxes', [$this, 'add_project_details_metabox']);
        add_action('save_post', [$this, 'save_project_details']);
        add_filter('manage_project_posts_columns', [$this, 'add_custom_project_columns']);
        add_action('manage_project_posts_custom_column', [$this, 'display_custom_project_column'], 10, 2);
        add_filter('manage_edit-project_sortable_columns', [$this, 'make_project_columns_sortable']);
        
    }

    function register_projects_cpt() {
        $labels = array(
            'name'               => 'Projects',
            'singular_name'      => 'Project',
            'menu_name'          => 'Projects',
            'name_admin_bar'     => 'Project',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Project',
            'new_item'           => 'New Project',
            'edit_item'          => 'Edit Project',
            'view_item'          => 'View Project',
            'all_items'          => 'All Projects',
            'search_items'       => 'Search Projects',
            'not_found'          => 'No projects found.',
            'not_found_in_trash' => 'No projects found in Trash.',
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => array('title', 'editor', 'excerpt', 'thumbnail'),
            'has_archive'        => true,  // Ensure this is true
            'show_in_rest'       => true,
        );
    
        register_post_type('project', $args);
    }
  
    

    public function add_project_details_metabox() {
        add_meta_box(
            'project_details_metabox',
            'Project Details',
            [$this, 'render_project_details_metabox'],
            'project',
            'normal',
            'default'
        );
    }

    public function render_project_details_metabox($post) {
        wp_nonce_field('save_project_details', 'project_details_nonce');

        $client_name = get_post_meta($post->ID, '_client_name', true);
        $project_deadline = get_post_meta($post->ID, '_project_deadline', true);
        $project_status = get_post_meta($post->ID, '_project_status', true);
        ?>
        <p>
            <label for="client_name">Client Name:</label><br>
            <input type="text" id="client_name" name="client_name" value="<?php echo esc_attr($client_name); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="project_deadline">Project Deadline:</label><br>
            <input type="date" id="project_deadline" name="project_deadline" value="<?php echo esc_attr($project_deadline); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="project_status">Project Status:</label><br>
            <select id="project_status" name="project_status" style="width: 100%;">
                <option value="Not Started" <?php selected($project_status, 'Not Started'); ?>>Not Started</option>
                <option value="In Progress" <?php selected($project_status, 'In Progress'); ?>>In Progress</option>
                <option value="Completed" <?php selected($project_status, 'Completed'); ?>>Completed</option>
            </select>
        </p>
        <?php
    }

    public function save_project_details($post_id) {
        if (!isset($_POST['project_details_nonce']) || !wp_verify_nonce($_POST['project_details_nonce'], 'save_project_details')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['client_name'])) {
            update_post_meta($post_id, '_client_name', sanitize_text_field($_POST['client_name']));
        }

        if (isset($_POST['project_deadline'])) {
            update_post_meta($post_id, '_project_deadline', sanitize_text_field($_POST['project_deadline']));
        }

        if (isset($_POST['project_status'])) {
            update_post_meta($post_id, '_project_status', sanitize_text_field($_POST['project_status']));
        }
    }

    public function add_custom_project_columns($columns) {
        $columns['client_name'] = 'Client Name';
        $columns['project_deadline'] = 'Deadline';
        $columns['project_status'] = 'Status';
        return $columns;
    }

    public function display_custom_project_column($column, $post_id) {
        switch ($column) {
            case 'client_name':
                echo esc_html(get_post_meta($post_id, '_client_name', true));
                break;
            case 'project_deadline':
                echo esc_html(get_post_meta($post_id, '_project_deadline', true));
                break;
            case 'project_status':
                echo esc_html(get_post_meta($post_id, '_project_status', true));
                break;
        }
    }

    public function make_project_columns_sortable($columns) {
        $columns['client_name'] = 'client_name';
        $columns['project_deadline'] = 'project_deadline';
        $columns['project_status'] = 'project_status';
        return $columns;
    }
    
}

// Initialize the class
new Projects();

?>