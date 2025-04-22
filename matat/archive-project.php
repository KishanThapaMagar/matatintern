<?php
get_header(); ?>

<div class="projects-archive">

    <?php
        $args=new WP_Query(array(
            'post_type'=>'project',
            'posts_per_page'=>-1,
            'post_status'=>'publish'
        ));
        if($args->have_posts()):
        while($args->have_posts()):$args->the_post(); 
        ?>
        <div class="project-container">
           <div class="project-content">
                <h1 class="project-title"><?php echo the_title();?></h1>
                <div class="project-image">               
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'medium'))?>" alt="">
                </div>
                <h3>Client Name:
                    <?php 
                    $client_name = get_post_meta(get_the_ID(), '_client_name', true);
                    echo $client_name ? esc_html($client_name) : 'No client name available'; 
                    ?></h3>
                <h2>Deadline: 
                    <?php 
                    $project_deadline = get_post_meta(get_the_ID(), '_project_deadline', true);
                    echo esc_html($project_deadline ? $project_deadline : 'No deadline set');?>
                </h2>
                <h3>
                    Status: 
                    
                    <?php 
                    $project_status = get_post_meta(get_the_ID(), '_project_status', true);
                    $status_class = '';  // Default class

                    // Assign classes based on the project status
                    if ($project_status === 'Not Started') {
                        $status_class = 'status-not-started';
                    } elseif ($project_status === 'In Progress') {
                        $status_class = 'status-in-progress';
                    } elseif ($project_status === 'Completed') {
                        $status_class = 'status-completed';
                    }
                    ?>
                    <span class="<?php echo esc_attr($status_class); ?> project-status">
                        <?php echo esc_html($project_status ? $project_status : 'No status set'); ?>
                    </span>
                </h3>
            </div>     
        </div>
            
        <?php
        endwhile;
        wp_reset_postdata();
        endif;
        

        ?>


</div>

<?php get_footer(); ?>
