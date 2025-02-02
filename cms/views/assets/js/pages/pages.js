/*=============================================
Abrir ventana modal de páginas
=============================================*/
//cuando se dá click en un botón con clase .myPage*(en sidebar.php), se ejecuta el código dentro de la función
$(document).on("click",".myPage",function(){//$(document).on quiere decir que cuando el documento haya terminado de cargar, se ejcuta la función
	//se activa el modal de bootstrap con el id #myPage que se encuentra en pages.php
	$("#myPage").modal("show");
	//obtener datos del boton
	var page = $(this).attr("page");
	
	$("#myPage").on('shown.bs.modal',function(){

		if(page != undefined){

			/*=============================================
			Editar Página
			=============================================*/
			//{"id_page":4,"title_page":"Sucursales","url_page":"sucursales","icon_page":"bi bi-shop","type_page":"modules","order_page":1000,"date_created_page":"2025-02-01","date_updated_page":"2025-02-01 14:52:18"}
			$("#title_page").before(`

				<input type="hidden" id="id_page" name="id_page" value="${btoa(JSON.parse(page).id_page)}">

			`)
			//si el botón tiene datos, los llena automaticamente
			$("#title_page").val(JSON.parse(page).title_page);
			$("#url_page").val(JSON.parse(page).url_page);
			$("#icon_page").val(JSON.parse(page).icon_page);
			$("#type_page").val(JSON.parse(page).type_page);
		

		}else{
			
			$("#title_page").val('');
			$("#url_page").val('');
			$("#icon_page").val('');
			$("#type_page").val('');
		}

	})

})

/*=============================================
Cambiar orden de páginas
=============================================*/

$("#sortable").sortable({
	placeholder: 'sort-highlight',
	handle: '.handle',
	forcePlaceholderSize: true,
	zIndex:999999,
	out: function(event,ui){
		
		var listPage = $("#sortable li");
		var countList = 0;

		listPage.each((i)=>{

			var idPage = $(listPage[i]).attr("idPage");
			var index = i+1;

			var data = new FormData();
			data.append("idPage",idPage);
			data.append("index", index);
			data.append("token", localStorage.getItem("tokenAdmin"));

			$.ajax({

				url:"/ajax/pages.ajax.php",
				method:"POST",
				data:data,
				contentType:false,
				cache:false,
				processData:false,
				success: function(response){
					
					if(response == 200){

						countList++;

						if(countList == listPage.length){

							fncToastr("success", "El orden del menú ha sido actualizado con éxito");
						}
					}

				}

			})

		})

	}

})

/*=============================================
Eliminar una página
=============================================*/

$(document).on("click",".deletePage",function(){

	var idPage = $(this).attr("idPage");
	
	if(atob(idPage) == 1 || atob(idPage) == 2){

		fncToastr("error", "Esta página no se puede borrar");
		return;
	}

	fncSweetAlert("confirm", "¿Está seguro de borrar esta página?", "").then(resp=>{

		if(resp){
			
			var data = new FormData();
			data.append("idPageDelete",idPage);
			data.append("token", localStorage.getItem("tokenAdmin"));

			$.ajax({

				url:"/ajax/pages.ajax.php",
				method:"POST",
				data:data,
				contentType:false,
				cache:false,
				processData:false,
				success: function(response){
					
					if(response == 200){

						fncSweetAlert("success","La página ha sido eliminada con éxito",setTimeout(()=>location.reload(),1250));
					
					}else{

						fncToastr("error", "ERROR: La página tiene módulos vinculados");
					}
				}

			})

		}
	})

})