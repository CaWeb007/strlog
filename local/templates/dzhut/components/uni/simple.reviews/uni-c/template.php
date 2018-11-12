<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="site-reviews">
<div class="text-message"></div>

<form id="review-form" style="display:none;">
    <div class="form-group">
    <input type="text" class="form-control" id="author" placeholder="Ваше имя">
  </div>
 <div class="form-group">
	<textarea class="form-control" id="reviewText" rows="3" placeholder="Текст сообщения"></textarea>
  </div>

  	<button  class="btn btn-default" id="newReview1">Отправить</button>
</form>

<?foreach($arResult['ITEMS'] as $reviews):?>
<?
//edebug($reviews);
?>
	<div class="reivew-box">
		<div class="top-rev">
			<h4><?=$reviews['PROPERTY_ATT_AUTHOR_VALUE'];?></h4>
			<?$DateElems = explode(".",$reviews['PROPERTY_ATT_RANDOM_DATE_VALUE']);
				$ResUnixTime = mktime(0,0,0,$DateElems[1],$DateElems[0],$DateElems[2]);
				$CreateTime = CIBlockFormatProperties::DateFormat("d F Y", $ResUnixTime);
				$CreateTime;
			?>
			<span><?=$CreateTime?></span>

		</div>
		<div class="review-text"><?=$reviews['PROPERTY_ATT_TEXT_VALUE']['TEXT'];?></div>
		<?if($reviews['PROPERTY_ATT_COMMENT_VALUE']['TEXT'] != ""):?>
			<div class="review-comment-author"><?=$reviews['PROPERTY_ATT_AUTHOR_COMMENT_VALUE'];?></div>
			<div class="review-comment"><?=$reviews['PROPERTY_ATT_COMMENT_VALUE']['TEXT'];?></div>
		<?endif;?>
	</div>
<?endforeach;?>

<?if(!empty($arResult['NAV_STRING'])):?>
	<?=$arResult['NAV_STRING'];?>
<?endif;?>
</div>
<?
$dateRev = date('d.m.Y');
?>
<div class="vk-rwviews">
	<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script>

<script type="text/javascript">
  VK.init({apiId: 5595507, onlyWidgets: true});
</script>

<!-- Put this div tag to the place, where the Comments block will be -->
<div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 10, width: "800", attach: "*"});
</script>
</div>

<script>
	$(document).ready(function(){
		$(".vk-rwviews").hide();

		$("#seeVk").on('click',function(){
			$('.side-bar-right button').removeClass('active');
			$(this).addClass('active');
			$(".site-reviews").fadeOut();
			$(".vk-rwviews").fadeIn();
		})
		$("#seeRev").on('click',function(){
			$('.side-bar-right button').removeClass('active');
			$(this).addClass('active');
			$(".vk-rwviews").fadeOut();
			$(".site-reviews").fadeIn();

		})

		$("#writeRev").on('click',function(e){
			$('.side-bar-right button').removeClass('active');
				$(this).addClass('active');

			if($("#review-form").css('display') == 'block'){
				$(this).removeClass('active');
				$("#review-form").fadeOut();
			}else if($('.site-reviews').css('display') == 'none' && $("#review-form").css('display') == 'none'){
						$(".vk-rwviews").fadeOut();
						$("#review-form").fadeIn();
						$(".site-reviews").fadeIn();
			}
			else{
					if($('.site-reviews').css('display') == 'none'){
						$("#review-form").fadeIn();
						$(".site-reviews").fadeIn();
					}else if($('.site-reviews').css('display') == 'block'){
						$("#review-form").fadeIn();
					}

			}

		})
	$('#newReview1').on('click', function(e) {
			e.preventDefault();
			var reviewText;
			var author;
				$("#review-form .form-control").each(function(){
					if($(this).val()!=""){
						if($(this).attr("id") == "reviewText"){
							reviewText = $(this).val();
						}
						if($(this).attr("id") == "author"){
							author = $(this).val();
						}
					}
				})
				if(reviewText != undefined && author != undefined){
					uri = "<?=$templateFolder."/ajax.php"?>";
					site_id = "<?=SITE_ID?>";
					dateRew = "<?=$dateRev?>";
					//uri = "/ajax.php";
					$.ajax({
						url:uri,
						type:'post',
						beforeSend:function(){
							BX.showWait();
						},
						data:{author:author,reviewText:reviewText,site_id:site_id,dateRew:dateRew},
						success:function(data){
							$('#author').add($('#reviewText')).removeClass('error');

							if(data){
								var suc = "Ваш отзыв добавлен!";
								$(".site-reviews .text-message").text(suc);
								$(".site-reviews .text-message").addClass('active');
								setTimeout(function(){
									$(".site-reviews .text-message").text("");
									$(".site-reviews .text-message").removeClass('active');
									$("#review-form").fadeOut();
									$('.side-bar-right button').removeClass('active');
									$("#reviewText,#author").val("");
								},2500);
							}else{
								console.log(data);
							}
						},
						complete:function(){
							BX.closeWait();
						}
					})
				}

				if ( reviewText == undefined ) {
					$('#reviewText').addClass('error');
				} else {
					$('#reviewText').removeClass('error');
				}

				if ( author == undefined ) {
					$('#author').addClass('error');
				} else {
					$('#author').removeClass('error');
				}
		})

	})
</script>