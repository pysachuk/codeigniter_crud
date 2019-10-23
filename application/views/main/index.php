<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script src="<?=base_url('/public/js/index.js');?>"></script>
<link rel="stylesheet" href="<?=base_url('/public/css/index.css');?>">


<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Test PROJECT</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="/">Главная</a></li>
				<li><a href="/auth/logout">Выход</a></li>

			</ul>
			<div class="user_email">Вы вошли как: <?=$user_email?></div>
		</div><!--/.nav-collapse -->
	</div>
</div>

<br>
<div class="container">
<table id="table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
			<th>Имя</th>
			<th>E-mail</th>
			<th>Активация</th>
			<th>Редактировать</th>
        </tr>
    </thead>
    <tbody class="tbody">
		<?php foreach ($users as $user) : ?>
        <tr id="<?=$user -> id?>">
            <td class="id"><?= $user -> id ?> </td>
            <td class="username"><?= $user -> name ?></td>
			<td class="useremail"><?= $user -> email ?></td>
			<td class="isactive"><?= ($user -> is_active) ? '<p style="color: #0000FF">Активирован</p>' : '<p style="color: #990000">Не активирован</p>' ?></td>
			<td>
				<button class="edit btn btn-primary btn-sm glyphicon glyphicon-pencil"></button>
				<button class="delete btn btn-danger btn-sm glyphicon glyphicon-trash"></button>
			</td>
        </tr>
		<?php endforeach; ?>
    </tbody>
</table>

</div>


<div id="addModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Добавить пользователя</h4>
			</div>
			<div class="modal-body">
				<form class="add_user" method="POST" action="#">
					<div class="form-group row">
						<label for="add_username" class="col-xs-2 col-form-label">Имя:</label>
						<div class="col-xs-10">
							<input name="name" class="add_name form-control" type="text" placeholder="Имя"  id="add_username">
						</div>
					</div>
					<div class="form-group row">
						<label for="add_user_email" class="col-xs-2 col-form-label">Email:</label>
						<div class="col-xs-10">
							<input name="email" class="add_email form-control" type="text" placeholder="Email"  id="add_user_email">
						</div>
					</div>

					<div class="form-group row">
						<label for="add_active_user" class="col-xs-2 col-form-label">Активирован:</label>
						<div class="col-xs-10">
							<input name="is_active" class="add_active form-check-input" type="checkbox" id="add_active_user">
						</div>
					</div>

					<div class="form-group row">
						<label for="add_user_password" class="col-xs-2 col-form-label">Пароль:</label>
						<div class="col-xs-10">
							<input name="password" class="add_password form-control" type="password" id="add_user_password" placeholder="Новый пароль">
						</div>
					</div>

					<div class="form-group row">
						<label for="add_password_confirmation" class="col-xs-2 col-form-label">Подтвердите пароль:</label>
						<div class="col-xs-10">
							<input name="password_confirmation" class="add_password_confirmation form-control" type="password" id="add_password_confirmation"
								   placeholder="Подтвердите пароль">
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				<button type="submit" class="btn btn-primary">Сохранить изменения</button>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="editModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="title modal-title">Редактирование пользователя</h4>
			</div>
			<div class="modal-body">
				<form class="edit_user" method="POST" action="#">
					<div class="form-group row">
						<label for="username" class="col-xs-2 col-form-label">Имя:</label>
						<div class="col-xs-10">
							<input class="user_id" type="hidden" value="">
							<input name="name" class="name form-control" type="text" placeholder="Имя"  id="username">
						</div>
					</div>
					<div class="form-group row">
						<label for="user_email" class="col-xs-2 col-form-label">Email:</label>
						<div class="col-xs-10">
							<input name="email" class="email form-control" type="text" placeholder="Email"  id="user_email">
						</div>
					</div>

					<div class="form-group row">
						<label for="active_user" class="col-xs-2 col-form-label">Активирован:</label>
						<div class="col-xs-10">
							<input name="is_active" class="active form-check-input" type="checkbox" id="active_user">
						</div>
					</div>

					<div class="form-group row">
						<label for="user_password" class="col-xs-2 col-form-label">Новый пароль:</label>
						<div class="col-xs-10">
							<input name="password" class="password form-control" type="password" id="user_password" placeholder="Новый пароль">
						</div>
					</div>

					<div class="form-group row">
						<label for="password_confirmation" class="col-xs-2 col-form-label">Подтвердите пароль:</label>
						<div class="col-xs-10">
							<input name="password_confirmation" class="password_confirmation form-control" type="password" id="password_confirmation"
								   placeholder="Подтвердите пароль">
						</div>
					</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				<button type="submit" class="btn btn-primary">Сохранить изменения</button>
				</form>
			</div>
		</div>
	</div>
</div>



<div id="errorModal" class="modal fade" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="errors alert alert-danger" role="alert">

				</div>

			</div>
		</div>
	</div>
</div>

<div id="successModal" class="modal fade" >
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				<div class="success alert alert-success" role="alert">

				</div>

			</div>

		</div>
	</div>
</div>

