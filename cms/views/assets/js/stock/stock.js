if($("#updateStock").length > 0){

	var data = new FormData();
	data.append("id_office_admin",$("#updateStock").val());
	data.append("token_admin", localStorage.getItem("tokenAdmin"));

	$.ajax({

		url:"/ajax/stock.ajax.php",
		method:"POST",
		data:data,
		contentType:false,
		cache:false,
		processData:false,
		success: function(response){
			
			loadMoreProducts(6,0,"all","");

		}

	})

}