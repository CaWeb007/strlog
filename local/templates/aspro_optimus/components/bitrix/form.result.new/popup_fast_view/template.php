<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<a href="#" class="close jqmClose"><i></i></a>
<div class="form">
	<div class="form_head">
		<?if($arResult["isFormTitle"] == "Y"):?>
			<h2><?=$arResult["FORM_TITLE"]?></h2>
		<?endif;?>
	</div>
	<script>
		var objUrl = parseUrlQuery(),
			add_url = '?FAST_VIEW=Y';
		if('clear_cache' in objUrl)
		{
			if(objUrl.clear_cache == 'Y')
				add_url += '&clear_cache=Y';
		}
		BX.ajax({
			url: '<?=$_REQUEST["item_href"]?>'+add_url,
			method: 'POST',
			data: BX.ajax.prepareData({'FAST_VIEW':'Y'}),
			dataType: 'html',
			processData: false,
			start: true,
			headers: [{'name': 'X-Requested-With', 'value': 'XMLHttpRequest'}],
			onfailure: function(data) {
				alert('Error connecting server');
			},
			onsuccess: function(html){
				var ob = BX.processHTML(html);
				// inject
				BX('fast_view_item').innerHTML = ob.HTML;
				BX.ajax.processScripts(ob.SCRIPT);
				$('#fast_view_item').closest('.form').addClass('init');
				$('.fast_view_frame h2').html($('#fast_view_item .title.hidden').html());

				initCountdown();
				InitZoomPict($('#fast_view_item .zoom_picture'));
				setStatusButton();
				InitFlexSlider();

				/*setTimeout(function(){
					showTotalSummItem('Y');
				}, 100);*/
				
				$(window).scroll();
			}
		})
		$('.jqmClose').on('click', function(e){
			e.preventDefault();
			$(this).closest('.jqmWindow').jqmHide();
		})
	</script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').validate({
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').valid() ){
					setTimeout(function() {
						$(form).find('button[type="submit"]').attr("disabled", "disabled");
					}, 300);

					var eventdata = {type: 'form_submit', form: form, form_name: '<?=$arResult["arForm"]["VARNAME"]?>'};
					BX.onCustomEvent('onSubmitForm', [eventdata]);

					// form.submit();
				}
			},
			errorPlacement: function( error, element ){
				error.insertBefore(element);
			},
			messages:{
				licenses_popup: {
					required : BX.message('JS_REQUIRED_LICENSES')
				}
			}
		});

		if(arOptimusOptions['THEME']['PHONE_MASK'].length){
			var base_mask = arOptimusOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
			$('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').inputmask('mask', {'mask': arOptimusOptions['THEME']['PHONE_MASK'] });
			$('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
					}
				}
			});
		}
		$('.popup').jqmAddClose('a.jqmClose');
		$('.popup').jqmAddClose('button[name="web_form_reset"]');
	});
	</script>
	
	<div id="fast_view_item"><div class="loading_block"></div></div>
</div>