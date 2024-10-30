<div class="wrap">
    <h2 class="wpcube"><?php echo $this->plugin->displayName; ?> &raquo; <?php _e('Settings'); ?></h2>
           
    <?php    
    if (isset($this->message)) {
        ?>
        <div class="updated fade"><p><?php echo $this->message; ?></p></div>  
        <?php
    }
    if (isset($this->errorMessage)) {
        ?>
        <div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>  
        <?php
    }
    ?> 
    
    <div id="poststuff">
    	<div id="post-body" class="metabox-holder columns-2">
    		<!-- Content -->
    		<div id="post-body-content">
    			<!-- Form Start -->
		        <form id="post" name="post" method="post" action="admin.php?page=<?php echo $this->plugin->name; ?>">
		            <div id="normal-sortables" class="meta-box-sortables ui-sortable">   
		            	<!-- Authentication -->
	                    <div class="postbox">
	                        <h3 class="hndle"><?php _e('Settings', $this->plugin->name); ?></h3>
	                        
	                        <div class="option">
	                        	<p>
	                        		<label for="specific">
	                        			<?php _e('Apply to specific image sizes only', $this->plugin->name); ?></strong>
	                        			<input type="checkbox" name="<?php echo $this->plugin->name; ?>[specific]" value="1" id="specific" />
	                        		</label>
	                        	</p>
	                        </div>
	                        
	                        <div id="image-sizes">
	                        	
	                        </div>
	                        
	                        <!-- Save -->
	                        <div class="option">
	                        	<p>
	                        		<input type="submit" name="submit" value="<?php _e('Save', $this->plugin->name); ?>" class="button button-primary" /> 
		                		</p>
	                        </div>
	                   	</div>
					</div>
					<!-- /normal-sortables -->
			    </form>
			    <!-- /form end -->
    			
    		</div>
    		<!-- /post-body-content -->
    		
    		<!-- Sidebar -->
    		<div id="postbox-container-1" class="postbox-container">
    			<?php require_once($this->plugin->folder.'/_modules/dashboard/views/sidebar-donate.php'); ?>		
    		</div>
    		<!-- /postbox-container -->
    	</div>
	</div>      
</div>