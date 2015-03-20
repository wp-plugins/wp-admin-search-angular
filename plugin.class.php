<?php
/**
* Plugin Main Class
*/
class Admin_Search_Angular
{
    
    function __construct(){
        add_action( 'admin_menu', array($this, 'admin_search_menu_page') );
        add_action( 'admin_enqueue_scripts', array($this, 'loading_angular_admin'));
        add_action('wp_ajax_wcp_delete_post', array($this, 'delete_this_post'));
    } 


    function admin_search_menu_page(){
        add_menu_page( 'Search WordPress', 'Search WP', 'manage_options', 'wcp-search', array($this,'render_search_page'), 'dashicons-search' );
    } 


    function render_search_page(){
        ?>
        <div ng-app="WPSearch" style="margin: 10px;">
            <div class="panel panel-default" ng-controller="searchCtrl as WPS">
                <div class="panel-heading">
                    <h3 class="panel-title">Type and Filter</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" ng-model="search.post_title" placeholder="Search by title...">
                        </div>
                        <div class="col-sm-3">
                            <select ng-model="search.post_type" ng-init="search.post_type = ''" class="form-control">
                                <option value="">All Post Types</option>
                                <option value="post">Only Posts</option>
                                <option value="page">Only Pages</option>
                                <option value="product">Only Products</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select ng-model="search.post_status" ng-init="search.post_status = ''" class="form-control">
                                <option value="">All Status</option>
                                <option value="publish">Only Published</option>
                                <option value="future">Only Future</option>
                                <option value="draft">Only Draft</option>
                                <option value="pending">Only Pending</option>
                                <option value="private">Only Private</option>
                                <option value="trash">Only Trash</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select ng-model="orderBy" ng-init="orderBy = ''" class="form-control">
                                <option value="">Random Order</option>
                                <option value="post_title">Title</option>
                                <option value="post_date">Date</option>
                                <option value="comment_count">Comments</option>
                            </select>
                        </div>                        
                    </div>
                    <hr>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Comments</th>
                            <th>Date Created</th>
                            <th>Date Modified</th>
                        </tr>
                        <tr ng-mouseenter="showBtn = true" ng-mouseleave="showBtn = false" ng-repeat="post in allPosts | filter:search | orderBy:orderBy | limitTo:10">
                            <td>{{post.post_title}} <br><br>
                                <a ng-show="showBtn" href="<?php echo admin_url(); ?>/post.php?post={{post.ID}}&action=edit" class="btn btn-primary btn-xs">Edit</a>
                                <a ng-show="showBtn" target="_blank" href="{{post.guid}}" class="btn btn-success btn-xs">Preview</a>
                                <button ng-show="showBtn" ng-click="deletePost(post.ID, post.post_title)" class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-example-modal-md">Delete</button>
                            </td>
                            <td>{{post.post_type}}</td>
                            <td>{{post.post_status}}</td>
                            <td>{{post.comment_count}}</td>
                            <td>{{post.post_date}}</td>
                            <td>{{post.post_modified}}</td>
                        </tr>
                    </table>
                    <div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Are You Sure?</h4>
                            </div>
                            <div class="modal-body">
                                <p>Do you want to delete the <b>{{deletingtitle}}</b> with id of <strong>{{deletingid}}</strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-danger" ng-click="deleteNow()" id="deletebtn">Delete</button>
                            </div>                                                     
                        </div>
                      </div>
                    </div>

                </div>
            </div>
        </div>
        
        <?php
    }

    function loading_angular_admin($check){
        if ('toplevel_page_wcp-search' == $check ) {
            wp_enqueue_script( 'angular-js', plugin_dir_url( __FILE__ ). '/js/angular.min.js');
            wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ). '/js/bootstrap.min.js');
            wp_enqueue_script( 'angular-js-custom', plugin_dir_url( __FILE__ ). '/js/script.js', array('angular-js', 'jquery', 'bootstrap-js'));
            wp_enqueue_style( 'bootstrap-js', plugin_dir_url( __FILE__ ). '/css/bootstrap.min.css');
            global $post;
            $args = array( 'numberposts' => -1, 'post_type' => array( 'post', 'page', 'product' ), 'post_status'      => array('publish','private', 'draft','future','pending', 'trash') );
            $posts = get_posts($args);
            wp_localize_script( 'angular-js-custom', 'searchdata', array( 'url' => admin_url('admin-ajax.php'), 'posts' => $posts) );
        }
    }

    function delete_this_post(){
        extract($_REQUEST);

        wp_delete_post( $post_id );

        die(0);
    }
}
?>