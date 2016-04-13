<?php

/**
 * The file that defines the plugin functions
 *
 * Functions for adding action and filter hooks.
 * Extending plugin functionality.
 *
 * @link       https://github.com/kseniamaslennikova/km-jobs
 * @since      1.0.0
 *
 * @package    KM Jobs
 * @subpackage KM Jobs/includes
 */

/* KM Jobs plugin activation functions*/
function kmjobs_setup_post_types() {

    //регистрируем тип постов Вакансии
    $kmjob_labels = array(
        'name' => _x( 'Вакансии', 'kmjob' ),
        'singular_name' => _x( 'Вакансию', 'kmjob' ),
        'add_new' => _x( 'Добавить вакансию', 'kmjob' ),
        'add_new_item' => _x( 'Новая вакансия', 'kmjob' ),
        'edit_item' => _x( 'Изменить вакансию', 'kmjob' ),
        'new_item' => _x( 'Новая вакансия', 'kmjob' ),
        'view_item' => _x( 'Просмотреть вакансию', 'kmjob' ),
        'search_items' => _x( 'Поиск вакансий', 'kmjob' ),
        'not_found' => _x( 'Вакансий не найдено', 'kmjob' ),
        'not_found_in_trash' => _x( 'Не найдено вакансий в корзине', 'kmjob' ),
        'parent_item_colon' => '',
        'menu_name' => _x( 'Вакансии', 'kmjob' ),
    );

    $args = array(
        'labels' => $kmjob_labels,
        'taxonomies' => array('job-location','job-category','job-salary'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'hierarchical' => true,
        'menu_position' => 6,
        'menu_icon'=> 'dashicons-portfolio',
        'supports' => array('title','editor','author'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'вакансии')
    );
    register_post_type('kmjob',$args);

}
add_action( 'init', 'kmjobs_setup_post_types' );

//регистрируем таксономии для типа постов Вакансии
function kmjobs_setup_taxonomies(){

    // Местоположение вакансии
    $location_labels = array(
        'name' => _x( 'Местоположение', 'taxonomy general name' ),
        'singular_name' => _x( 'Местоположение', 'taxonomy singular name' ),
        'search_items' =>  __( 'Искать в Местоположениях' ),
        'all_items' => __( 'Все местоположения' ),
        'most_used_items' => null,
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Изменить местоположение' ),
        'update_item' => __( 'Обновить местоположение' ),
        'add_new_item' => __( 'Добавить местоположение' ),
        'new_item_name' => __( 'Новое местоположение' ),
        'menu_name' => __( 'Местоположения' ),
    );
    register_taxonomy('job-location',array('kmjob'),array(
        'hierarchical' => true,
        'labels' => $location_labels,
        'show_ui' => true,
        'query_var' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'местоположения' )
    ));

    // Категория вакансии
    $category_labels = array(
        'name' => _x( 'Категория вакансии', 'taxonomy general name' ),
        'singular_name' => _x( 'Категория вакансии', 'taxonomy singular name' ),
        'search_items' =>  __( 'Искать в категориях вакансии' ),
        'all_items' => __( 'Все варианты' ),
        'most_used_items' => null,
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Изменить категорию' ),
        'update_item' => __( 'Обновить категорию' ),
        'add_new_item' => __( 'Добавить категорию' ),
        'new_item_name' => __( 'Новая категория' ),
        'menu_name' => __( 'Категория вакансии' ),
    );
    register_taxonomy('job-category',array('kmjob'),array(
        'hierarchical' => true,
        'labels' => $category_labels,
        'show_ui' => true,
        'query_var' => true,
        'show_admin_column' => true,
        'meta_box_cb' => 'kmjob_category_meta_box',
        'rewrite' => array('slug' => 'категории' )
    ));

    // Зарплата вакансии
    $salary_labels = array(
        'name' => _x( 'Зарплата', 'taxonomy general name' ),
        'singular_name' => _x( 'Зарплата', 'taxonomy singular name' ),
        'search_items' =>  __( 'Искать в зарплатах' ),
        'all_items' => __( 'Все варианты' ),
        'most_used_items' => null,
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Изменить зарплату' ),
        'update_item' => __( 'Обновить зарплату' ),
        'add_new_item' => __( 'Добавить зарплату' ),
        'new_item_name' => __( 'Новая зарплата' ),
        'menu_name' => __( 'Зарплата' ),
    );
    register_taxonomy('job-salary',array('kmjob'),array(
        'hierarchical' => true,
        'labels' => $salary_labels,
        'show_ui' => true,
        'query_var' => true,
        'show_admin_column' => true,
        'meta_box_cb' => 'kmjob_salary_meta_box',
        'rewrite' => array('slug' => 'зарплата' )
    ));

}
add_action( 'init', 'kmjobs_setup_taxonomies');

//Меняем отображение списка Вакансий на главной странице Админки
add_filter( 'manage_kmjob_posts_columns', 'kmjob_edit_columns' );
function kmjob_edit_columns( $columns ) {

    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Название вакансии',
        'job-category' =>'Категория',
        'job-location' =>'Местоположение',
        'job-salary' =>'Зарплата',
        'author' => 'Автор',
        'date' => 'Дата'
    );
}

/*
 * Функции для работы с типом постов Вакансии
 */
// Добавить вывод постов типа Вакансии на главную страницу в общий список
add_action( 'pre_get_posts', 'add_kmjobs_to_query' );

function add_kmjobs_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', /*'page', */'kmjob' ) );
    return $query;
}

//подставляем нужные данные в столбцы списка Вакансий
add_action( 'manage_kmjob_posts_custom_column', 'kmjob_columns', 10, 2 );
function kmjob_columns( $column, $post_id ) {

    switch ( $column ) {
        case 'job-category':
            echo get_the_term_list( $post_id, 'job-category', '', ', ','' );
            break;
        case 'job-location':
            echo get_the_term_list( $post_id, 'job-location', '', ', ','' );
            break;
        case 'job-salary':
            echo get_the_term_list( $post_id, 'job-salary', '', ', ','' );
            break;

    }
}

//меняем размер колонок в списке вакансий
add_action('admin_head', 'kmjobs_admin_column_width');
function kmjobs_admin_column_width() {
    echo '<style type="text/css">
        .column-title { text-align: left; width:200px !important; overflow:hidden }					
		.column-job-category { text-align: left; width:120px !important; overflow:hidden }
		.column-job-location { text-align: left; width:120px !important; overflow:hidden }
		.column-job-salary { text-align: left; width:120px !important; overflow:hidden }
					
    </style>';
}

//меняем расположение стандартных метабоксов для типа постов Вакансии
add_action( 'admin_head', 'change_kmjobs_metaboxes' );
function change_kmjobs_metaboxes() {

    remove_meta_box('job-categorydiv', 'kmjob', 'side' );
    add_meta_box('job-categorydiv', __('Категория вакансии'),'kmjob_category_meta_box', 'kmjob', 'normal','high');

    remove_meta_box( 'job-salarydiv', 'kmjob', 'side' );
    add_meta_box('job-salarydiv', __('Зарплата'), 'kmjob_salary_meta_box', 'kmjob', 'normal', 'high');

    remove_meta_box( 'authordiv', 'kmjobs', 'normal' );
    add_meta_box('authordiv', __('Автор'), 'post_author_meta_box', 'kmjobs', 'normal', 'low');
}


add_filter('gettext', 'kmjobs_custom_rewrites', 10, 4);
function kmjobs_custom_rewrites($translation, $text, $domain) {

    global $post;

    $translations = &get_translations_for_domain($domain);
    $translation_array = array();

    switch ($post->post_type) {
        case 'kmjob':
            $translation_array = array(
                'Enter title here' => 'Название вакансии',
                'Excerpt' => "Краткая информация о вакансии"
            );
            $pos = strpos($text, 'Excerpts are optional hand-crafted summaries of your');
            if ($pos !== false) {
                return  'Добавьте краткую информацию о вакансии.';
            }
            break;
    }

    if (array_key_exists($text, $translation_array)) {
        return $translations->translate($translation_array[$text]);
    }

    return $translation;
}


/**
 * Новый meta box для таксономии Категория вакансии
 */
function kmjob_category_meta_box( $post ) {
    $terms = get_terms( 'job-category', array( 'hide_empty' => false ) );
    $post  = get_post();
    $job_category = wp_get_object_terms( $post->ID, 'job-category', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
    $name  = '';
    if ( ! is_wp_error( $job_category ) ) {
        if ( isset( $job_category[0] ) && isset( $job_category[0]->name ) ) {
            $name = $job_category[0]->name;
        }
    }
    ?>

    <select name="kmjob_category" id="kmjob_category" style="width:60%;">
        <?php
        foreach ($terms as $term) {
            ?>
            <option <?php selected($term->name, $name); ?>
                value='<?php esc_attr_e($term->name); ?>'><?php esc_attr_e($term->name); ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}


/**
 * Новый meta box для таксономии Зарплата вакансии
 */
function kmjob_salary_meta_box( $post ) {
    $terms = get_terms( 'job-salary', array( 'hide_empty' => false ) );
    $post  = get_post();
    $job_salary = wp_get_object_terms( $post->ID, 'job-salary', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
    $name  = '';
    if ( ! is_wp_error( $job_salary ) ) {
        if ( isset( $job_salary[0] ) && isset( $job_salary[0]->name ) ) {
            $name = $job_salary[0]->name;
        }
    }
    ?>

    <select name="kmjob_salary" id="kmjob_salary" style="width:60%;">
        <?php
        foreach ($terms as $term) {
            ?>
            <option <?php selected($term->name, $name); ?>
                value='<?php esc_attr_e($term->name); ?>'><?php esc_attr_e($term->name); ?></option>
            <?php
        }
        ?>
    </select>
<?php
}

/**
 * Сохраняем результаты для типа постов Вакансии.
 *
 * @param int $post_id The ID of the post that's being saved.
 */
function save_kmjob( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! empty( $_POST['kmjobs_info'] ) && ! wp_verify_nonce( $_POST['kmjobs_info'], 'kmjobs_info' ) )
        return;

    //сохраняем категорию вакансии
    if ( ! isset( $_POST['kmjob_category'] ) ) {
        return;
    }
    $kmjob_category = sanitize_text_field( $_POST['kmjob_category'] );

    if ( empty( $kmjob_category ) ) {
        remove_action( 'save_post_kmjob', 'save_kmjob' );
        $postdata = array(
            'ID'          => $post_id,
            'post_status' => 'draft',
        );
        wp_update_post( $postdata );
    } else {
        $term = get_term_by( 'name', $kmjob_category, 'job-category' );
        if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
            wp_set_object_terms( $post_id, $term->term_id, 'job-category', false );
        }
    }

    //сохраняем зарплату вакансии
    if ( ! isset( $_POST['kmjob_salary'] ) ) {
        return;
    }
    $kmjob_salary = sanitize_text_field( $_POST['kmjob_salary'] );

    if ( empty( $kmjob_salary ) ) {
        remove_action( 'save_post_kmjob', 'save_kmjob' );
        $postdata = array(
            'ID'          => $post_id,
            'post_status' => 'draft',
        );
        wp_update_post( $postdata );
    } else {
        $term = get_term_by( 'name', $kmjob_salary, 'job-salary' );
        if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
            wp_set_object_terms( $post_id, $term->term_id, 'job-salary', false );
        }
    }

}
add_action( 'save_post_kmjob', 'save_kmjob' );

/**
 * Показываем сообщение об ошибке и необходимости заполнения ключевых параметров для публикации вакансии
 *
 * @param WP_Post The current post object.
 */
function kmjobs_show_required_field_error_msg( $post ) {
    if ( 'kmjobs' === get_post_type( $post ) && 'auto-draft' !== get_post_status( $post ) ) {
        $kmjob_category = wp_get_object_terms( $post->ID, 'job-category', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
        if ( is_wp_error( $kmjob_category ) || empty( $kmjob_category ) ) {
            printf(
                '<div class="error below-h2"><p>%s</p></div>',
                esc_html__( 'Категория вакансии является необходимым параметром для заполнения. Вакансия не была опубликована.' )
            );
        }

        $kmjob_salary = wp_get_object_terms( $post->ID, 'job-salary', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
        if ( is_wp_error( $kmjob_salary ) || empty( $kmjob_salary ) ) {
            printf(
                '<div class="error below-h2"><p>%s</p></div>',
                esc_html__( 'Зарплата вакансии является необходимым параметром для заполнения. Вакансия не была опубликована.' )
            );
        }
    }
}
add_action( 'edit_form_top', 'kmjobs_show_required_field_error_msg' );

/**
 * Реализация шорткода
 * [kmjobs] - выводит список всех вакансий с фильтрами по городу и филлиалу
*/
function kmjobs_output( $atts ) {

    $output = '';

    //выводим список всех вакансий
    //начинаем вывод в буфер
    ob_start();

    echo '<h5>Список открытых вакансий</h5>';

    /**
     * добавляем фильтры по городам и филиалам
     */
    ?>
    <div class="kmjobs_filters">

        <div class="city">
            <label for="kmjobs_citylocation">Город</label>
            <?php
            /** Выбираем только узлы верхнего уровня - города */
            $terms = get_terms('job-location', array('parent'=> 0, 'hide_empty'=> false));
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                //перебираем города
                ?>
                <select name="kmjobs_citylocation" id="kmjobs_citylocation">
                    <option value='Все города' selected>Все города</option>
                    <?php
                    foreach ($terms as $term) {
                        ?>
                        <option value='<?php esc_attr_e($term->name); ?>'><?php esc_attr_e($term->name); ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
            }
            ?>
        </div>

        <div class="branch">
            <label for="kmjobs_branchlocation">Филиал</label>
            <!-- Выбираем только дочерние для городов узлы - филиалы -->
            <!-- перебираем города -->
            <select name="kmjobs_branchlocation" id="kmjobs_branchlocation" disabled>
                <option value='Все филиалы' selected>Все филиалы</option>
            </select>
        </div>
    </div>
    <?php
    /**
     *
     */

    $args = array(
        'post_type' => 'kmjob',
        'posts_per_page' => -1
    );
    $jobs = new WP_Query($args);
    //если в базе были заведены вакансии
    if($jobs->have_posts()):
        echo '<div class="kmjob_list_container">';

        //наполняем содержимым контейнер вакансий
        while($jobs->have_posts()): $jobs->the_post();
            $job_id=get_the_ID();
            //получаем категорию вакансии
            $job_category = wp_get_object_terms( $job_id, 'job-category', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
            $job_category_name  = '';
            if ( ! is_wp_error( $job_category ) ) {
                if ( isset( $job_category[0] ) && isset( $job_category[0]->name ) ) {
                    $job_category_name = $job_category[0]->name;
                }
            }
            //получаем зарплату вакансии
            $job_salary = wp_get_object_terms( $job_id, 'job-salary', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
            $job_salary_name  = '';
            if ( ! is_wp_error( $job_salary ) ) {
                if ( isset( $job_salary[0] ) && isset( $job_salary[0]->name ) ) {
                    $job_salary_name = $job_salary[0]->name;
                }
            }
            //получаем города и филиалы, в которых открыта вакансия
            $job_location= get_the_term_list( $job_id, 'job-location', '', ', ','' );

            ?>
            <div class="kmjob_list">

                <h2><a href="<?=the_permalink();?>"><?= get_the_title(); ?></a></h2>
                <date><?=human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ).' назад'; ?></date>
                <ul>
                    <li>Категория: <?= get_the_term_list( $job_id, 'job-category', '', ', ','' ); ?></li>
                    <li>Зарплата: <?= get_the_term_list( $job_id, 'job-salary', '', ', ','' ); ?></li>
                    <li>Вакансия открыта в следующих городах и филиалах: <?=$job_location; ?></li>
                </ul>
            </div>
            <?php

        endwhile;
        //завершили наполнение содержимым контейнера вакансий

        echo '</div>';
        //сохраняем содержимое буфера и очищаем его
        $output.=ob_get_contents();
        ob_clean();
    //если в базе не было заведено ни одной вакансии
    else :
        $output=esc_attr_e('Невозможно вывести список вакансий. Не заведено ни одной вакансии в базе.');

    endif;
    //выводим данные на страницу
    return $output;
}

function kmjobs_register_shortcode() {
    //добавляем шорткод [kmjobs]
    add_shortcode( 'kmjobs', 'kmjobs_output' );
}

add_action( 'init', 'kmjobs_register_shortcode' );


function kmjob_custom_content($content) {
    if ( (is_singular( 'kmjob' ) || is_post_type_archive( 'kmjob' )) && is_main_query()) {

        $job_id=get_the_ID();

        /**
         * выводим категорию и зарплату вакансии
         */
        $preoutput='';
        //начинаем вывод в буфер
        ob_start();

        echo 'Категория: '.get_the_term_list( $job_id, 'job-category', '', ', ','' );
        echo '<h5>Зарплата: '.get_the_term_list( $job_id, 'job-salary', '', ', ','' ).'</h5>';

        //сохраняем содержимое буфера и очищаем его
        $preoutput.=ob_get_contents();
        ob_clean();
        /**
         * завершили вывод категории и зарплаты вакансии
         */

        $output='';
        //начинаем вывод в буфер
        ob_start();

        echo '<h5>Данная вакансия открыта в следующих городах:</h5>';
        //The taxonomy we want to parse
        $taxonomy = "job-location";

        //выбираем города с филиалами
        $hierarchy = _get_term_hierarchy($taxonomy);

        //получаем все местоположения для данной вакансии
        $postterms = get_the_terms( $job_id,$taxonomy );
        $postparents = array();
        //выбираем в отдельное множество только дочерние - филиалы
        if ( !empty( $postterms ) ) {
            $postchildterms = array();
            foreach ( $postterms as $postterm ){
                if( 0 != $postterm->parent ){
                    $postchildterms[] = $postterm->term_id;
                }
                else {
                    $postparents[] = $postterm->term_id;
                }
            }
        }

        /** Выбираем только узлы верхнего уровня - города */
        $terms = get_terms($taxonomy, array('parent'=> 0, 'hide_empty'=> false));
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){

            echo '<ul>';
            /** перебираем города */
            foreach($terms as $term) {

                /** Если в городе заданы филиалы  */
                if($hierarchy[$term->term_id]) {

                    /** выводим и филиалы для данной вакансии */
                    $ul_html='';
                    foreach($hierarchy[$term->term_id] as $child) {
                        /** получаем объект филиала по ID */
                        $child = get_term($child, $taxonomy);
                        //если в филиале есть данная вакансия
                        if ( ! empty( $postchildterms ) && ! is_wp_error( $postchildterms ) ){
                            if (in_array($child->term_id, $postchildterms)) {
                                //выводим филиал
                                $term_link = get_term_link( $child );
                                $ul_html.='<li><a href="'.$term_link.'">'.$child->name.'</a></li>';
                            }
                        }
                    }
                    //если среди всех филиалов данного города есть такие в которых открыта данная вакансия
                    if (!empty($ul_html)){
                        //то выводим город и филиалы
                        $term_link = get_term_link( $term );
                        echo '<li><a href="'.$term_link.'">'. $term->name.'</a><ul>'.$ul_html.'</ul></li>';
                    }

                }
                //если в городе не заданы филиалы
                else {
                    if ( ! empty( $postparents ) && ! is_wp_error( $postparents ) ){
                        if (in_array($term->term_id, $postparents)) {
                            $term_link = get_term_link( $term );
                            echo '<li><a href="'.$term_link.'">'.$term->name.'</a></li>';
                        }
                    }
                }
            }
            echo '</ul>';

        }

        //сохраняем содержимое буфера и очищаем его
        $output.=ob_get_contents();
        ob_clean();

        //обновляем контент для вакансии
        $old_content=$content;
        $content=$preoutput.$old_content.$output;
        
    }
    return $content;

}
add_filter( 'the_content', 'kmjob_custom_content' );

//подключаем нужные для плагина скрипты и стили
function kmjobs_enqueue_scripts() {

    wp_enqueue_style( 'kmjobs-styles', plugin_dir_url( __FILE__ ) . '../css/kmjobs_styles.css' );

    if (!is_admin() && $GLOBALS['pagenow'] != 'wp-login.php') {
        // comment out the next two lines to load the local copy of jQuery
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js', false, '1.12.2');
        wp_enqueue_script('jquery');
    }

    wp_enqueue_script('kmjobs-js', plugin_dir_url( __FILE__ ) . '../js/kmjobs.js', array(), null, true);
}
add_action( 'wp_enqueue_scripts', 'kmjobs_enqueue_scripts' );


//добавляем возможность обращаться к ajax через ajaxurl из frontend
add_action('wp_head','kmjobs_frontend_define_ajaxurl');
function kmjobs_frontend_define_ajaxurl() {
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <?php
}


/**
 * Функция для вывода всех вакансий в блоке шорткода [kmjobs] для выбора родителя в модальном окне
 * с фильтрами по городу и филиалу и без
*/
add_action( 'wp_ajax_nopriv_ajax_jobs_listing', 'do_ajax_jobs_listing' );
add_action( 'wp_ajax_ajax_jobs_listing', 'do_ajax_jobs_listing' );
function do_ajax_jobs_listing() {

    $city='';

    if (!empty($_REQUEST["city"])){
        $city = $_REQUEST["city"];
    }   

    $branch='';

    if (!empty($_REQUEST["branch"])){
        $branch = $_REQUEST["branch"];
    }       
    
    $args = array(
        'post_type' => 'kmjob',
        'posts_per_page' => -1        
    );
    
    if (!empty($city) && $city<>'Все города'){
        //выводим только вакансии для данного местоположения

        $job_location=$city;

        if (!empty($branch) && $branch<>'Все филиалы'){
            $job_location=$branch;
        }        
        
        $args = array(
            'post_type' => 'kmjob',
            'posts_per_page' => -1,
            'tax_query' => 	array(
                array(
                    'taxonomy' => 'job-location',
                    'field'    => 'name',
                    'terms'    => $job_location,
                ),
            ),
        );                
    }      

    $jobs = new WP_Query($args);

    if($jobs->have_posts()){

        //наполняем содержимым контейнер вакансий
        while($jobs->have_posts()): $jobs->the_post();
            $job_id=get_the_ID();
            //получаем категорию вакансии
            $job_category = wp_get_object_terms( $job_id, 'job-category', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
            $job_category_name  = '';
            if ( ! is_wp_error( $job_category ) ) {
                if ( isset( $job_category[0] ) && isset( $job_category[0]->name ) ) {
                    $job_category_name = $job_category[0]->name;
                }
            }
            //получаем зарплату вакансии
            $job_salary = wp_get_object_terms( $job_id, 'job-salary', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
            $job_salary_name  = '';
            if ( ! is_wp_error( $job_salary ) ) {
                if ( isset( $job_salary[0] ) && isset( $job_salary[0]->name ) ) {
                    $job_salary_name = $job_salary[0]->name;
                }
            }
            //получаем города и филиалы, в которых открыта вакансия
            $job_location= get_the_term_list( $job_id, 'job-location', '', ', ','' );

            ?>
            
            <div class="kmjob_list">

                <h2><a href="<?=the_permalink();?>"><?= get_the_title(); ?></a></h2>
                <date><?=human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ).' назад'; ?></date>
                <ul>
                    <li>Категория: <?= get_the_term_list( $job_id, 'job-category', '', ', ','' ); ?></li>
                    <li>Зарплата: <?= get_the_term_list( $job_id, 'job-salary', '', ', ','' ); ?></li>
                    <li>Вакансия открыта в следующих городах и филиалах: <?=$job_location; ?></li>
                </ul>
            </div>
            <?php

        endwhile;
        //завершили наполнение содержимым контейнера вакансий

    }
    else {
        ?>
        <p>В базе пока нет ни одной вакансии.</p>
        <?php
    }
    wp_reset_query();
    die();
}


/**
 * Функция для вывода всех вакансий в блоке шорткода [kmjobs] для выбора родителя в модальном окне
 * с фильтрами по городу и филиалу и без
 */
function do_ajax_jobs_listing_by_filter($jobcity,$jobbranch) {

    $output='';

    $city='';
    if (!empty($jobcity)){
        $city = $jobcity;
    }

    $branch='';
    if (!empty($jobbranch)){
        $branch = $jobbranch;
    }

    //начинаем вывод в буфер
    ob_start();

    $args = array(
        'post_type' => 'kmjob',
        'posts_per_page' => -1
    );

    if (!empty($city) && $city<>'Все города'){
        //выводим только вакансии для данного местоположения

        $job_location=$city;

        if (!empty($branch) && $branch<>'Все филиалы'){
            $job_location=$branch;
        }

        $args = array(
            'post_type' => 'kmjob',
            'posts_per_page' => -1,
            'tax_query' => 	array(
                array(
                    'taxonomy' => 'job-location',
                    'field'    => 'name',
                    'terms'    => $job_location,
                ),
            ),
        );
    }

    $jobs = new WP_Query($args);

    if($jobs->have_posts()){

        //наполняем содержимым контейнер вакансий
        while($jobs->have_posts()): $jobs->the_post();
            $job_id=get_the_ID();
            //получаем категорию вакансии
            $job_category = wp_get_object_terms( $job_id, 'job-category', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
            $job_category_name  = '';
            if ( ! is_wp_error( $job_category ) ) {
                if ( isset( $job_category[0] ) && isset( $job_category[0]->name ) ) {
                    $job_category_name = $job_category[0]->name;
                }
            }
            //получаем зарплату вакансии
            $job_salary = wp_get_object_terms( $job_id, 'job-salary', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
            $job_salary_name  = '';
            if ( ! is_wp_error( $job_salary ) ) {
                if ( isset( $job_salary[0] ) && isset( $job_salary[0]->name ) ) {
                    $job_salary_name = $job_salary[0]->name;
                }
            }
            //получаем города и филиалы, в которых открыта вакансия
            $job_location= get_the_term_list( $job_id, 'job-location', '', ', ','' );

            ?>
            <div class="kmjob_list">

                <h2><a href="<?=the_permalink();?>"><?= get_the_title(); ?></a></h2>
                <date><?=human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ).' назад'; ?></date>
                <ul>
                    <li>Категория: <?= get_the_term_list( $job_id, 'job-category', '', ', ','' ); ?></li>
                    <li>Зарплата: <?= get_the_term_list( $job_id, 'job-salary', '', ', ','' ); ?></li>
                    <li>Вакансия открыта в следующих городах и филиалах: <?=$job_location; ?></li>
                </ul>
            </div>
            <?php

        endwhile;
        //завершили наполнение содержимым контейнера вакансий

    }
    else {
        ?>
        <p>В базе пока нет ни одной вакансии.</p>
        <?php
    }
    
    //сохраняем содержимое буфера и очищаем его
    $output.=ob_get_contents();
    ob_clean();
    return $output;

}

/**
 * Функция для вывода всех филиалов для выбранного города в
 * выпадающем списке
 */
add_action( 'wp_ajax_nopriv_ajax_kmjobs_filter', 'do_ajax_kmjobs_filter' );
add_action( 'wp_ajax_ajax_kmjobs_filter', 'do_ajax_kmjobs_filter' );
function do_ajax_kmjobs_filter() {

    $city = $_REQUEST["city"];

    $branchbox_html='';
    $jobslist_html='';

    //начинаем вывод в буфер
    ob_start();

    $city_obj = get_term_by('name', $city, 'job-location');
    $city_id=$city_obj->term_id;

    //выбираем города с филиалами
    $hierarchy = _get_term_hierarchy('job-location');

    /** Если в городе заданы филиалы  */
    if($hierarchy[$city_id]) {
        /** выводим и филиалы для данной вакансии */
        ?>
        <option value="Все филиалы" selected>Все филиалы</option>
        <?php
        foreach ($hierarchy[$city_id] as $child) {
            /** получаем объект филиала по ID */
            $child = get_term($child, 'job-location');
            //выводим филиал
            ?>
            <option value="<?php esc_attr_e($child->name); ?>"><?php esc_attr_e($child->name); ?></option>
            <?php
        }
    }
    else {
        /** выводим только строку Все филиалы */
        ?>
        <option value="Все филиалы" selected>Все филиалы</option>
        <?php
    }

    //сохраняем содержимое буфера и очищаем его
    $branchbox_html.=ob_get_contents();
    ob_clean();
    
    $jobslist_html=do_ajax_jobs_listing_by_filter($city,'');

    $result['branchbox_html'] = $branchbox_html;
    $result['jobslist_html'] = $jobslist_html;

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    }
    else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    die();
}