/*=============================================
JD SLIDER
=============================================*/

$(".jd-slider").jdSlider({

	wrap: '.slide-inner',
	slideShow: 4,
	slideToScroll: 2,
	isLoop: true,
	responsive: [{
		viewSize: 768,
		settings: {
			slideShow: 1,
			slideToScroll: 1
		}
	}]
	
});

/*=============================================
CARGAR MÁS PRODUCTOS
=============================================*/

$(document).on("click","#loadPageProducts",function(){

	if(Number($("#currentPageProducts").val()) <  Number($("#totalPagesProducts").val())){

		var nextPage = Number($("#currentPageProducts").val()) + 1;

		if(Number($("#totalPagesProducts").val()) == nextPage){

			$("#loadPageProducts").addClass("d-none");
			$("#loadPageProducts").removeClass("d-block");
		}

		$("#currentPageProducts").val(nextPage);

		var limit = Number($("#limitProduct").val());
		var startAt = (nextPage*limit)-limit;
		var category = $("#filterByCategory").val();

		loadMoreProducts(limit,startAt,category,search);

	}else{

		$("#loadPageProducts").addClass("d-none");
		$("#loadPageProducts").removeClass("d-block");
	}

})

/*=============================================
FILTRAR PRODUCTOS POR CATEGORÍAS
=============================================*/

$(document).on("click",".loadCategory",function(){

	var category = $(this).attr("idCategory");
	$("#filterByCategory").val(category);
	
	var limit = Number($("#limitProduct").val());
	var startAt = 0;
	$("#currentPageProducts").val(1);

	loadMoreProducts(limit,startAt,category,search);

})

/*=============================================
FILTRAR PRODUCTOS POR BÚSQUEDA
=============================================*/

$(document).on("keyup","#searchProduct",function(){

	var search = $(this).val();

	var limit = Number($("#limitProduct").val());
	var startAt = 0;
	$("#currentPageProducts").val(1);
	var category = $("#filterByCategory").val();

	loadMoreProducts(limit,startAt,category,search);
})

/*=============================================
FUNCIÓN PARA CARGAR MÁS PRODUCTOS
=============================================*/

function loadMoreProducts(limit,startAt,category,search){

	if(search == ""){

		fncSweetAlert("loading", "Cargando productos...", "");

	}

	var data = new FormData();
	data.append("limit", limit);
	data.append("startAt", startAt);
	data.append("category",category);
	data.append("search",search);
	data.append("idOffice", $("#idOffice").val());

	$.ajax({

		url:"/ajax/pos.ajax.php",
		method:"POST",
		data:data,
		contentType: false,
		cache: false,
		processData: false,
		success: function(response){
			
			if(JSON.parse(response).htmlProducts != ""){

				if(startAt == 0){

					$(".viewProducts").html(JSON.parse(response).htmlProducts);

				}else{

					$(".viewProducts").append(JSON.parse(response).htmlProducts);

				}

				if(JSON.parse(response).totalPagesProducts > 1 && $("#currentPageProducts").val() < JSON.parse(response).totalPagesProducts){

					$("#loadPageProducts").removeClass("d-none");
					$("#loadPageProducts").addClass("d-block");

				}

				if(JSON.parse(response).totalPagesProducts <= 1 && $("#currentPageProducts").val() == 1){

					$("#loadPageProducts").addClass("d-none");
					$("#loadPageProducts").removeClass("d-block");

				}

			}

			fncSweetAlert("close", "", "");

		}

	})

} 