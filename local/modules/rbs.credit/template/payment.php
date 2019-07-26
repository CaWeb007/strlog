<div class="com-rbs__resize" >

	<div class="com-rbs2__wrapper">
		<div class="com-rbs2__inner">
			<div class="com-rbs2__content">
				<div class="com-rbs2__row">
					<div class="com-rbs2__col _left">
						<?php if (in_array($params['rbs_result']['errorCode'], array(999, 1, 2, 3, 4, 5, 7, 8))) { ?>
							<span class="rbs__error-code"><?=getMessage("RBS_CREDIT_ERROR_TITLE");?><?=$params['rbs_result']['errorCode']?></span>
							<span class="rbs__error-message"><?=$params['rbs_result']['errorMessage']?></span>

						<?php } else { ?>

							<span class="com-rbs2__summ">
								<?= getMessage("COM_RBS_CREDIT_TITLE"); ?>: <br class="mobile_br">
								<span style="white-space: nowrap;">
									<b class="com-rbs2__summ-number"><?= number_format($params['ORDER_AMOUNT'], 2, '.', ' '); ?> </b>
									<img class="com-rbs2__ruble-img" src="/bitrix/images/rbs.credit/ruble-2.svg" alt="">
								</span>
							</span>

							<a href="<?=$params['payment_link']?>" class="com-rbs2__submit _credit-type-<?=$params['CREDIT_TYPE'];?>">
								<?php if( $params['CREDIT_TYPE'] == 2 ) { ?>
									<span><?=getMessage("RBS_CREDIT_NAME_TYPE_2");?></span>
								<?php } else { ?>
									<?=getMessage("RBS_CREDIT_NAME_TYPE_1");?>
								<?php }?>
							</a>

							<?php if( $params['CREDIT_TYPE'] == 2 ) { ?>
								<span class="com-rbs2__payment-min-pay">
									<?= getMessage("COM_RBS_CREDIT_MIN_PAY_1");?>
									<?php echo number_format(round($params['ORDER_AMOUNT'] / $params['CREDIT_MAX_MONTH'],2), 2, '.', ' '); ?> 
									<img class="com-rbs2__ruble-img" src="/bitrix/images/rbs.credit/ruble-2.svg" alt="">
									<?= getMessage("COM_RBS_CREDIT_MIN_PAY_2");?>
								</span>
							<?php } ?>

						<?php }?>
					</div>
					<div class="com-rbs2__col _right">
						<img class="com-rbs2__bank-logo" src="/bitrix/images/rbs.credit/bank_logo.svg" alt="">
					</div>
				</div>
			</div>
			<div class="com-rbs2__footer">
				<span class="com-rbs2__footer-text">
					<?= getMessage("COM_RBS_CREDIT_DESCRIPTION_TEXT");?>
					<a class="com-rbs2__descr-link" target="_blank" href="https://www.pokupay.ru/credit_terms"><?= getMessage("COM_RBS_CREDIT_DESCRIPTION_LINK");?></a>.
				</span>
			</div>
		</div>
	</div>	
</div>




<style>
	body .rbs__error-code {
		font-family: arial;
		color: red;
		font-size: 20px;
		display: block;
		margin-top:5px;
		margin-bottom: 7px;
	}
	body .rbs__error-message {
		font-family: arial;
		color:#000;
		font-size: 14px;
		display: block;
	}
	body .com-rbs2__wrapper {
		max-width: 530px;
		width: 100%;
		background: #fff;
		border:1px solid #ddd;
		border-radius: 4px;
		margin: 5px 0 20px;
	}
	body .com-rbs2__inner {}
	body .com-rbs2__row {
		display: flex;
		flex-wrap: nowrap;
		flex-direction: row;
	}
	body .com-rbs2__col {}
	body .com-rbs2__col._left {
		padding-right: 20px;
		width: 290px;
	}
	body .com-rbs2__col._right {
		margin-left: auto;
		width: auto;
	}
	body .com-rbs2__content {
		padding: 20px 20px 15px;
	}
	body .com-rbs2__footer {
		background: #ddd;
		padding: 10px 20px;
		margin: 0;
	}
	body .com-rbs2__footer-text {
		font-size: 12px;
		color: #000;
		line-height: 17px;
		display: block;
		font-family: arial;
	}
	body .com-rbs2__descr-link {
		font-size:12px;
		color: #000;
		text-decoration: underline;
		line-height: 17px;
		font-family: arial;
	}
	body .com-rbs2__descr-link:hover {
		text-decoration: none;
	}
	body .com-rbs2__summ {
		font-size: 20px;
		line-height: 24px;
		display: block;
		margin:0px 0 22px;
		font-family: arial;
	}
	.com-rbs2__summ-number {
		font-size: 22px;
		line-height: 24px;
		font-weight: block;
		font-family: arial;
	}
	body .com-rbs2__ruble-img {
	    width: 20px;
	    height: 17px;
	    display: inline-block;
	    vertical-align: top;
	    margin-top: 2px;
	}
	body .com-rbs2__submit {
		font-family: arial;
		font-size: 14px;
		line-height: 17px;
		background: url(/bitrix/images/rbs.credit/sber_logo_icon.svg) no-repeat 2px center;
		background-size: 44px;
		background-color: #00be64;
		color: #fff;
		padding: 0px 30px 0px 55px;
		margin: 0 0 4px 0;
		text-decoration: none !important;
		outline: none;
	    min-height: 44px;
	    display: flex;
	    border:none;
	    outline: none;
	    align-items: center;
	    min-width: 170px;
	    max-width: 300px;
	    width: auto;
	    font-size: 20px;
	    line-height: 22px;
	}
	body .com-rbs2__submit._credit-type-2 {
		padding-top: 2px;
		font-size: 18px;
		line-height: 15px;
	}
	body .com-rbs2__submit._credit-type-2 em {
		font-size: 12px;
		line-height: 15px;
		font-style: normal;
	}
	body .com-rbs2__submit:hover {
		background-color: #079d57;
		color: #fff;
		text-decoration: none !important;
	}
	body .com-rbs2__payment-min-pay {
		display: block;
		font-family: arial;
		font-size: 14px;
		line-height: 16px;
		font-weight: bold;
		color: #000;
		margin: 8px 0 0;
		padding:  0 0 0;
		text-align: left;
	}
	body .com-rbs2__payment-min-pay .com-rbs2__ruble-img {
		height: 12px;
		width: 12px;
		margin: 2px 0 0 0;
	}
	body .com-rbs2__bank-logo {
		max-width: 160px;
		width: 100%;
	}

	/* MOBILE */
	body ._mobile .com-rbs2__row {
		flex-direction: column;
	}	
	body ._mobile .com-rbs2__col._left {
		order:2;
		width: 100%;
		padding: 0;
	}	
	body ._mobile .com-rbs2__col._right {
		order:1;
		margin: 0 0 20px;
		padding: 0;
		width: 100%;
	}	
	body ._mobile .com-rbs2__wrapper {
		width: 100%;
		max-width: 340px;
	}
	body ._mobile .com-rbs2__submit {
		width: 100%;
		max-width: 100%;
	}



	body table {
		width: 100%;
	}
	body .ps_logo {
		display: none;
	}
	.mobile_br {display: none;}


</style>
<script>
	var rbs_wrap = document.getElementsByClassName('com-rbs__resize')[0];
	function changeViewMode(e) {
		var wrap_width = rbs_wrap.offsetWidth;
		if(wrap_width < 700) {
			rbs_wrap.classList.add("_mobile");
		} else {
			rbs_wrap.classList.remove("_mobile");
		}
	}
	changeViewMode();
	window.onresize = changeViewMode;
</script>