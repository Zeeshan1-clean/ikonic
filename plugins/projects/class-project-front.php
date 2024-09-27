<?php

class Project_Front{

    public function __construct(){

        // Redirect User if Ip starts with 77.29
        add_action('init', array($this, 'redirect_user_by_checking_ip') );
        add_shortcode('coffee',[$this,'get_my_coffee']);
        add_action('wp_enqueue_scripts', [$this,'enqueue_project_ajax_script']);
        add_shortcode( 'quote_short_code', array( $this, 'print_quotes_from_api') );
    }
    public function print_quotes_from_api(){

        for ($i=1 ; $i < 6 ; $i++) { 

            $response = wp_remote_get('https://api.kanye.rest/');

            if (is_wp_error($response)) {
                echo 'Error: ' . $response->get_error_message();
            } else {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);
                if (!empty($data['quote'])) {
                    echo "<b>Quote # " . $i . ': </b>' . esc_html($data['quote']) . "<br>";
                } else {
                 echo  "No quote found.";
             }
         }
     }
 }
 public function enqueue_project_ajax_script() {
    wp_enqueue_script('project-ajax', plugin_dir_url(__FILE__) . 'assets/js/front.js', array('jquery'), null, true);
    wp_localize_script('project-ajax', 'ajax_obj', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
public function get_my_coffee(){
    ob_start();
    $json_coffe=$this->hs_give_me_coffee();
    $js_obj=json_decode($json_coffe);
    ?>
    <style type="text/css">
        .coffe-link{
            text-decoration: none;
            background-color: brown;
            color: white;
            padding: 20px;
        }
        .flex-center{
            display: flex;
            flex-direction: column;
            gap: 2rem;
            align-items: center;
        }
    </style>
    <div class="flex-center">
        <a class="coffe-link" href="<?php echo $js_obj->data[0]->link; ?>">Here is you Coffe</a>
        <br>
        <img src="<?php echo $js_obj->data[0]->link; ?>">
    </div>
    <?php
    return ob_get_clean();
}
public function hs_give_me_coffee() {
    $api_url = 'https://coffee.alexflipnote.dev/random.json';
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return json_encode(array('success' => false, 'message' => 'Failed to fetch coffee link: ' . $response->get_error_message()));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['file'])) {
        return json_encode(array('success' => true, 'data' => array(
            array(
                'id' => uniqid(),
                'title' => 'Random Coffee',
                'link' => esc_url($data['file'])
            )
        )));
    } else {
        return json_encode(array('success' => false, 'message' => 'No coffee link found.'));
    }
}

public function redirect_user_by_checking_ip(){

    $user_ip = $_SERVER['REMOTE_ADDR'];

    if (strpos($user_ip, '77.29') === 0) {

        wp_redirect('https://www.google.com');
        exit;
    }
}
}

new Project_Front();