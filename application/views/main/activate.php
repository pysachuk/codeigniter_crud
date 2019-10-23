<script src="<?=base_url('/public/js/activate_user.js');?>"></script>
<div class="container">
	<div class="alert alert-info" role="alert">
		<p>Код активации отправлен на ваш EMAIL: <?=$email?>. Введите его здесь или перейдите по ссылке в письме.</p>
	</div>
<form action="#" method="get" class="activation_form">
	<div class="form-group">
		<label for="formGroupExampleInput">Введите код активации: </label>
		<input type="text" name="activation_code" class="form-control" id="formGroupExampleInput" placeholder="Код активации">
		<br>
		<div><button type="submit" class="submit btn btn-success">Активировать</button> </div>
		<br>
		<div><a class="btn btn-danger" href="/auth/logout">ВЫХОД</a></div>
	</div>

</form>
</div>
<div id="errorModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="errors alert alert-danger" role="alert">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>

