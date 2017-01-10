<?php 
require_once( dirname(__FILE__) . '/../../../../../wp-load.php' );
?>

        <h3><?php echo __('Inserting ad blocks in your theme','wpailang');?></h3>

        <p><?php echo __('You can manually insert ad blocks in your theme by using the following function','wpailang');?>:
            <br>
            <code>&lt;?php show_ad_block(X); ?&gt;</code>
        </p>

         <h3><?php echo __('Inserting ad blocks in the editor','wpailang');?></h3>

        <p>
        	<?php echo __('You can manually insert ad blocks in the WordPress editor by using the button','wpailang');?>
            <img src="<?php echo plugins_url('images/dollar.png', dirname(dirname(__FILE__))); ?>">. <?php echo __('This will insert a short code in the form','wpailang');?>: <br>
            <code>[showad block=X]</code><br>
            <?php echo __('Alternatively, you can also insert this short code yourself.','wpailang');?>
        </p>

        <h3><?php echo __('Disabling ads','wpailang');?></h3>

        <p><?php echo __('In order to disable some ads for a type of page, you can use one of the options above. But to disable ads in a particular post or page, you can use one of the following:','wpailang');?>
        <ul class="disc-list">
            <li>&lt;!--NoAds--&gt; : <?php echo __('suppresses all ads when displaying this post (except in a list of posts)','wpailang');?></li>
            <li>&lt;!--NoBelowTitleAds--&gt; : <?php echo __('suppresses the ad below the post or page title','wpailang');?></li>
            <li>&lt;!--NoAfterFirstParagraphAds--&gt; : <?php echo __('suppresses the ad after the first paragraph','wpailang');?></li>
            <li>&lt;!--NoMiddleOfContentAds--&gt; : <?php echo __('suppresses the ad in the middle of the post or page','wpailang');?></li>
            <li>&lt;!--NoBeforeLastParagraphAds--&gt; : <?php echo __('suppresses the ad before the last paragraph','wpailang');?></li>
            <li>&lt;!--NoBelowContentAds--&gt; : <?php echo __('suppresses the ad below the post or page content','wpailang');?></li>
            <li>&lt;!--NoBelowCommentsAds--&gt; : <?php echo __('suppresses the ad below the comments','wpailang');?></li>
            <li>&lt;!--NoWidgetAds--&gt; : <?php echo __('suppresses the ad widget','wpailang');?></li>
            <li>&lt;!--NoBelowFooterAds--&gt; : <?php echo __('suppresses the footer','wpailang');?></li>
		    <li>&lt;!--NoAdBlockX--&gt; : <?php echo __('suppresses ad block X on this post or page e.g. &lt;!--NoAdBlock1--&gt;','wpailang');?></li>
        </ul>
        <?php echo __('Just add it to your post in the text editor. These will be present on the page but not visible and will partially or totally disable ads when this post or page is viewed.','wpailang');?>
        </p>

        <h3><?php echo __('Aligning ad blocks','wpailang');?></h3>

        <p>
            <?php echo __('In order to center an ad block, please wrap it in a div like this','wpailang');?>:<br>
            <code>&lt;div style=&quot;display: table; margin: 0px auto;&quot;&gt; <?php echo __('YOUR AD CODE HERE','wpailang');?> &lt;/div&gt;</code>
        </p>

        <p>
            <?php echo __('In order to align an ad block to the left, please wrap it in a div like this','wpailang');?>:<br>
            <code>&lt;div style=&quot;float: left;&quot;&gt; <?php echo __('YOUR AD CODE HERE','wpailang');?> &lt;/div&gt;</code>
        </p>

        <p>
            <?php echo __('In order to align an ad block to the right, please wrap it in a div like this','wpailang');?>:<br>
            <code>&lt;div style=&quot;float: right;&quot;&gt; <?php echo __('YOUR AD CODE HERE','wpailang');?> &lt;/div&gt;</code>
        </p>