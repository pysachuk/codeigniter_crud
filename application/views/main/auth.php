<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<script src="<?=base_url('/public/js/auth.js');?>"></script>
<link rel="stylesheet" href="<?=base_url('/public/css/auth_form.css');?>">

<div class="container">
	<div class="row">

		<div class="col-md-offset-3 col-md-6">
			<form action="/auth/login" method="POST" class="validate form-horizontal">
				<span class="heading">АВТОРИЗАЦИЯ</span>
				<div class="form-group">
					<input name="email" type="email" class="email form-control" id="inputEmail" placeholder="E-mail">
					<i class="fa fa-user"></i>
				</div>
				<div class="form-group help">
					<input name="password" type="password" class="password form-control" id="inputPassword" placeholder="Пароль">
					<i class="fa fa-lock"></i>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-default">ВХОД</button>
				</div>
				<div class="form-group">
					<a href="/register" class="btn btn-default"> РЕГИСТРАЦИЯ</a>
				</div>
			</form>


		</div>

	</div><!-- /.row -->
</div><!-- /.container -->

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

