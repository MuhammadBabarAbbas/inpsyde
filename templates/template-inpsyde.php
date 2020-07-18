<?php
/**
 * @package  InpsydeJobPlugin
 */
 
    use Inc\classes\util\InpsydeCache;
    use Inc\classes\common\Constants;
    use \Inc\classes\exception\InpsydeExternalLinkException;
    use \Inc\classes\exception\JsonException;
        
    get_header();
    $data = array();
    $errorMessage = "";
    try{
        $data = InpsydeCache::getCachedContent(Constants::INPSYDE_USERS_ENDPOINT, Constants::INPSYDE_USERS_TRANSIENT_KEY, false);
    } catch (InpsydeExternalLinkException $ex) {
        $errorMessage = $ex->errorMessage();
    }  catch (JsonException $ex) {
        $errorMessage = $ex->errorMessage();
    }    
    if($errorMessage != ""){
?>
<div class="error">
  <p>
    <strong><?php echo $errorMessage;?></strong>
  </p>
</div>
<?php
}
?>
<div class="modal fade" id="userModal" role="dialog">
    <div class="modal-dialog">
 
     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">User Info</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
 
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
     </div>
    </div>
   </div>
   
   
   
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
			<header class="entry-header">
				<h2 class="entry-title"><?php echo $inpsydeJobPlugin->pluginName();?></h2>
                <br/>
			</header>
			<div class="entry-content">
               <table id="inpsydetable" class="table table-striped table-hover"> 
                    <thead> 
                        <tr> 
                            <th>Id</th> 
                            <th>Name</th> 
                            <th>Username</th>
                        </tr> 
                    </thead>
                    <tbody>
                    <?php
                    if($data != null && is_array($data) && sizeof($data) > 0){
                        foreach($data as $row){
                            echo '<tr><td><a href="#" rel="'.$row->id.'" class="popup">'.$row->id.'</a></td><td><a href="#" rel="'.$row->id.'" class="popup">'.$row->name.'</a></td><td><a href="#" rel="'.$row->id.'" class="popup">'.$row->username.'</a></td></tr>';
                        }                       
                    }                    
                    ?>
                    </tbody>
                </table>
			</div>
		</article>
	</main>
</div>
<?php
	get_footer();
