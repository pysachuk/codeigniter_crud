$(document).ready( function () {
	$(".validate").validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 6,
			},
		},

		messages: {
			email: {
				required: "Заполните поле E-mail",
				email: "Ваш e-mail должен быть в форме name@domain.com"
			},
			password:{
				required: "Введите пароль",
				minlength: "Пароль должен быть не менее 6 символов",
			},
		},
		submitHandler: function(form) {

			$.ajax({
				url: '/auth/login/', //url страницы (action_ajax_form.php)
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
					else if (result.msg == 'activate')
					{
						window.location.replace("/register/activate");
					}
					else if(result.msg == 'OK')
					{
						window.location.replace("/");
					}

				},
				error: function (response) { // Данные не отправлены
					$("#errorModal").modal('show');
					$('.errors').html('Ошибка. Данные не отправлены!');
				}
			});

		}
	});
});
