$(document).ready( function () {

	var table = $('#table').DataTable(
		{
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
			},
			dom: 'Bfrtip',
			buttons: [
				{
					text: 'Добавить пользователя',
					className: 'btn btn-success',
					action: function ( e, dt, node, config ) {

						$("#addModal").modal('show');
						validate_add();

					}
				}

			],
			columns: [
				{ data: 'id'},
				{ data: 'name'},
				{ data: 'email'},
				{ data: 'activation'},
				{ data: "edit",
					"defaultContent": '<button class="edit btn btn-primary btn-sm glyphicon glyphicon-pencil"></button>' +
						'<button class="delete btn btn-danger btn-sm glyphicon glyphicon-trash"></button>',
					"targets": -1
				},
			]
		}
	);

	//EDIT
	function validate_edit()
	{
		$(".edit_user").validate({

			rules: {
				name: {
					required: true,
					minlength: 2,
				},
				email: {
					required: true,
					email: true
				},
				password: {
					minlength: 6,
				},
				password_confirmation: {
					equalTo: ".password"
				}
			},

			messages: {
				name:{
					required: "Это поле обязательно для заполнения",
					minlength: "Логин должен быть минимум 2 символа",
					maxlength: "Максимальное число символов - 16"
				},
				email: {
					required: "Заполните поле E-mail",
					email: "Ваш e-mail должен быть в форме name@domain.com"
				},
				password:{
					required: "Введите пароль",
					minlength: "Пароль должен быть не менее 6 символов",
				},
				password_confirmation:{
					required: "Подтвердите пароль",
					equalTo: "Пароли не совпадают"
				}
			},
			submitHandler: function(form) {
				user_id = $('.user_id').attr('value');
				$.ajax({
					url: '/main/edit_user/'+user_id, //url страницы
					type: "POST", //метод отправки
					dataType: "html", //формат данных
					data: $('.edit_user').serialize(),  // Сеарилизуем объект
					success: function (response) { //Данные отправлены успешно
						result = $.parseJSON(response);
						if(result.error)
						{
							$("#errorModal").modal('show');
							$('.errors').html(result.error);
						}
						else if(result.user)
						{
							user = result.user;
							$("#editModal").modal('hide');

							$("#"+user.id+' .username').html(user.name);
							$("#"+user.id+' .useremail').html(user.email);

							if(user.is_active == 1)
								$("#"+user.id+' .isactive').html('<p style="color: #0000FF">Активирован</p>');
							else
								$("#"+user.id+' .isactive').html('<p style="color: #990000">Не активирован</p>');

							$("#successModal").modal('show');
							$('.success').html('Пользователь отредактирован!');


						}

					},
					error: function (response) { // Данные не отправлены
						$("#errorModal").modal('show');
						$('.errors').html('Ошибка. Данные не отправлены!');
					}
				});

			}
		});
	}


	$(".edit").click(function () {
		user_id = $(this).parent().parent().attr("id");
		validate_edit();
		$.ajax({
			url: '/main/edit_user/'+user_id, //url страницы
			type: "GET", //метод отправки
			dataType: "html", //формат данных
			success: function (response) { //Данные отправлены успешно
				result = $.parseJSON(response);
				if(result.error)
				{
					$("#errorModal").modal('show');
					$('.errors').html(result.error);
				}
				else if(result.user)
				{
					user = result.user;
					$("#editModal").modal('show');
					$("#editModal .user_id").attr('value', user.id);
					$("#editModal .name").attr('value', user.name);
					$("#editModal .email").attr('value', user.email);

					if(user.is_active == 1)
						$("#editModal .active").attr('checked', 'checked');
					else
						$("#editModal .active").removeAttr("checked")
				}

			},
			error: function (response) { // Данные не отправлены
				$("#errorModal").modal('show');
				$('.errors').html('Ошибка. Данные не отправлены');
			}
		});
	});


	//DELETE
	$(".delete").click(function () {

		user_id = $(this).parent().parent().attr("id");

		bootbox.confirm({
			message: "Вы уверены??",
			buttons: {

				cancel: {
					label: 'Нет',
					className: 'btn-danger'
				},
				confirm: {
					label: 'Да',
					className: 'btn-success'
				},
			},
			callback: function (result) {
				if(!result)
					bootbox.hideAll()
				else
					delete_user();
			}
		});

		function delete_user()
		{
			$.ajax({
				url: '/main/delete_user/'+user_id, //url страницы
				type: "GET", //метод отправки
				dataType: "html", //формат данных
				success: function (response) { //Данные отправлены успешно
					result = $.parseJSON(response);
					if(result.error)
					{
						$("#errorModal").modal('show');
						$('.errors').html(result.error);
					}
					else if(result.msg == 'OK')
					{
						$('tr#'+user_id).fadeOut();
					}

				},
				error: function (response) { // Данные не отправлены
					$("#errorModal").modal('show');
					$('.errors').html('Ошибка. Данные не отправлены');
				}
			});
		}

	});

	//ADD USER

	function validate_add()
	{
		$(".add_user").validate({

			rules: {
				name: {
					required: true,
					minlength: 2,
				},
				email: {
					required: true,
					email: true
				},
				password: {
					required: true,
					minlength: 6,
				},
				password_confirmation: {
					required: true,
					equalTo: ".add_password"
				}
			},

			messages: {
				name:{
					required: "Это поле обязательно для заполнения",
					minlength: "Логин должен быть минимум 2 символа",
					maxlength: "Максимальное число символов - 16"
				},
				email: {
					required: "Заполните поле E-mail",
					email: "Ваш e-mail должен быть в форме name@domain.com"
				},
				password:{
					required: "Введите пароль",
					minlength: "Пароль должен быть не менее 6 символов",
				},
				password_confirmation:{
					required: "Подтвердите пароль",
					equalTo: "Пароли не совпадают"
				}
			},
			submitHandler: function(form) {
				$.ajax({
					url: '/main/add_user/', //url страницы
					type: "POST", //метод отправки
					dataType: "html", //формат данных
					data: $('.add_user').serialize(),  // Сеарилизуем объект
					success: function (response) { //Данные отправлены успешно
						result = $.parseJSON(response);
						if(result.error)
						{
							$("#errorModal").modal('show');
							$('.errors').html(result.error);
						}
						else if(result.user)
						{
							user = result.user;
							$("#addModal").modal('hide');

							if(user.is_active == 1)
								var activation = '<p style="color: #0000FF">Активирован</p>';
							else
								var activation = '<p style="color: #990000">Не активирован</p>';

							table.row.add( {
								"id":       user.id,
								"name":   user.name,
								"email":     user.email,
								"activation": activation,
								"edit":     '<button class="edit btn btn-primary btn-sm glyphicon glyphicon-pencil"></button>' +
								 	'<button class="delete btn btn-danger btn-sm glyphicon glyphicon-trash"></button>',
							} ).draw();


							$("#successModal").modal('show');
							$('.success').html('Пользователь добавлен!');


						}

					},
					error: function (response) { // Данные не отправлены
						$("#errorModal").modal('show');
						$('.errors').html('Ошибка. Данные не отправлены!');
					}
				});

			}
		});
	}


} );
