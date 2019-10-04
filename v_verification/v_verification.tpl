

{if $changed}
	<div class="alert-message error alert alert-block alert-danger">
		<p><strong>مشخصات تایید شده شما با مشخصات فعلی همخوانی ندارد:</strong><br/>
			به نظر می رسد شما شماره تلفن همراه خود را تغییر داده اید جهت تایید اطلاعات جدید بر روی کلید تایید مجدد کلیک نمایید.
		</p>
	</div>
{/if}
{if $erroralert eq 'true'}
	<div class="alert-message error alert alert-block alert-danger">
		<p><form id="resendform" method="POST" action="index.php?m=v_verification" class="form-inline pull-left">
			<input type="hidden" name="resend" value="sms" />
			<input id="submit" type="submit" class="btn btn-danger btn-small" value="ارسال مجدد پیامک"
				{if $smarty.post.resend eq 'sms' OR $smarty.session.resend}disabled{/if}
			/>
		</form>
		<strong>هنوز برخی از اطلاعات شما تایید نشده اند، قبل از ایجاد سفارش و خرید ابتدا باید اطلاعات زیر را تایید نمایید:</strong>
			<br/>
				{if $phoneverify eq 'on'}{if $send.sms}<strong>تایید شماره تلفن همراه:</strong> پیامی همراه با کد فعال سازی به شماره {$phone} ارسال شد.
				{/if}{/if}
		</p>
	</div>
{/if}
{if $erroralert eq 'true' or $send.sms}
	{if $send.sms}
		<div class="halfwidthcontainer logincontainer">
			<div class="page-header">
				<h1><span class="head-body">تایید تلفن همراه </span></h1>
			</div>
			{if $checkphone eq 'error'}
				<div class="alert-message error alert alert-block alert-danger">
					<p><strong>شماره تلفن شما موجود نمی باشد.</strong></p>
				</div>
			{/if}
			{if $checkphone eq '1' and $checkphonecode eq 'error'}
				<div class="alert-message error alert alert-block alert-danger">
					<p><strong>شماره تلفن شما در سیستم موجود می باشد اما کد فعال سازی اشتباست.</strong></p>
				</div>
			{/if}
			{if $phoneactive eq '1'}
				<div class="alert-message success alert alert-block alert-success">
					<p><strong>شماره تلفن همراه شما تایید شد.</strong><a class="btn btn-small" href="index.php">بازگشت</a></p>
				</div>
			{/if}
			{if $phoneactive eq '1'}{else}
			<p>جهت تایید شماره تلفن همراه خود کد ارسال شده را در قسمت زیر وارد نمایید، این کد چهار رقمی می باشد:</p>

			<form class="form-inline text-center" method="post" action="index.php?m=v_verification&phone">
					<div class="form-group">
	            <label for="inputEmail">کد فعال سازی:</label>
								<input type="text" name="phonecode" id="phonecode" value="" class="form-control">
	        </div>
					<input type="submit" class="btn btn-success" value="فعال سازی" />
				<br>
			</form>
			{/if}
		</div>
	{/if}
{/if}
{if $success}
	<div class="alert-message success alert alert-block alert-success">
		<p><strong>تمامی اطلاعات شما تایید شده است.</strong> <a class="btn btn-small" href="index.php">بازگشت به صفحه اصلی</a></p>
	</div>
{/if}

<script type="text/javascript">
$(document).ready(function () {

    $("#resendform").submit(function () {
        $("#submit").attr("disabled", true);
        return true;
    });
		{if $smarty.post.resend eq 'sms' OR $smarty.session.resend}
		setTimeout(function() {
      $('#submit').removeAttr('disabled');
    }, 300000)
		{/if}
});
</script>
