$(document).ready( function () {
$(".validate").validate({

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
		$.ajax({
			url: '/register/add_user/', //url страницы (action_ajax_form.php)
			type: "POST", //метод отправки
			dataType: "html", //формат данных
			data: $('.validate').serialize(),  // Сеарилизуем объект
			success: function (response) { //Данные отправлены успешно
				result = $.parseJSON(response);
				if(result.error)
				{
					$("#errorModal").modal('show');
					$('.errors').html(result.error);
				}
				else if(result.msg == 'OK')
				{
					//Тут будет модальное окно с формой активации аккаунта
					window.location.replace("/");
					// alert('OK');
				}

			},
			error: function (response) { // Данные не отправлены
				$("#errorModal").modal('show');
				$('.errors').html('Ошибка. Данные не отправлены!');
			}
		});

	}
})
	});
