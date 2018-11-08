<?if(isset($_POST["pay"]) && $_POST["pay"] == 'bonuses'):?>
    console.log('GooD!');
<?elseif (isset($_POST["pay"]) && $_POST["pay"] == 'money'):?>
    <?echo 'this.result.TOTAL';?>
<?endif;?>