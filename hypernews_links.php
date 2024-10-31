<?php


function hypernews_links(){
    global $wpdb;
    global $current_user;
    get_currentuserinfo();

    //nocache_headers();

    $current_link = new Newsflow_Item(0);
    
    $link_id = 0;
    if (isset($_GET['id'])){
        
        if (isset($_GET['delete'])){
            Newsflow_Item::delete((int)esc_attr($_GET['id']));
            echo '<script>document.location="?page=hypernews_links";</script>';
        }
        else{
            $current_link = new Newsflow_Item((int)esc_attr($_GET['id']));
            $link_id = $current_link->id;
        }
        
    }

?>
    <div class="wrap">
        <div id="icon-link-manager" class="icon32"><br/></div><h2><?php _e('Newsflow', 'newsflow'); echo ' - '; _e('RSS Feeds', 'newsflow'); ?></h2>

        <?php
        if (isset($_POST['id'])){
            //SPARA

            $current_link->name = esc_attr($_POST['source']);
            $current_link->channel = esc_attr($_POST['channel']);
            $current_link->url = $_POST['url'];
            $current_link->search = esc_attr($_POST['search']);
            $current_link->maxchars = esc_attr($_POST['maxchars']);
            $current_link->removechars = esc_attr($_POST['removechars']);
            $current_link->maxage = esc_attr($_POST['maxage']);
            //$current_link['sort_order'] = esc_attr($_POST['sort_order']);
            $current_link->posttypes = $_POST['posttypes'];
            $current_link->report = $_POST['report'];

            $id = $current_link->save();
            $current_link = new Newsflow_Item($id);

            ?>
            <div style="background:#EEEE33;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
                <p><?php _e('Link settings is saved '.date('Y-m-d H:i:s').'.','matchmail'); ?></p>
            </div>
            <?php
        }
        ?>

        <form method="post">
        <?php
        if (isset($_GET['id'])) {
            ?>
            <h3><?php _e('Edit RSS source','newsflow'); ?></h3>
                <table cellpadding="6">
                    <tr>
                        <td align="right">
                            <?php _e('Source name','newsflow'); ?>:
                        </td>
                        <td>
                            <input type="text" name="source" value="<?php echo $current_link->name; ?>" id="newsflow_name" size="50" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php _e('Channel name','newsflow'); ?>:
                        </td>
                        <td>
                            <input type="text" name="channel" value="<?php echo $current_link->channel; ?>" size="50" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php _e('Url','newsflow'); ?>:
                        </td>
                        <td>
                            <textarea name="url" id="url" cols="50" rows="10" scrollbars="1"><?php echo $current_link->url; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php _e('Search','newsflow'); ?>:
                        </td>
                        <td>
                            <textarea name="search" cols="50" rows="10" scrollbars="1"><?php echo $current_link->search; ?></textarea><br/>
                            <i><?php _e('Only collecting news with one of these comma separated words found','newsflow'); ?></i>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php _e('Strikethrough text after n-characters:', 'newsflow'); ?>
                        </td>
                        <td>
                            <input type="text" name="maxchars" value="<?php echo $current_link->maxchars; ?>" size="5" />&nbsp;<?php _e('( 0 = feature disabled )', 'newsflow'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php _e('Remove text after n-characters:', 'newsflow'); ?>
                        </td>
                        <td>
                            <input type="text" name="removechars" value="<?php echo $current_link->removechars; ?>" size="5" />&nbsp;<?php _e('( 0 = feature disabled )', 'newsflow'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php _e('Delete RSS-items older than:', 'newsflow'); ?>
                        </td>
                        <td>
                            <input type="text" name="maxage" value="<?php echo $current_link->maxage; ?>" size="5" />&nbsp;<?php _e('( hours, 0 = feature disabled )', 'newsflow'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php _e('Email errors to:', 'newsflow');

                            $report = $current_link->report;
                            if (!$report){
                                wp_get_current_user();
                                $report = $current_user->user_email;
                            }

                            ?>
                        </td>
                        <td>
                            <input type="text" name="report" value="<?php echo $report ?>" size="50" />&nbsp;<?php _e('( separate multiple email adresses with , )', 'newsflow'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?php
                                _e('Publish to:', 'newsflow');
                            ?>
                        </td>
                        <td>
                            <?php
                                echo '<div>';
                                $post_types=get_post_types(array('public'=>true),'objects');
                                foreach ($post_types as $post_type )
                                {
                                    echo '<span style="float:left; border: solid 1px #aaa; background-color:#eee;padding:4px;margin-right:10px;margin-bottom: 10px; min-width: 150px; overflow: hidden; ">';
                                    echo '<input type="checkbox" name="posttypes[]" value="'.$post_type->name.'" ';

                                    $posttypes = $current_link->posttypes;
                                    if (!is_array($posttypes)) $posttypes = array();

                                    if (in_array($post_type->name, $posttypes))
                                    {
                                        echo ' checked';
                                    }

                                    echo '> '. $post_type->label. '</span>';

                                }
                                echo '<div style="clear:both;"></div>';
                                echo '</div>';
                            ?>
                        </td>
                    </tr>
                    <!--tr>
                        <td align="right">
                            <span style="width:200px;"><?php _e('Sort order:','newsflow'); ?></span>
                        </td>
                        <td>
                            <input type="text" name="sort_order" value="<?php echo $current_link->sort_order; ?>" size="5" />
                        </td>
                    </tr-->
                    <tr>
                        <td align="right">
                            <span style="width:200px;"><?php _e('Test this feed after save:','newsflow'); ?></span>
                        </td>
                        <td>
                            <input type="checkbox" name="test" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="width:200px;"><?php _e('Reset all items from this source:', 'newsflow'); ?></span>
                        </td>
                        <td>
                            <input type="checkbox" name="clear" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="save" value="true" />
                            <input type="hidden" name="id" value="<?php echo ((int)esc_attr($_GET["id"])); ?>" />
                        </td>
                        <td>
                            <input type="submit" name="save" class="button-primary" value="<?php _e('Save','newsflow') ?>" />
                            <input type="button" class="button-secondary" value="<?php _e('Cancel','newsflow') ?>" onclick="document.location='?page=hypernews_links';" />
                        </td>
                    </tr>
                </table>

        <?php

            if ($_POST["clear"]){
                echo '<h3>Clear...</h3>';
                $sql = "DELETE FROM ".Newsflow_Database::database_table_store() . " WHERE link_id=".$current_link->id;
                $wpdb->query( $sql );
                $sql = "UPDATE ".Newsflow_Database::database_table_raw() . " SET (stored=0) WHERE link_id=".$current_link->id;
                $wpdb->query( $sql );
            }

            if ($_POST["test"]){
                do_action('newsflow_fetch',$current_link->id,true);
            }
        }
        else 
        {


            $list = new Hypernews_Links();
            $list->prepare_items();
            $list->display();

        }
        
        ?>
            
        </form>        
        
    </div>
<?php
}

if(!class_exists('WP_List_Table'))
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Hypernews_Links extends WP_List_Table {
    
    function __construct() 
    {
        global $status, $page;
         parent::__construct( array(
            'singular'=> 'Link', //Singular label
            'plural' => 'Links', //plural label, also this well be one of the table css class
            'ajax'	=> false //We won't support Ajax for this table
            ) );
    }    
    
    function prepare_items() {
        global $_wp_column_headers;
        global $current_user;
        get_currentuserinfo();

        $channel = get_user_meta($current_user->ID, "hypernews_channel",true);

        $screen = get_current_screen();
                
        $this->process_bulk_action();

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Fetch the items -- */
        $data = Newsflow_Item::get_links();

        if ($channel){
            foreach($data as $key => $link){
                if ($link->channel!=$channel){
                    unset($data[$key]);
                }
            }
        }


        //sort link for latest execution time
        //function sort_data($a, $b)
        //{
        //    $meta_a = json_decode($a->link_notes);
        //    $meta_b = json_decode($b->link_notes);
        //    return $meta_a['source'] > $meta_b['source'];
        //}
        //usort($data, 'sort_data');

        $this->items = $data;

    }
    
    function get_columns(){
        $columns = array(
            'id' => 'Id',
            'source'     => __('Source','newsflow'),
            'channel'    => __('Channel','newsflow'),
            'url'  => __('RSS-Url','newsflow'),
            'posttypes' => __('Post types','newsflow'),
            'fetch_date'    => __('Latest fetch','newsflow'),
            'fetch_time'    => __('Exec time','newsflow'),
            'fetch_index'    => __('# items','newsflow'),
            'fetch_added'    => __('New articles','newsflow'),
            'error' => __('Error','newsflow')
        );
        return $columns;
    }
    
    function get_sortable_columns() {
        return array();
    }
    
    function get_hidden_columns() {
        $result = array();
        $result[] = "id";
        $result[] = "url";
        return $result;
    }
    
    function get_bulk_actions() {

        $error_channels = $this->get_error_channels();

        //$actions['new_link'] = __('Add new RSS Feed', 'newsflow');
        $actions['only_show'] = __('Show all sources','newsflow');
        foreach (Newsflow_Item::channels() as $key => $value) {
            if (in_array($value, $error_channels)){
                $actions[$value] .= '* ';
            }
            $actions[$value] .= __('Only show sources from channel: ','newsflow').$value;
            if (in_array($value, $error_channels)){
                $actions[$value] .= ' * ' . __(' (Contains errors!)','newsflow');
            }
        }
        return $actions;
    }

    function get_error_channels(){
        $result = array();
        $links = Newsflow_Item::get_links();
        foreach($links as $link){
            if (strpos($link->error,'NOK')===0 && !in_array($link->channel, $result)) $result[] = $link->channel;
        }
        return $result;
    }
    
    function process_bulk_action() {
        global $current_user;
        get_currentuserinfo();

        if ($this->current_action() === 'only_show'){
            update_user_meta( $current_user->ID, "hypernews_channel", NULL);
            wp_die('<SCRIPT> setTimeout(\'document.location="?page=hypernews_links";\',10); </SCRIPT>');
            return;
        }

        foreach (Newsflow_Item::channels() as $key => $value) {

            $current = $this->current_action();
            if ($current === $value){
                update_user_meta( $current_user->ID, "hypernews_channel", $value);
                wp_die('<SCRIPT> setTimeout(\'document.location="?page=hypernews_links";\',10); </SCRIPT>');
                return;
            }
        }

        if ($this->current_action() === 'new_link'){
            echo '<SCRIPT> document.location="?page=hypernews_links&id=0"; </SCRIPT>';
            return;
        }
        
    }

    function extra_tablenav( $which )
    {
        global $current_user;
        get_currentuserinfo();
        if ( $which == "top" )
        {
            ?>
        <input onclick="document.location='<?php echo get_admin_url(); ?>admin.php?page=hypernews_links&id=0'" style="margin-top: 4px;" type="button" class="button-primary" title="Newsflow Fetch" target="_blank" value="<?php _e('Add new RSS feed','newsflow'); ?>" />
            &nbsp;
        <!--input type="button" class="button-secondary" title="Newsflow Fetch" target="_blank" value="<?php _e('Show all sources','newsflow'); ?>" /-->
        <?php

            $channel = get_user_meta($current_user->ID, "hypernews_channel", true);
            if (!empty($channel) && strlen($channel)>0){
                echo '<div style="color:red;display:inline-block; padding-top:7px">'.__('Filter news on channel:','newsflow').' <strong>'.$channel.'</strong></div>';
            }

            if ( isset( $_REQUEST['fetch'] ) )
            {
                $this->reload_page();
            }
        }
        if ( $which == "bottom" ){
            //The code that goes after the table is there
            echo " ";
        }
    }

    function column_source($item){
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%1$s&id=%2$s">'.__('Edit','newsflow').'</a>',$_REQUEST['page'],$item->id),
            'delete'      => sprintf('<a onclick="return confirm(\''.__('Confirm delete this source!','newsflow').'\');" href="?page=%s&id=%s&delete=true">'.__('Delete','newsflow').'</a>',$_REQUEST['page'],$item->id)
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item->name,
            /*$2%s*/ $item->id,
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    
    function column_channel($item){
        return $item->channel;
    }

    function column_fetch_time($item){
        return $item->stats->time . ' s';
    }

    function column_fetch_index($item){
        return $item->stats->index;
    }

    function column_fetch_added($item){
        return $item->stats->added;
    }

    function column_fetch_date($item){
        return $item->stats->date;
    }

    function column_id($item){
        return $item->id;
    }
    
    function column_error($item){
        if (!empty($item->error)){
            $error = $item->error;

            if (substr($error, 0, 2) == "OK"){
                return '<span style="color:green">'.$error.'</span>';
            }
            else {
                return '<span style="color:red">'.$error.'</span>';
            }
        }
        else
        {
            return '';
        }
    }

    function column_url($item){
        return $item->url;
    }
    
    function column_posttypes($item){
        $result = "";
        $posttypes = $item->posttypes;
        if (is_array($posttypes)){
            foreach ($posttypes as $key => $value) {
                if (strlen($result)>0) { $result.=', '; }
                $result.=$value;
            }
        }
        return $result;
    }
    
    function column_default($item, $column_name){
        switch($column_name){
            default:
                return $item->column_name; //Show the whole array for troubleshooting purposes
        }
    }

}


?>
