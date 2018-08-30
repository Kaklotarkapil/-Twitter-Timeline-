<?php
//start session
session_start();

// Include config file and twitter PHP Library
include_once("config.php");
include_once("inc/twitteroauth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Home</title>

	<link rel="icon" type="image/png" href="images/Twitter_bird_icon.png"/>
	<link rel="stylesheet" type="text/css" href="css/layout.css">
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  
 <!--Slider Script-->
<script src="js/jssor.slider.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        jssor_1_slider_init = function() {

            var jssor_1_SlideshowTransitions = [
              {$Duration:1200,x:-0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
              {$Duration:1200,x:0.3,$SlideOut:true,$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2}
            ];

            var jssor_1_options = {
              $AutoPlay: 1,
              $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $ThumbnailNavigatorOptions: {
                $Class: $JssorThumbnailNavigator$,
                $Orientation: 2,
                $NoDrag: true
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*#region responsive code begin*/

            var MAX_WIDTH = 980;

            function ScaleSlider() {
                var containerElement = jssor_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;

                if (containerWidth) {

                    var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

                    jssor_1_slider.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();

            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        };
    </script>
  <!--Slode done-->
</head>
<body style="background-color: #6699ff">
<?php
if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified') 
{
		//Retrive variables
		$screen_name 		= $_SESSION['request_vars']['screen_name'];
		$twitter_id			= $_SESSION['request_vars']['user_id'];
		$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
		$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
		
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
		$user_info = $connection->get('account/verify_credentials'); 
		
		$prof_img=str_replace('_normal' , '_400x400' ,$user_info->profile_image_url);
		
			//If user wants to tweet using form.
			if(isset($_POST["updateme"])) 
			{
				//Post text to twitter
				$my_update = $connection->post('statuses/update', array('status' => $_POST["updateme"]));
				die('<script type="text/javascript">window.top.location="Home.php"</script>'); //redirect back to index.php
	
			}
			


		function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) 
		{
			$connection1 = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
				return $connection1;
		}

		//make a connection
		$connection1 = getConnectionWithAccessToken(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
			$sc_name=$user_info->screen_name;
			$result = $connection1->get('followers/list', array('count' => 200, 'screen_name' => $screen_name));
			$newFollowers = array();
				
		if(isset($_POST['create_pdf']))
		{
			//echo "kapil";
			require_once("pdf/tcpdf/tcpdf.php");
			$obj_pdf = new TCPDF('p',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
			$obj_pdf->setCreator(PDF_CREATOR);
			$obj_pdf->SetTitle("Data");
			$obj_pdf->SetHeaderData('','',PDF_HEADER_TITLE,PDF_HEADER_STRING);
			$obj_pdf->SetHeaderFont(array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
			$obj_pdf->SetFooterFont(array(PDF_FONT_NAME_DATA,'',PDF_FONT_SIZE_DATA));
			$obj_pdf->SetDefaultMonospacedFont('helvetica');
			$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$obj_pdf->SetMargins(PDF_MARGIN_LEFT,'5',PDF_MARGIN_RIGHT);
			$obj_pdf->SetPrintHeader(false);
			$obj_pdf->SetPrintFooter(false);
			$obj_pdf->SetAutoPageBreak(TRUE,10);
			$obj_pdf->SetFont('helvetica','',12);
			$obj_pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$obj_pdf->AddPage();
			
			foreach($result->users as $user)
			{
			
				$html2 = 
				'<div style="background-color:gray;height:auto;width:auto">
		     		<img src="'.$user->profile_image_url.'" style="height:32px;width:32px;border:1px solid black"/>
					'.$user->name.' <br/> 
					'.date('d/m/Y H:i:s',strtotime($user->created_at)).' <br/>
					 '.$user->followers_count.' <br/>
					  '.$user->friends_count.' <br/>
					  '.$user->statuses_count.' 
				</div>
				<br/>';
				$obj_pdf->writeHTMLCell(0,0,'','',$html2,0,1,0,true,'',true);
						
			}
				
			ob_end_clean();
			$obj_pdf->Output("file.pdf","I");

		}
			
			
						
?>

<div class="navbar navbar-default navbar-static-top" style="height: 10px;margin-left: 8px;background-color: transparent;border-color: transparent;" >
	<div class="container">
		<div class="navbar-collapse navbar-collapse-1 collapse" aria-expanded="true" style="margin-top: -23px">
			<ul class="nav navbar-nav">
				<li class="active">
					<a href="../UploadCSVGoogleDrive/index.php"><span class="glyphicon glyphicon-home"></span>Upload csv</a>
				</li>

				<li>
					<a href="#fake"><span class="glyphicon glyphicon-bell"></span> Notifications</a>
				</li>
				<li>
					<a href="#fake"><span class="glyphicon glyphicon-envelope"></span> Messages</a>
				</li>
			</ul>
			<div class="navbar-form navbar-right" style="width: auto">
				
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-body">
				
					<a href="#"><img class="img-responsive"  style="width:100%;height:auto;" src="<?php echo $prof_img?>"></a>
						<br>
						<div style="background:#cccccc;height:70px;">
							<?php echo 'Name : <strong>'.$user_info->screen_name .'</strong>'; ?><br>
							
							<?php echo 'Ocupation : <strong>'.$user_info->description .'</strong>'; ?><br>
							<?php echo 'Location : <strong>'.$user_info->location .'</strong>';?>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<h5>
									<small>TWEETS</small>
									<a href="#"><?php echo $user_info->statuses_count?></a>
								</h5>
							</div>
							<div class="col-xs-4">
								<h5>
									<small>FOLLOWING</small>
									<a href="#"><?php echo $user_info->friends_count?></a>
								</h5>
							</div>
							<div class="col-xs-5">
								<h5>
									<small>FOLLOWERS</small>
									<a href="#"><?php echo $user_info->followers_count?></a>
								</h5>
							</div>
					</div>
					
				</div>
			</div>

			<div class="panel panel-default panel-custom">
				<div class="panel-heading">
					<h3 class="panel-title">
						Wants To Tweet ?
						
					</h3>
				</div>

				<div class="panel-body">
				<?php 
					//show tweet form
					echo '<div class="tweet_box">';
					echo '<form method="post" action="index.php"><table width="200" border="0" cellpadding="3">';
					echo '<tr>';
					echo '<td><textarea name="updateme" cols="60" rows="4"></textarea></td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td><input type="submit" value="Tweet" /></td>';
					echo '</tr></table></form>';
					echo '</div>';
				?>
				</div>
			</div>
		</div>
		
		
		
		
		<!-- Slide attach here-->
		

<div class="col-sm-6" style="margin-left:-20px;margin-top:20px">
			<div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:850px;height:475px;overflow:hidden;visibility:hidden;">
        <!-- Loading Screen -->
        <div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
            <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="../svg/loading/static-svg/spin.svg" />
        </div>
        <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:850px;height:380px;overflow:hidden;">
           
			<?php 
				$statuses = $connection->get('statuses/user_timeline', array('screen_name' => $screen_name,"count" => 10));
				foreach ($statuses  as $key => $statuse)
				{
					$arrStatus = [];
					$arrStatus['$created_at']=$statuse->created_at;
					$arrStatus['$text'] = $statuse->text;
						//for URL
						foreach($statuse->entities->urls as $key=>$url)
						{
							$arrStatus['urls'][]=$url->url;
						}
						//for Media
						if(isset($statuse->entities->media))
						{
							foreach($statuse->entities->media as $key => $media)
							{
								$arrStatus['media']['type'][]=$media->type;
								$arrStatus['media']['url'][]=$media->media_url;
							}
						}
				?>
					<div style="background-color:transparent;">
				
					<!--For Description-->
					<div style="position:absolute;top:40px;width:100%;height:auto;z-index:0;font-size:22px;color:#000000;line-height:24px;text-align:center;padding:5px;box-sizing:border-box;color: black;font-family: sans-serif;font-variant: small-caps;background-color: white;margin-top: 20px">
					<?php echo "<center>".$statuse->text."</center><br />";?>
					</div>

					
					
					<!--for url display-->
					
					<?php
						
						if(isset($arrStatus['urls'][0]))
						{
							echo '<div style="position:absolute;width:100%;height:50px;z-index:0;font-size:20px;color:#000000;line-height:24px;text-align:center;padding:5px;box-sizing:border-box;background-color: white;margin-bottom:5px">';
							 echo "URL : "."<a href=".$arrStatus['urls'][0].">" . $arrStatus['urls'][0] . "</a>";
							
							echo '</div>';
						}
					?>
					
					
					<?php 
					//for media Display
						if(isset($arrStatus['media']['type'][0]))
						{
					?>
					   <img style="height:200px;width:200;margin-top:160px;margin-left:200px;border-radius: 5%" src="<?php echo $arrStatus['media']['url'][0]; ?>"> 
					<?php
						}		
					?>
												
					<div data-u="thumb"><?php echo "Created date & time :".date('d/m/Y H:i:s',strtotime($statuse->created_at));?></div>
					</div>

		
			<?php
					
					 
				}
			?>
		
        </div>

        <!-- Thumbnail Navigator -->
        <div data-u="thumbnavigator" style="position:absolute;bottom:0px;left:0px;width:980px;height:50px;color:#FFF;overflow:hidden;cursor:default;background-color:rgba(0,0,0,.5);">
            <div data-u="slides">
                <div data-u="prototype" style="position:absolute;top:0;left:0;width:980px;height:50px;">
                    <div data-u="thumbnailtemplate" style="position:absolute;top:0;left:0;width:100%;height:100%;font-family:verdana;font-weight:normal;line-height:50px;font-size:16px;padding-left:10px;box-sizing:border-box;"></div>
                </div>
            </div>
        </div>
        <!-- Arrow Navigator -->
        <div data-u="arrowleft" class="jssora061" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
            <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <path class="a" d="M11949,1919L5964.9,7771.7c-127.9,125.5-127.9,329.1,0,454.9L11949,14079"></path>
            </svg>
        </div>
        <div data-u="arrowright" class="jssora061" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
            <svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <path class="a" d="M5869,1919l5984.1,5852.7c127.9,125.5,127.9,329.1,0,454.9L5869,14079"></path>
            </svg>
        </div>
    </div>
    <script type="text/javascript">jssor_1_slider_init();</script>
    <!-- #endregion Jssor Slider End -->

    
    	<div class="panel panel-default panel-custom" style="margin-top: 80px;margin-left: 20px;">
				<div class="panel-heading">
					<h3 class="panel-title">
						Some Help Here
					</h3>
				</div>

				<div class="panel-body">
				<form method="post" action="downloadcompleate.php">
					<input type="text" class="form-control-nav" name="input_value" placeholder="@Name Of the user">
				
					<button class="btn btn-primary" type="submit" name="Create_csv" aria-label="Left Align">
					<span  type="submit" name="Create_csv" class="" aria-hidden="true"> </span> Dowload Followers 
				</button>
				</form>
	
	

				<form method="post" action="index.php">
					<button class="btn btn-primary" type="submit" name="logout" aria-label="Left Align" >
					Logout
					</button>
			</form>

				</div>
			</div>

	</div>
	<!--slide done-->

		
		
		
		<div class="col-sm-3" style="margin-left:15px">
			<div class="panel panel-default panel-custom">
				<div class="panel-heading">
					<h3 class="panel-title">
						Followers
						<small><a href="#">Refresh</a> ● <a href="#">View all</a></small>
					</h3>
				</div>
				<div class="panel-body">
				<?php
				
				$result = $connection->get('followers/list', array('count' => 10, 'screen_name' => $screen_name));
				
					
					foreach($result->users as $user)
					{
				?>
					<div class="media" style="border-style: outset;">
						<div class="media-left">
							<img src="<?php print_r($user->profile_image_url); ?>" height="32px" width="32px" alt="" class="media-object img-rounded">
						</div>
						<div class="media-body">
							<h4 class="media-heading" style="margin-top:5px"><?php echo $user->name; ?></h4>
							
						</div>
					</div>
				<?php } ?>
				</div>
				<div class="panel-footer">
					<form method="post">
						<button class="btn btn-primary" type="submit" name="create_pdf" aria-label="Left Align">
						<span  type="submit" name="create_pdf" class="" aria-hidden="true"> </span> Download My Followers
						</button>
					</form>
				</div>
			</div>

		</div>
	</div>
		
</div>
</body>
<?php
	}					
	else
	{
	//Display login button
	echo '<a href="process.php"><img src="images/sign-in-with-twitter.png" width="151" height="24" border="0"/></a>';
	}
?>

</html>