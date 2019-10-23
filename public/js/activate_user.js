$(document).ready( function () {
	$(".activation_form").validate({
		rules: {
			activation_code: {
				required: true
			},
		},

		messages: {
			activation_code: {
				required: "Введите код активации!",
			},
		},
		submitHandler: function (form) {

			$.ajax({
				url: '/register/activate_user', //url страницы (action_ajax_form.php)
				type: "GET", //метод отправки
				dataType: "html", //формат данных
				data: $('.activation_form').serialize(),  // Сеарилизуем объект
				success: function (response) { //Данные отправлены успешно
					result = $.parseJSON(response);
					if (result.error) {
						$("#errorModal").modal('show');
						$('.errors').html(result.error);
					} else if (result.msg == 'OK') {
						window.location.replace("/");
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

