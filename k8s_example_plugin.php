<?php
/*
Plugin Name: Salme's Kubernetes Plugin
Plugin URI: http://github.com/javsalgar/wp-kubernetes-example-plugin
Description: A plugin that shows the number of active replicas of a given deployment
Version: 0.0.1
Author: Javier J Salmeron, jsalmeron@bitnami.com
Author URI: http://bitnami.com
License: GPL2
 */

/*  Copyright 2018  Javier Salmeron (email : jsalmeron@bitnami.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
?>
<?php

// Register and load the widget
function k8s_widget_load()
{
    register_widget('k8s_widget');
}
add_action('widgets_init', 'k8s_widget_load');

// Creating the widget
class k8s_widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'k8s_widget',
            // Widget name will appear in UI
            __('Kubernetes Sidebar', 'k8s_widget_domain'),
            // Widget description
            array('description' => __('Kubernetes Sidebar that shows info about the replicas', 'k8s_widget_domain'))
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        echo $args['before_title'] . "Kubernetes Information" . $args['after_title'];

        // Here we display the Kubernetes information

        // create curl resource
        $ch = curl_init();

        // Subsitute the two PUT_HERE placeholders. WP_K8S_PLUGIN_DEPLOYMENT_NAME is an environment variable
        curl_setopt($ch, CURLOPT_URL, "https://PUT_HERE_DNS_OF_API_SERVER:443/PUT_HERE_API_PATH_TO_DEPLOYMENTS/" . getenv('WP_K8S_PLUGIN_DEPLOYMENT_NAME')); // TODO

        // This will disable https verification check
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Path to Service Account token (Substitute the PUT_HERE placeholder)
        $path_to_file = 'PUT_HERE_THE_PATH_TO_THE_SERVICE_ACCOUNT_TOKEN_FILE'; // TODO

        // Open the file
        $token_file = fopen($path_to_file, "r");
        $token = fread($token_file, filesize($path_to_file));

        // Set authorization
        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization));

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // Log to apache error log (so you can check)
        error_log($output);

        // close curl resource to free up system resources
        curl_close($ch);

        // Now show the information we are looking for
        echo "Total active replicas: " . json_decode($output, true)["spec"]["replicas"];

        // Theme stuff
        echo $args['after_widget'];
    }
} // Class k8s_widget ends here
