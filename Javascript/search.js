$(document).ready(function () {
	$("#search").on("input", function () {
		var searchQuery = $(this).val();
		$.ajax({
			url: "../PHP/Activity-Page/activity.php",
			type: "GET",
			data: { query: searchQuery },
			success: function (data) {
				$("#results").html(data);
			},
		});
	});
});
