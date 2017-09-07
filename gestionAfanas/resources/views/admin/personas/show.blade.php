<!DOCTYPE html>
<html lang="es">
<head>
	<meta name="csrf_token" content="{{ csrf_token() }}" />
	<meta charset="UTF-8">	
	<title>@yield('title', 'Eventos')</title>

	<link href="{{ asset('plugins/fullcalendar-3.1.0/fullcalendar.min.css') }}" rel='stylesheet' />
	<link href="{{ asset('plugins/fullcalendar-3.1.0/fullcalendar.print.min.css') }}" rel='stylesheet' media='print' />
	<script src="{{ asset('plugins/fullcalendar-3.1.0/lib/moment.min.js') }}"></script>
	<script src="{{ asset('plugins/fullcalendar-3.1.0/lib/jquery.min.js') }}"></script>
	<script src="{{ asset('plugins/fullcalendar-3.1.0/lib/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('plugins/fullcalendar-3.1.0/fullcalendar.min.js') }}"></script>
	<script src="{{ asset('plugins/fullcalendar-3.1.0/locale/es.js') }}"></script>

	<link rel="stylesheet" href="{{ asset('css/general.css') }}">

	<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">


	<link rel="stylesheet" href="{{ asset('plugins/chosen/chosen.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/trumbowyg/ui/trumbowyg.css') }}">
	<link rel="stylesheet" href="{{ asset('css/fullcalendar.css') }}">
	<link rel="stylesheet" href="{{ asset('css/estiloMensajeEliminar.css') }}">
	<link rel="stylesheet" href="{{ asset('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') }}">

	<!-- DATA TIME PICKER -->
	<link rel="stylesheet" href="{{ asset('plugins/clockpicker/dist/bootstrap-clockpicker.min.css') }}">
	<!-- **************** -->

<script>

$(function() {
    $('.clockpicker').clockpicker({
    	placement: 'top',
    	donetext: 'Aceptar'
    	//twelvehour: true
    });
  });

	var activarCalNoche = false;

	//deshabilitar click boton derecho
	$(document).ready(function () {
	    //Disable full page
	    $("body").on("contextmenu",function(e){
	        return false;
	    });
	    
	    //Disable part of page
	    //$("#eliminarEvento").on("contextmenu",function(e){
	        //return false;
	    //});
	});

	//fullcalendar
	$(function () {

		jQuery(function(){
		   jQuery('#calendarioEventos').click();
		});

		var pulsado = null;
		var botonActivado = false; //boton para que muestre y guarde la lista de eventos

		$('#calendarioEventos').on('click', function(e){
        	e.preventDefault();
        	calendarioActivo = "eventos";
        	$('#calendarNoche').fullCalendar('destroy');
        	
			/* initialize the external events
		     -----------------------------------------------------------------*/
		     var botonPulsado = null; //variable que almacena la id de la categoria que se ha pulsado.
		     //var colorFondo = botonPulsado;


	    	function ini_events(ele) {

	      		ele.each(function () {
	        		// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
	        		// it doesn't need to have a start or end
	        		var eventObject = {
	          			title: $.trim($(this).text()), // use the element's text as the event title
	          			//stick: true 
	        		};
	        		// store the Event Object in the DOM element so we can get to it later
	        		$(this).data('event', eventObject);
	        		// make the event draggable using jQuery UI
	        		$(this).draggable({
	          			zIndex: 1070,
	          			revert: true, // will cause the event to go back to its
	          			revertDuration: 0  //  original position after the drag
	        		});
	      		});
	    	}
	    	ini_events($('#external-events div.external-event'));


	    	//deshabilita la opcion de hacer click derecho por defecto dentro del calendario
	    	//$('#calendar:not(".fc-event")').on('contextmenu', function (e) {
			    //e.preventDefault()
			//})

		    /* initialize the calendar
		     -----------------------------------------------------------------*/
		    //Date for the calendar events (dummy data)
		    var date = new Date();
		    var d = date.getDate(),
		        m = date.getMonth(),
		        y = date.getFullYear();
	  
		    $('#calendar').fullCalendar({
		    	header: {
		        	left: 'prev,next today',
		        	center: 'title',
		        	right: 'prevYear,month,agendaWeek,agendaDay,nextYear'
		      	},
		      	buttonText: {
		        	today: 'hoy',
		        	month: 'mes',
		        	week: 'semana',
		        	day: 'dia',
		        	//prevYear: parseInt(new Date().getFullYear(), 10) - 1,
            		//nextYear: parseInt(new Date().getFullYear(), 10) + 1
		      	},

		      	//if(event.tipoEvento == 1)
		      	eventTextColor: 'black',

		    	events: { url:"/cargaEventos{{ $persona->id }}"},
			      
		      	editable: true,
		      	droppable: true, // this allows things to be dropped onto the calendar !!!

		      	drop: function (date, allDay) { // this function is called when something is dropped
		        	// retrieve the dropped element's stored Event Object
		        	var originalEventObject = $(this).data('event');
		        	// we need to copy it, so that multiple events don't have a reference to the same object
		        	var copiedEventObject = $.extend({}, originalEventObject);
		        	allDay=true;
		        	// assign it the date that was reported
		        	copiedEventObject.start = date;
		        	copiedEventObject.allDay = allDay;
		        	copiedEventObject.backgroundColor = $(this).css("background-color");

		        	// is the "remove after drop" checkbox checked?
		        	if ($('#drop-remove').is(':checked')) {
		          		// if so, remove the element from the "Draggable Events" list
		          		$(this).remove();
		        	}

		        	//Guardamos el evento creado en base de datos
		        	var persona = "{{ $persona->id }}";
		       		var tipoEvento = botonPulsado; //id del evento pulsado
		       		var momentoDia = "1"; //al momento de crearla lo ponemos a 1, luego se modifica.
		   	        var time = copiedEventObject.start.format("HH:mm");
		   	       	var start = copiedEventObject.start.format("YYYY-MM-DD HH:mm");
		        	var title = copiedEventObject.title;
		       		var back = copiedEventObject.backgroundColor;
		       		var categoriaSeleccionada = botonPulsado;

					$.ajax({
		            	url: '/guardaEventos',
		            	type: "POST",         
		            	beforeSend: function (xhr) {
		            		var token = $('meta[name="csrf_token"]').attr('content');

		            		if (token) {
		                		return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		            		}
		        		},

		        		data: 'id_persona='+persona+'&id_tipoEvento='+tipoEvento+'&id_momentoDia='+momentoDia+'&hora='+time+'&start='+start+'&allday='+allDay+'&title='+title+'&background='+back,
		            
		            	success: function(events) {
		                	console.log('Evento creado');      
		                	$('#calendar').fullCalendar('refetchEvents' );

		                	console.log("ID_PERSONA: "+persona);
		                	console.log("ID_TIPO_EVENTO: "+tipoEvento);
		                	console.log("ID_MOMENTO_DIA: "+momentoDia);
		                	console.log("HORA: "+time);
		                	console.log("START "+start);	
		                	console.log("ALL DAY: "+allDay);
		                	console.log("TITULO: "+title);      	
		                	console.log("BACKGROUND: "+back);
		            	},
		              	error: function(json){
		                	console.log("Error al crear evento");
		                	console.log("ID_PERSONA: "+persona);
		                	console.log("ID_TIPO_EVENTO: "+tipoEvento);
		                	console.log("ID_MOMENTO_DIA: "+momentoDia);
		                	console.log("HORA: "+time);
		                	console.log("FECHA INICIO "+start);	
		                	console.log("TODO EL DIA: "+allDay);
		                	console.log("TITULO: "+title);      	
		                	console.log("COLOR: "+back);
		                	console.log("BOTON PULSADO" +categoriaSeleccionada);
		            	}        
		        	});
	      		},

		    	eventResize: function(event) {
		    		var id = event.id;
		    		var persona = "{{ $persona->id }}";
		        	var start = event.start.format("YYYY-MM-DD HH:mm");
		          	var back = event.backgroundColor;
		          	var allDay = event.allDay;
		          	var texto = event.observ;
		          	var id_tipoEvento = event.id_tipoEvento;
		          	var id_momentoDia = event.id_momentoDia;
		          	var title = event.title;
			    	var hora = event.hora;
			    	var opcion = 1;
		          
		         	if(event.end){
		            	var end = event.end.format("YYYY-MM-DD HH:mm");
		          	}else{
		          		var end="NULL";
		          	}

		            $.ajax({
		            	url: '/actualizaEventos',         
		              	type: "POST",
		              	beforeSend: function (xhr) {
		            		var token = $('meta[name="csrf_token"]').attr('content');

		            		if (token) {
		                		return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		            		}
		        		},
		        
		        		data: 'id='+id+'&id_persona='+persona+'&id_tipoEvento='+id_tipoEvento+'&id_momentoDia='+id_momentoDia+'&hora='+hora+'&start='+start+'&end='+end+'&allday='+allDay+'&title='+title+'&background='+back+'&observ='+texto+'&opcion='+opcion,  
		             
		                success: function(json) {
		                	console.log("Updated Successfully");
		                },
		                error: function(json){
		                	console.log("Error al actualizar evento arrastrando la hora");
		                }
		            });
	    		},

			    eventDrop: function(event, delta) {		    	
			    	var id = event.id;
			    	var persona = "{{ $persona->id }}";
			    	var id_tipoEvento = event.id_tipoEvento;
			    	var id_momentoDia = event.id_momentoDia;
			    	var observ = event.observ;
			    	var title = event.title;
			    	var hora = event.hora;
			    	var start = event.start.format("YYYY-MM-DD HH:mm");
			    	var allDay = event.allDay;
			    	var opcion = 1; //para filtrar los eventos que tienen mas de una tabla
			    	var turnos = 1; //por defecto
			        
			        if(event.end){
			        	var end = event.end.format("YYYY-MM-DD HH:mm");
			        }else{
			        	var end="NULL";
			        }

			        var back=event.backgroundColor;
			        var texto = event.observ;

			        if(allDay == true)
			        	allDay = 1;
			        else
			        	allDay = 0;
		        
			        $.ajax({  
			        	url: '/actualizaEventos',
			            type: "POST",
			            beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},
			            data: 'id='+id+'&id_persona='+persona+'&id_tipoEvento='+id_tipoEvento+'&id_momentoDia='+id_momentoDia+'&hora='+hora+'&start='+start+'&end='+end+'&allday='+allDay+'&title='+title+'&background='+back+'&observ='+texto+'&opcion='+opcion,       
			     
			            success: function(json) {
			            	console.log("TODO EL DIA: " +allDay);
			            	console.log("Updated Successfully eventdrop");
			            	
			            },
			            error: function(json){
			            	console.log("Error al actualizar eventdrop, el texto es: " +title);
			            	//console.log("OPCION: " +opcion);
			            	//console.log("ID: " +id);
			            	//console.log("ID PERSONA: " +persona);
			            	//console.log("ID TIPO EVENTO: " +id_tipoEvento);
			            	//console.log("ID MOMENTO DIA: " +id_momentoDia);
			            	//console.log("FECHA INICIO: " +start);
			            	console.log("FECHA FIN: " +end);
			            	//console.log("HORA: " +hora);
			            	//console.log("ALLDAY: " +allDay);
			            	//console.log("BACKGROUND: " +back);
			            	//console.log("OBSERVACIONES: " +observ);
			            }
			        });
		    	},

		    	//muestra cada formulario del evento al hacer click sobre el
			    eventClick: function (event) {    

			    	//console.log(event.backgroundColor);
			    	//probando con background color, cambiar luego con id
			    	var bgColor = event.backgroundColor;
			    	//var colorVerde = "rgb(0, 166, 90)";

			    	/*
			    	* 1 - estado fisico 					rgb(0, 166, 90)
			    	* 2 - crisis epilepticas 				rgb(243, 156, 18)
			    	* 3 - apetito							rgb(0, 192, 239)
			    	* 4 - sueño								rgb(0, 115, 183) // rgb(255, 0, 0) ROJO
			    	* 5 - consultas y hospitalizaciones		rgb(57, 204, 204)
			    	* 6 - estado mental						rgb(210, 214, 222)
			    	* 7 - incidencias comportamentales		rgb(17, 17, 17)// NUEVO: rgb(255, 231, 6)
			    	* 8 - problemas de conducta				rgb(1, 255, 112)
			    	* 9 - higiene							rgb(221, 75, 57)
			    	* 10 - control de esfinteres			rgb(96, 92, 168)
			    	* 11 - correcion en la mesa 			rgb(255, 133, 27)
			    	* 12 - ocupacional 						rgb(210, 105, 30)
			    	* 13 - vivienda 						rgb(0, 139, 139)
			    	* 14 - relaciones sociales 				rgb(255, 20, 147)
			    	* 15 - humor bipolar 					rgb(255, 182, 193)
			    	*/

			    	switch(bgColor)
			    	{
			    		case "rgb(0, 166, 90)": //1 - estado fisico
			    			
			    			$('#modalTitle').html(event.title);
			    			$('#descripcionTexto').html(event.observ);
		           	 		$('#obtenerId').html(event.id);
		           	 		$('#obtenerFechaIni').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin').html("NULL");
				        	$('#obtenerTodoeldia').html(event.allDay);
		           	 		$('#obtenercolor').html(event.backgroundColor);
		           	 		$('#obtenertitulo').html(event.title);
		            		$('#fullCalModal').modal();

		            		break;

		            	case "rgb(243, 156, 18)": //2 - crisis epilepticas
	
		            		$('#modalTitle1').html(event.title);
			    			$('#descripcionTexto1').html(event.observ);
			    			//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));
		           	 		//mostramos la hora
		           	 		$('input.placeHora').prop("placeholder", event.start.format("HH:mm"));
		           	 		//cargamos la hora del reloj
		           	 		$('#ultimaToma1').val(event.ultimaToma);
		           	 		$('#crisis1').val(event.duracion);

		           	 		//RADIO BUTTONS
		           	 		var opcionPerdida = event.perdidaConciencia;
		           	 		
		           	 		switch (opcionPerdida)
		           	 		{
		           	 			case 1: //SI
		           	 				document.getElementById("perdida1").checked = true;
		           	 				break;
		           	 			case 0: //NO
		           	 				document.getElementById("perdida2").checked = true;
		           	 				break;
		           	 		}

		           	 		var opcionRelajacion = event.relajaEsfinteres;

		           	 		switch (opcionRelajacion)
		           	 		{
		           	 			case 1:
		           	 				document.getElementById("relajacion1").checked = true;
		           	 				break;
		           	 			case 0:
		           	 				document.getElementById("relajacion2").checked = true;
		           	 				break;
		           	 		}

		           	 		var opcionConvulsion = event.convulsiones;

		           	 		switch (opcionConvulsion)
		           	 		{
		           	 			case 1:
		           	 				document.getElementById("convulsion1").checked = true;
		           	 				break;
		           	 			case 0:
		           	 				document.getElementById("convulsion2").checked = true;
		           	 				break;
		           	 		}
		           	 		
		           	 		var opcionLesion = event.lesionesFisicas;

		           	 		switch (opcionLesion)
		           	 		{
		           	 			case 1:
		           	 				document.getElementById("lesion1").checked = true;
		           	 				break;
		           	 			case 0:
		           	 				document.getElementById("lesion2").checked = true;
		           	 				break;
		           	 		}
	
		           	 		$('#obtenerId1').html(event.id);
		           	 		$('#obtenerIdTipoEvento1').html(event.id_tipoEvento);
		           	 		$('#obtenerFechaIni1').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin1').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin1').html("NULL");


	           				$('#obtenerPerdida1').html(event.perdidaConciencia);
		           	 		$('#obtenerPerdida2').html(event.perdidaConciencia);
		           	 		$('#obtenerRelajacion1').html(event.relajaEsfinteres);
		           	 		$('#obtenerRelajacion2').html(event.relajaEsfinteres);
		           	 		$('#obtenerConvulsion1').html(event.convulsiones);
		           	 		$('#obtenerConvulsion2').html(event.convulsiones);
		           	 		$('#obtenerLesion1').html(event.lesionesFisicas);
		           	 		$('#obtenerLesion2').html(event.lesionesFisicas);

				        	$('#obtenerTodoeldia1').html(event.allDay);
		           	 		$('#obtenercolor1').html(event.backgroundColor);
		           	 		$('#obtenertitulo1').html(event.title);
		           	 		$('#obtenerDurCrisis1').html(event.duracion);
		            		$('#modal1').modal();

		            		break;
				        
		            	case "rgb(0, 192, 239)": //3 - apetito

		            		$('#modalTitle3').html(event.title);
			    			$('#descripcionTexto3').html(event.observ);
		           	 		$('#obtenerId3').html(event.id);
		           	 		$('#obtenerIdTipoEvento3').html(event.id_tipoEvento);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));

		           	 		//radio buttons
		           	 		var opcionRadio = event.id_momentoDia;

		           	 		switch (opcionRadio)
		           	 		{
		           	 			case 1:
		           	 				document.getElementById("desayuno3").checked = true;
		           	 				//$('#obtenerDesayuno3').html(event.id_momentoDia);
		           	 				break;
		           	 			case 2:
		           	 				document.getElementById("almuerzo3").checked = true;
		           	 				//$('#obtenerAlmuerzo3').html(event.id_momentoDia);	           	 				
		           	 				break;
		           	 			case 3:
		           	 				document.getElementById("cena3").checked = true;
		           	 				//$('#obtenerCena3').html(event.id_momentoDia);
		           	 				break;
		           	 		}


		           	 		$('#obtenerFechaIni3').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin3').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin3').html("NULL");
				        	$('#obtenerTodoeldia3').html(event.allDay);
		           	 		$('#obtenercolor3').html(event.backgroundColor);
		           	 		$('#obtenertitulo3').html(event.title);
		           	 		$('#obtenerDesayuno3').html(event.id_momentoDia);
		           	 		$('#obtenerAlmuerzo3').html(event.id_momentoDia);
		           	 		$('#obtenerCena3').html(event.id_momentoDia);

		            		$('#modalApetito').modal();

				        	break;

				        case "rgb(0, 115, 183)": //4 - sueño
				        	
				        	$('#modalTitle4').html(event.title);
				        	$('#numHoras').val(event.horasDormidas);
			    			$('#descripcionTexto4').html(event.observ);
		           	 		$('#obtenerId4').html(event.id);
		           	 		$('#obtenerIdTipoEvento4').html(event.id_tipoEvento);
		           	 		$('#obtenerFechaIni4').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin4').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin4').html("NULL");
				        	$('#obtenerTodoeldia4').html(event.allDay);
		           	 		$('#obtenercolor4').html(event.backgroundColor);
		           	 		$('#obtenertitulo4').html(event.title);
		           	 		$('#obtenerHorasDormidas4').html(event.horasDormidas);
		            		$('#modalSueño').modal();

				        	break;
			        
				        case "rgb(57, 204, 204)": //5 - consultas y hospitalizaciones

				        	$('#modalTitle5').html(event.title);
			    			$('#descripcionTexto5').html(event.observ);
		           	 		$('#obtenerId5').html(event.id);
		           	 		$('#obtenerIdTipoEvento5').html(event.id_tipoEvento);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));
		           	 		$('#obtenerFechaIni5').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin5').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin5').html("NULL");
				        	$('#obtenerTodoeldia5').html(event.allDay);
		           	 		$('#obtenercolor5').html(event.backgroundColor);
		           	 		$('#obtenertitulo5').html(event.title);
		           	 

				        	$('#modalHospital').modal();

				        	break;

				        case "rgb(210, 214, 222)": //6 - estado mental

				        	$('#modalTitle6').html(event.title);
			    			$('#descripcionTexto6').html(event.observ);
		           	 		$('#obtenerId6').html(event.id);
		           	 		$('#obtenerIdTipoEvento6').html(event.id_tipoEvento);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));
		           	 		$('#obtenerFechaIni6').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin6').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin6').html("NULL");
				        	$('#obtenerTodoeldia6').html(event.allDay);
		           	 		$('#obtenercolor6').html(event.backgroundColor);
		           	 		$('#obtenertitulo6').html(event.title);

				        	$('#modalMental').modal();

				        	break;

				        case "rgb(255, 231, 6)": //7 - incidencias comportamentales

				        	$('#modalTitle7').html(event.title);
		           	 		$('#obtenerId7').html(event.id);
		           	 		$('#obtenerIdTipoEvento7').html(event.id_tipoEvento);
		           	 		$('#obtenerFechaIni7').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin7').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin6').html("NULL");
		           	 		$('#obtenerTodoeldia7').html(event.allDay);
		           	 		$('#obtenercolor7').html(event.backgroundColor);
		           	 		$('#obtenertitulo7').html(event.title);

				        	//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));
		           	 		$('input.placeHoraIncidencia').prop("placeholder", event.start.format("HH:mm"));
		           	 		$('input.placeUsuario').prop("placeholder", "{{ $usuarioConectado }}");
		           	 		$('input.placeTipoIncidencia').prop("placeholder", event.title);

		           	 		$('#queAntes').html(event.antes);
		           	 		$('#queHizo').html(event.queHizo);
		           	 		$('#queDespues').html(event.despues);

							$('#modalComportamentales').modal();

							break;

						case "rgb(1, 255, 112)": //8 - problemas de conducta

							$('#modalTitle8').html(event.title);
			    			$('#descripcionTexto8').html(event.observ);
		           	 		$('#obtenerId8').html(event.id);
		           	 		$('#obtenerIdTipoEvento8').html(event.id_tipoEvento);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));
		           	 		$('#obtenerFechaIni8').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin8').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin8').html("NULL");
				        	$('#obtenerTodoeldia8').html(event.allDay);
		           	 		$('#obtenercolor8').html(event.backgroundColor);
		           	 		$('#obtenertitulo8').html(event.title);
		
							$('#modalConducta').modal();

							break;

						case "rgb(221, 75, 57)": //9 - higiene (= apetito)
						
							$('#modalTitle9').html(event.title);
			    			$('#descripcionTexto9').html(event.observ);
		           	 		$('#obtenerId9').html(event.id);
		           	 		$('#obtenerIdTipoEvento9').html(event.id_tipoEvento);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));

		           	 		//radio buttons
		           	 		if(event.turno != null)
		           	 		{
			           	 		var turnos = event.turno.split(',');
			           	 		var mañanaSi = false;
			           	 		var tardeSi = false;
			           	 		var nocheSi = false;

			           	 		//if(turnos != null)
			           	 		//{
				           	 		for(i=0; i < turnos.length; i++)
				           	 		{
				           	 			if(turnos[i] == "Mañana")
				           	 				mañanaSi = true;		       
				           	 			if(turnos[i] == "Tarde")
				           	 				tardeSi = true;
				           	 			if(turnos[i] == "Noche")
				           	 				nocheSi = true;
				           	 		}
				           	 	//}

				           	 	if(mañanaSi)
				           	 		document.getElementById("mañana9").checked = true;		
				           	 	else
				           	 		document.getElementById("mañana9").checked = false;

				           	 	if(tardeSi)
				           	 		document.getElementById("tarde9").checked = true;	
				           	 	else
				           	 		document.getElementById("tarde9").checked = false;	

				           	 	if(nocheSi)
				           	 		document.getElementById("noche9").checked = true;
				           	 	else
				           	 		document.getElementById("noche9").checked = false;
				           	 }
				           	 

		           	 		$('#obtenerFechaIni9').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin9').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin9').html("NULL");
				        	$('#obtenerTodoeldia9').html(event.allDay);
		           	 		$('#obtenercolor9').html(event.backgroundColor);
		           	 		$('#obtenertitulo9').html(event.title);
		           	 		//$('#obtenerDesayuno9').html(event.id_momentoDia);
		           	 		//$('#obtenerAlmuerzo9').html(event.id_momentoDia);
		           	 		//$('#obtenerCena9').html(event.id_momentoDia);

							$('#modalHigiene').modal();

							break;

						case "rgb(96, 92, 168)": //10 - control de esfinteres

							$('#modalTitle10').html(event.title);
			    			$('#descripcionTexto10').html(event.observ);
		           	 		$('#obtenerId10').html(event.id);
		           	 		$('#obtenerIdTipoEvento10').html(event.id_tipoEvento);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));

		           	 		if(event.turno != null)
		           	 		{
			           	 		//radio buttons
			           	 		var turnos = event.turno.split(',');
			           	 		var mañanaSi = false;
			           	 		var tardeSi = false;
			           	 		var nocheSi = false;

			           	 		//$('#modalTitle10').html(event.turno);

			           	 		if(turnos != null)
			           	 		{
				           	 		for(i=0; i < turnos.length; i++)
				           	 		{
				           	 			if(turnos[i] == "Mañana")
				           	 				mañanaSi = true;		       
				           	 			if(turnos[i] == "Tarde")
				           	 				tardeSi = true;
				           	 			if(turnos[i] == "Noche")
				           	 				nocheSi = true;
				           	 		}
				           	 	}

				           	 	if(mañanaSi)
				           	 		document.getElementById("mañana10").checked = true;		
				           	 	else
				           	 		document.getElementById("mañana10").checked = false;

				           	 	if(tardeSi)
				           	 		document.getElementById("tarde10").checked = true;	
				           	 	else
				           	 		document.getElementById("tarde10").checked = false;	

				           	 	if(nocheSi)
				           	 		document.getElementById("noche10").checked = true;
				           	 	else
				           	 		document.getElementById("noche10").checked = false;
				           	}

		           	 		$('#obtenerFechaIni10').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin10').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin10').html("NULL");
				        	$('#obtenerTodoeldia10').html(event.allDay);
		           	 		$('#obtenercolor10').html(event.backgroundColor);
		           	 		$('#obtenertitulo10').html(event.title);
		           	 		//$('#obtenerDesayuno10').html(event.id_momentoDia);
		           	 		//$('#obtenerAlmuerzo10').html(event.id_momentoDia);
		           	 		//$('#obtenerCena10').html(event.id_momentoDia);

							$('#modalEsfinteres').modal();

							break;


						case "rgb(255, 133, 27)": //11 - correcion en la mesa
							
							$('#modalTitle11').html(event.title);
			    			$('#descripcionTexto11').html(event.observ);
		           	 		$('#obtenerId11').html(event.id);
		           	 		$('#obtenerIdTipoEvento11').html(event.id_tipoEvento);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));

		           	 		//radio buttons
		           	 		var opcionRadio = event.id_momentoDia;

		           	 		switch (opcionRadio)
		           	 		{
		           	 			case 1:
		           	 				document.getElementById("desayuno11").checked = true;
		           	 				break;
		           	 			case 2:
		           	 				document.getElementById("almuerzo11").checked = true;
		           	 				break;
		           	 			case 3:
		           	 				document.getElementById("cena11").checked = true;
		           	 				break;
		           	 		}

		           	 		$('#obtenerFechaIni11').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin11').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin11').html("NULL");
				        	$('#obtenerTodoeldia11').html(event.allDay);
		           	 		$('#obtenercolor11').html(event.backgroundColor);
		           	 		$('#obtenertitulo11').html(event.title);
		           	 		$('#obtenerDesayuno11').html(event.id_momentoDia);
		           	 		$('#obtenerAlmuerzo11').html(event.id_momentoDia);
		           	 		$('#obtenerCena11').html(event.id_momentoDia);

							$('#modalMesa').modal();

							break;

						case "rgb(210, 105, 30)": //12 - ocupacional
						
							$('#modalTitle12').html(event.title);
			    			$('#descripcionTexto12').html(event.observ);
		           	 		$('#obtenerId12').html(event.id);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));

		           	 		$('#obtenerFechaIni12').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin12').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin12').html("NULL");
				        	$('#obtenerTodoeldia12').html(event.allDay);
		           	 		$('#obtenercolor12').html(event.backgroundColor);
		           	 		$('#obtenertitulo12').html(event.title);

							$('#modalOcupacional').modal();

							break;

						case "rgb(0, 139, 139)": //13 - vivienda

							$('#modalTitle13').html(event.title);
			    			$('#descripcionTexto13').html(event.observ);
		           	 		$('#obtenerId13').html(event.id);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));

		           	 		$('#obtenerFechaIni13').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin13').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin13').html("NULL");
				        	$('#obtenerTodoeldia13').html(event.allDay);
		           	 		$('#obtenercolor13').html(event.backgroundColor);
		           	 		$('#obtenertitulo13').html(event.title);

							$('#modalVivienda').modal();

							break;

						case "rgb(255, 20, 147)": //14 - relaciones sociales

							$('#modalTitle14').html(event.title);
			    			$('#descripcionTexto14').html(event.observ);
		           	 		$('#obtenerId14').html(event.id);

		           	 		//mostramos la fecha de cuando se ha creado el evento
		           	 		$('input.placeFecha').prop("placeholder", event.start.format("DD-MM-YYYY"));

		           	 		$('#obtenerFechaIni14').html(event.start.format("YYYY-MM-DD HH:mm"));
		           	 		if(event.end)
				        		$('#obtenerFechaFin14').html(event.end.format("YYYY-MM-DD HH:mm"));
				        	else
				        		$('#obtenerFechaFin14').html("NULL");
				        	$('#obtenerTodoeldia14').html(event.allDay);
		           	 		$('#obtenercolor14').html(event.backgroundColor);
		           	 		$('#obtenertitulo14').html(event.title);

							$('#modalSocial').modal();

							break;
			    	}
			    },

			    //evento cuando haces click derecho sobre un evento
			    eventRender: function (event, element) {
			        element.bind('mousedown', function (e) {
			            if (e.which == 3) {
			            	//mostramos el modal con el titulo del evento y guardamos la id del evento         
			           		$('#eliminarEvento').modal();
		           			$('#tituloEvento').html(event.title);
			           	 	$('#obtenerIdEventoEliminar').html(event.id);
			            }
			        });
			    },
			   
			    eventMouseover: function( event, jsEvent, view ) { 
			    	var start = (event.start.format("HH:mm"));
			        var back = event.backgroundColor;
			        var tooltip = "";
			        
			        if(event.end){
			            var end = event.end.format("HH:mm");
			        }else{
			        	var end="No definido";
			        }

			        if(event.allDay){
			        	var allDay = "Si";
			        }else{
			        	var allDay="No";
			        }
	     
			        if(end == "No definido")
			        	tooltip = '<div class="tooltipevent" style="width:200px;height:100px;color:black;background:'+back+';position:absolute;z-index:10001;">'+'<center>'+ event.title +'</center>'+'Todo el dia: '+allDay+'<br>'+ 'Inicio: '+start+'<br>'+'</div>';

			        else if(allDay == "No")
			        	tooltip = '<div class="tooltipevent" style="width:200px;height:100px;color:black;background:'+back+';position:absolute;z-index:10001;">'+'<center>'+ event.title +'</center>'+'<br>'+ 'Inicio: '+start+'<br>'+ 'Fin: '+ end +'</div>';

			        else
			        	tooltip = '<div class="tooltipevent" style="width:200px;height:100px;color:black;background:'+back+';position:absolute;z-index:10001;">'+'<center>'+ event.title +'</center>'+'Todo el dia: '+allDay+'<br>'+ 'Inicio: '+start+'<br>'+ 'Fin: '+ end +'</div>';

			        
			        $("body").append(tooltip);
			        $(this).mouseover(function(e) {
			        	$(this).css('z-index', 10000);
			         	$('.tooltipevent').fadeIn('500');
			          	$('.tooltipevent').fadeTo('10', 1.9);
			        }).mousemove(function(e) {
			          	$('.tooltipevent').css('top', e.pageY + 10);
			          	$('.tooltipevent').css('left', e.pageX + 20);
			        });            
			    },
	      
			    eventMouseout: function(calEvent, jsEvent) {
			    	$(this).css('z-index', 8);
			        $('.tooltipevent').remove();
			    },
			    
			    dayClick: function(date, jsEvent, view) {
			    	if (view.name === "month") {
			            $('#calendar').fullCalendar('gotoDate', date);
			            $('#calendar').fullCalendar('changeView', 'agendaDay');
			        }
			    }
			});

			
			$('#confirmarEliminarEvento').on('click', function(e){
				var id = document.getElementById("obtenerIdEventoEliminar").innerHTML;

				$.ajax({
	        		url: '/eliminaEvento',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id , 		     

					success: function(json) {
			            console.log("Updated Successfully eventdrop");

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });

			        },
		            error: function(json){
		            	console.log("Error al eliminar el evento");
		            }
	        	})

	        	$('#eliminarEvento').modal("hide");  

			});

			//GUARDAR OBSERVACIONES
			$('#submitButton').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId").innerHTML;
				var texto = $('#descripcionTexto').val(); //observacion
				var tipoEvento = botonPulsado;
				var persona = "{{ $persona->id }}";

				var fechaIni = document.getElementById("obtenerFechaIni").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia").innerHTML;
				var color = document.getElementById("obtenercolor").innerHTML;
				var titulo = document.getElementById("obtenertitulo").innerHTML;
	            
	            //var postId = $(this).data('event.id');
	            //console.log("ID " +id);

	        	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo , 	

			  
					success: function(json) {
			            console.log("Updated Successfully");

			            $('#calendarNoche').fullCalendar('refetchEvents');
	        			$('#calendarNoche').fullCalendar('renderEvents')      	
						$('#calendarNoche').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });

			        },
		            error: function(json){
		            	console.log("Error al actualizar eventdrop");
		            	console.log("TEXTO: " +texto);
		            	console.log("TIPO EVENTO: " +tipoEvento);
		            }
	        	})

	        	$('#fullCalModal').modal("hide");        	

	        	//console.log("ID " +id);
	        });

			//GUARDAR APETITO
			$('#guardarApetito').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId3").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni3").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin3").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia3").innerHTML;
				var color = document.getElementById("obtenercolor3").innerHTML;
				var titulo = document.getElementById("obtenertitulo3").innerHTML;
	            var texto = $('#descripcionTexto3').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	//var tipoEvento = botonPulsado;
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento3").innerHTML;

		       	//saber que radio button esta pulsado
		       	var opcionRadio = "";

		       	if(document.getElementById("desayuno3").checked)
		       		opcionRadio = 1;

		       	if(document.getElementById("almuerzo3").checked)
		       		opcionRadio = 2;

		       	if(document.getElementById("cena3").checked)
		       		opcionRadio = 3;

		       	console.log("OPCION RADIO ANTES DE GUARDAR: " +opcionRadio);
	
	        	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        //data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&id_momentoDia='+opcionRadio , 


			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&id_momentoDia='+opcionRadio , 	
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            //console.log("ID: " +id);
			            //console.log("ID TIPO EVENTO: " +tipoEvento);
			            console.log("OPCION RADIO: " +opcionRadio);

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
						

			        },
		            error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            	//console.log("ID TIPO EVENTO: " +tipoEvento);
		            	//console.log("ID: " +id);
		            	console.log("OPCION RADIO: " +opcionRadio);
		            }
	        	})

	        	$('#modalApetito').modal("hide");        	
			});

			//GUARDAR CONSULTAS Y HOSPITALIZACIONES
			$('#guardarHospital').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId5").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni5").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin5").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia5").innerHTML;
				var color = document.getElementById("obtenercolor5").innerHTML;
				var titulo = document.getElementById("obtenertitulo5").innerHTML;
	            var texto = $('#descripcionTexto5').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento5").innerHTML;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            //console.log("ID: " +id);
			            //console.log("ID TIPO EVENTO: " +tipoEvento);

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            	//console.log("ID TIPO EVENTO: " +tipoEvento);
		            	//console.log("ID: " +id);
		            }
	        	})

	        	$('#modalHospital').modal("hide"); 

			});

			//GUARDAR ESTADO MENTAL
			$('#guardarMental').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId6").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni6").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin6").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia6").innerHTML;
				var color = document.getElementById("obtenercolor6").innerHTML;
				var titulo = document.getElementById("obtenertitulo6").innerHTML;
	            var texto = $('#descripcionTexto6').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento6").innerHTML;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            //console.log("ID: " +id);
			            //console.log("ID TIPO EVENTO: " +tipoEvento);

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            	//console.log("ID TIPO EVENTO: " +tipoEvento);
		            	//console.log("ID: " +id);
		            }
	        	})

	        	$('#modalMental').modal("hide"); 
			});

			//GUARDAR PROBLEMAS DE CONDUCTAS
			$('#guardarConducta').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId8").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni8").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin8").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia8").innerHTML;
				var color = document.getElementById("obtenercolor8").innerHTML;
				var titulo = document.getElementById("obtenertitulo8").innerHTML;
	            var texto = $('#descripcionTexto8').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento8").innerHTML;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            //console.log("ID: " +id);
			            //console.log("ID TIPO EVENTO: " +tipoEvento);

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            	//console.log("ID TIPO EVENTO: " +tipoEvento);
		            	//console.log("ID: " +id);
		            }
	        	})

		       	$('#modalConducta').modal("hide"); 
			});

			//GUARDAR HIGIENE
			$('#guardarHigiene').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId9").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni9").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin9").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia9").innerHTML;
				var color = document.getElementById("obtenercolor9").innerHTML;
				var titulo = document.getElementById("obtenertitulo9").innerHTML;
	            var texto = $('#descripcionTexto9').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento9").innerHTML;
		       	var opcion = 0;

		       	//comprobar checklist
		       	var turnos = new Array();

		       	//saber que radio button esta pulsado
		       	var opcionRadio = "";

		       	if(document.getElementById("mañana9").checked)
		       	{
		       		turnos.push("Mañana");
		       		opcionRadio = 1;
		       	}

		       	if(document.getElementById("tarde9").checked)
		       	{
		       		turnos.push("Tarde");
		       		opcionRadio = 2;
		       	}

		       	if(document.getElementById("noche9").checked)
		       	{
		       		turnos.push("Noche");
		       		opcionRadio = 3;
		       	}

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&id_momentoDia='+opcionRadio+'&turnos='+turnos+'&opcion='+opcion, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            console.log("OPCION RADIO: " +opcionRadio);

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            	console.log("OPCION RADIO: " +opcionRadio);
		            }
	        	})

	        	$('#modalHigiene').modal("hide");        	      
			});

			//GUARDAR ESFINTERES
			$('#guardarEsfinteres').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId10").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni10").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin10").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia10").innerHTML;
				var color = document.getElementById("obtenercolor10").innerHTML;
				var titulo = document.getElementById("obtenertitulo10").innerHTML;
	            var texto = $('#descripcionTexto10').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento10").innerHTML;
		       	var opcion = 0;

		       	//comprobar checklist
		       	var turnos = new Array();


		       	//saber que radio button esta pulsado
		       	var opcionRadio = "";

		       	if(document.getElementById("mañana10").checked)
		       	{
		       		turnos.push("Mañana");
		       		opcionRadio = 1;
		       	}

		       	if(document.getElementById("tarde10").checked)
		       	{
		       		turnos.push("Tarde");
		       		opcionRadio = 2;
		       	}

		       	if(document.getElementById("noche10").checked)
		       	{
		       		turnos.push("Noche");
		       		opcionRadio = 3;
		       	}

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&id_momentoDia='+opcionRadio+'&turnos='+turnos+'&opcion='+opcion, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            console.log("OPCION RADIO: " +turnos);
			            console.log("TURNOS: " +opcionRadio);

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TURNOS: " +turnos);
		            	console.log("TEXTO: " +texto);
		            	console.log("OPCION RADIO: " +opcionRadio);
		            }
	        	})

		       	$('#modalEsfinteres').modal("hide");        	      
			});

			//GUARDAR CORRECION EN LA MESA
			$('#guardarMesa').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId11").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni11").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin11").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia11").innerHTML;
				var color = document.getElementById("obtenercolor11").innerHTML;
				var titulo = document.getElementById("obtenertitulo11").innerHTML;
	            var texto = $('#descripcionTexto11').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento11").innerHTML;

		       	//saber que radio button esta pulsado
		       	var opcionRadio = "";

		       	if(document.getElementById("desayuno11").checked)
		       		opcionRadio = 1;

		       	if(document.getElementById("almuerzo11").checked)
		       		opcionRadio = 2;

		       	if(document.getElementById("cena11").checked)
		       		opcionRadio = 3;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&id_momentoDia='+opcionRadio , 	
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            console.log("OPCION RADIO: " +opcionRadio);

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            	console.log("OPCION RADIO: " +opcionRadio);
		            }
	        	})

		       	$('#modalMesa').modal("hide"); 

			});

			//GUARDAR OCUPACIONAL
			$('#guardarOcupacional').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId12").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni12").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin12").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia12").innerHTML;
				var color = document.getElementById("obtenercolor12").innerHTML;
				var titulo = document.getElementById("obtenertitulo12").innerHTML;
	            var texto = $('#descripcionTexto12').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento12").innerHTML;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            }
	        	})

	        	$('#modalOcupacional').modal("hide"); 
			});

			//GUARDAR VIVIENDA
			$('#guardarVivienda').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId13").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni13").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin13").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia13").innerHTML;
				var color = document.getElementById("obtenercolor13").innerHTML;
				var titulo = document.getElementById("obtenertitulo13").innerHTML;
	            var texto = $('#descripcionTexto13').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento13").innerHTML;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            }
	        	})

	        	$('#modalVivienda').modal("hide"); 
			});

			//GUARDAR SOCIAL
			$('#guardarsocial').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId14").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni14").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin14").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia14").innerHTML;
				var color = document.getElementById("obtenercolor14").innerHTML;
				var titulo = document.getElementById("obtenertitulo14").innerHTML;
	            var texto = $('#descripcionTexto14').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento14").innerHTML;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo, 	
			  
					success: function(json) {
			            console.log("Updated Successfully");

			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            }
	        	})

	        	$('#modalSocial').modal("hide"); 
			});
		
			//GUARDAR CRISIS EPILEPTICAS
			$('#submitButton1').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId1").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni1").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin1").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia1").innerHTML;
				var color = document.getElementById("obtenercolor1").innerHTML;
				var titulo = document.getElementById("obtenertitulo1").innerHTML;
				//var durCrisis = document.getElementById("obtenerDurCrisis1").innerHTML;

	            var texto = $('#descripcionTexto1').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	//var tipoEvento = botonPulsado;
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento1").innerHTML;
		       	var opcion = 0; //variable para controlar el eventdrop de los eventos que tienen mas de 1 tabla

		       	//valores nuevos especificos
		       	var hora = document.getElementById("obtenerHora1").innerHTML;
		       	var durCrisis = $('#crisis1').val();
		       	var ultiToma = $('#ultimaToma1').val(); //document.getElementById("obtenerUltiToma1").innerHTML;
		       	

		       	//saber que radio button esta pulsado
		       	var radioPerdida = "";
		       	var radioRelajacion = "";
		       	var radioConvulsion = "";
		       	var radioLesion = "";

		     	var ultimaTomaFormateada = "0000-00-00 "+ultiToma;

		       	//perdida conciencia
		       	if(document.getElementById("perdida1").checked)
		       		radioPerdida = 1;
		       	if(document.getElementById("perdida2").checked)
		       		radioPerdida = 2;

		       	//relajacion esfinteres
		       	if(document.getElementById("relajacion1").checked)
		       		radioRelajacion = 1;
		       	if(document.getElementById("relajacion2").checked)
		       		radioRelajacion = 2;

		       	//convulsiones
		       	if(document.getElementById("convulsion1").checked)
		       		radioConvulsion = 1;
		       	if(document.getElementById("convulsion2").checked)
		       		radioConvulsion = 2;

		       	//lesiones fisicas
		       	if(document.getElementById("lesion1").checked)
		       		radioLesion = 1;
		       	if(document.getElementById("lesion2").checked)
		       		radioLesion = 2;


	        	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        //data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&duracion='+durCrisis,

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&perdidaConciencia='+radioPerdida+'&relajaEsfinteres='+radioRelajacion+'&convulsiones='+radioConvulsion+'&lesionesFisicas='+radioLesion+'&hora='+hora+'&duracion='+durCrisis+'&ultimaToma='+ultimaTomaFormateada+'&opcion='+opcion,
  
					success: function(json) {
			            console.log("Updated Successfully");

	        			console.log("ID EVENTO: " +tipoEvento);
		            	//console.log("FECHA: " +fechaIni);
		            	//console.log("HORA: " +hora);
		            	//console.log("DURACION: " +durCrisis);
		            	console.log("PERDIDA CONCIENCIA: " +radioPerdida);
		            	console.log("RELAJA ESFINTERES: " +radioRelajacion);
		            	console.log("CONVULSIONES: " +radioConvulsion);
		            	console.log("LESIONES FISICAS: " +radioLesion);
		            	console.log("ULTIMA TOMA: " +ultiToma);
		            	//console.log("OBSERVACION: " +texto);

		            	$('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents');
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
		            error: function(json){
		            	console.log("HORA FORMATEADA: " +ultimaTomaFormateada);
		            	console.log("ID EVENTO: " +tipoEvento);
		            	console.log("FECHA: " +fechaIni);
		            	console.log("HORA: " +hora);
		            	console.log("DURACION: " +durCrisis);
		            	console.log("PERDIDA CONCIENCIA: " +radioPerdida);
		            	console.log("RELAJA ESFINTERES: " +radioRelajacion);
		            	console.log("CONVULSIONES: " +radioConvulsion);
		            	console.log("LESIONES FISICAS: " +radioLesion);
		            	console.log("ULTIMA TOMA: " +ultiToma);
		            	console.log("OBSERVACION: " +texto);
		            }
	        	})

	        	$('#modal1').modal("hide");        	
			});
			
			//GUARDAR SUEÑO
			$('#guardarSueño').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId4").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni4").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin4").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia4").innerHTML;
				var color = document.getElementById("obtenercolor4").innerHTML;
				var titulo = document.getElementById("obtenertitulo4").innerHTML;
	            var texto = $('#descripcionTexto4').val(); //observacion
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento4").innerHTML;
		       	var horasDormidas = $('#numHoras').val();
		       	var opcion = 0;

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&horasDormidas='+horasDormidas+'&opcion='+opcion,
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            console.log("HORAS DORMIDAS: " +horasDormidas);
			            console.log("TIPO EVENTO: " +tipoEvento);
			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
		            }
	        	})

	        	$('#modalSueño').modal("hide"); 
			});
	
			//GUARDAR INCIDENCIAS COMPORTAMENTALES
			$('#guardaIncidencia').on('click', function(e){
				e.preventDefault();

				//valores a modificar
				var id = document.getElementById("obtenerId7").innerHTML;
				var fechaIni = document.getElementById("obtenerFechaIni7").innerHTML;
				var fechaFin = document.getElementById("obtenerFechaFin7").innerHTML;
				var todoeldia = document.getElementById("obtenerTodoeldia7").innerHTML;
				var color = document.getElementById("obtenercolor7").innerHTML;
				var titulo = document.getElementById("obtenertitulo7").innerHTML;
	            var persona = "{{ $persona->id }}";
		       	var tipoEvento = document.getElementById("obtenerIdTipoEvento7").innerHTML;
		       	var opcion = 0;
		       	var antes = $('#queAntes').val(); 
		       	var queHizo = $('#queHizo').val(); 
		       	var despues = $('#queDespues').val(); 
		       	var texto = "null";

		       	$.ajax({
	        		url: '/actualizaEventos',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&opcion='+opcion+'&antes='+antes+'&queHizo='+queHizo+'&despues='+despues+'&observ='+texto,
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            console.log("QUE ANTES: " +antes);
			            console.log("QUE HIZO: " +queHizo);
			            console.log("QUE DESPUES: " +despues);
			            console.log("TIPO EVENTO: " +tipoEvento);
			            console.log("TITULO: " +titulo);
			            $('#calendar').fullCalendar('refetchEvents');
	        			$('#calendar').fullCalendar('renderEvents')      	
						$('#calendar').fullCalendar({ events: { url: '/cargaEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error");
		            	console.log("QUE ANTES: " +antes);
			            console.log("QUE HIZO: " +queHizo);
			            console.log("QUE DESPUES: " +despues);
			            console.log("TIPO EVENTO: " +tipoEvento);
		            }
	        	})

	        	$('#modalComportamentales').modal("hide"); 
			});


			//activar o desactivar botones (Eventos)
	        function activarBoton(boton)
	        {
	        	var botones = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
	        	var botonP = boton;

	        	for(i=0; i < botones.length; i++)
	        	{
	        		if(botonP == botones[i])
	        			$('.'+botones[i]).show();
	        		else
	        			$('.'+botones[i]).hide();
	        	}      	
	        };

	        //funcion de los botones
	        $('.categorias').on('click', function(e){
	        	e.preventDefault();
	        	botonPulsado = this.id;
	        	activarBoton(botonPulsado);
	        });
	
        	if(botonActivado == true)
        	{
        		botonActivado = false;
	    		$('#menuCalendarioNoche').toggle();
	    		$('#menuCalendarioEventos').toggle();
	    	}

	    });

		/***********************************************************************************************
		/***********************************************************************************************
		/***********************************************************************************************
		/******************************        CALENDARIO NOCHE        *********************************
		/***********************************************************************************************
		/***********************************************************************************************
        /***********************************************************************************************/
        $('#calendarioNoche').on('click', function(e){
        	e.preventDefault();
        	calendarioActivo = "noche";
        	//$('#menuCalendarioEventos').hide();
        	
        	$('#calendar').fullCalendar('destroy');

        	//$('#calendarNoche').fullCalendar('refetchEvents');
			//$('#calendarNoche').fullCalendar('renderEvents');
			//$('#calendarNoche').fullCalendar({ events: { url: '/cargaEventosNoche{{ $persona->id }}' } });

        	//Ocultamos todos los eventos del calendario de eventos
        	for(i=0; i < 16; i++)
        		$('.'+i).hide();

        	//$('.1').hide();
        	//$('.2').hide();
        	//$('.3').hide();
        	//$('.4').hide();
        	//$('.5').hide();
        	//$('.6').hide();
        	//$('.7').hide();
        	//$('.8').hide();
        	//$('.9').hide();
        	//$('.10').hide();
        	//$('.11').hide();
        	//$('.12').hide();
        	//$('.13').hide();
        	//$('.14').hide();
        	//$('.15').hide();

        	activarCalNoche = true;

        	/* initialize the external events
			-----------------------------------------------------------------*/
			var botonPulsado = null; //variable que almacena la id de la categoria que se ha pulsado.

			function ini_events(ele) {
					ele.each(function () {
					// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
					// it doesn't need to have a start or end
					var eventObject = {
			  			title: $.trim($(this).text()), // use the element's text as the event title
			  			//stick: true 
					};
					// store the Event Object in the DOM element so we can get to it later
					$(this).data('event', eventObject);
					// make the event draggable using jQuery UI
					$(this).draggable({
			  			zIndex: 1070,
			  			revert: true, // will cause the event to go back to its
			  			revertDuration: 0  //  original position after the drag
					});
					});
			}
			ini_events($('#external-events div.external-event'));

		    /* initialize the calendar
		     -----------------------------------------------------------------*/
		    //Date for the calendar events (dummy data)
		    var date = new Date();
		    var d = date.getDate(),
		        m = date.getMonth(),
		        y = date.getFullYear();

		    $('#calendarNoche').fullCalendar({
		    	header: {
		        	left: 'prev,next today',
		        	center: 'title',
		        	right: 'prevYear,month,agendaWeek,agendaDay,nextYear'
		      	},
		      	buttonText: {
		        	today: 'hoy',
		        	month: 'mes',
		        	week: 'semana',
		        	day: 'dia'
		      	},

		      	defaultView: 'agendaWeek',
		      	//allDayText: 'Observaciones',

		    	events: { url:"/cargaNocheEventos{{ $persona->id }}"},

		      	//allDaySlot: false, // ocultamos la opcion de todo el dia
		      	editable: true,
		      	droppable: true, // this allows things to be dropped onto the calendar !!!
		      	
		      	drop: function (date, allDay, delta) { // this function is called when something is dropped
		        	// retrieve the dropped element's stored Event Object
		        	var originalEventObject = $(this).data('event');
		        	// we need to copy it, so that multiple events don't have a reference to the same object
		        	var copiedEventObject = $.extend({}, originalEventObject);
		        	allDay=true;
		        	// assign it the date that was reported
		        	copiedEventObject.start = date;
		        	copiedEventObject.allDay = allDay;
		        	copiedEventObject.backgroundColor = $(this).css("background-color");

		        	//Guardamos el evento creado en base de datos
		        	var persona = "{{ $persona->id }}";
		       		var tipoEvento = "115"; //separamos los eventos de los de noche
		       		//var momentoDia = "1"; //al momento de crearla lo ponemos a 1, luego se modifica.
		   	        var time = copiedEventObject.start.format("HH:mm");
		   	       	var start = copiedEventObject.start.format("YYYY-MM-DD HH:mm");
		        	var title = copiedEventObject.title;		       		
		       		var categoriaSeleccionada = botonPulsado;
		       		var back = copiedEventObject.backgroundColor;
		       		//allDay = false;
				
					$.ajax({
		            	url: '/guardaNocheEventos',
		            	type: "POST",         
		            	beforeSend: function (xhr) {
		            		var token = $('meta[name="csrf_token"]').attr('content');

		            		if (token) {
		                		return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		            		}
		        		},

		        		//data: 'id_persona='+persona+'&id_tipoEvento='+tipoEvento+'&id_momentoDia='+momentoDia+'&hora='+time+'&start='+start+'&allday='+allDay+'&title='+title+'&background='+back,

		        		data: 'id_persona='+persona+'&id_tipoEvento='+tipoEvento+'&hora='+time+'&start='+start+'&allday='+allDay+'&title='+title+'&background='+back,
		            
		            	success: function(events) {
		                	console.log('Evento creado');      

		                	console.log("ID_PERSONA: "+persona);
		                	console.log("ID_TIPO_EVENTO: "+tipoEvento);
		                	console.log("HORA: "+time);
		                	console.log("START "+start);
		                	console.log("ALL DAY: "+allDay);

		                	//$('#calendarNoche').fullCalendar('refetchEvents');

		                	$('#calendarNoche').fullCalendar('refetchEvents');
	        				//$('#calendarNoche').fullCalendar('renderEvents');
							//$('#calendarNoche').fullCalendar({ events: { url: '/cargaEventosNoche{{ $persona->id }}' } });
		            	},
		              	error: function(json){
		                	console.log("Error al crear evento");
		                	console.log("ID_PERSONA: "+persona);
		                	console.log("ID_TIPO_EVENTO: "+tipoEvento);
		                	//console.log("ID_MOMENTO_DIA: "+momentoDia);
		                	console.log("HORA: "+time);
		                	console.log("START "+start);	
		                	console.log("ALL DAY: "+allDay);
		                	console.log("TITULO: "+title);      	
		                	console.log("BACKGROUND: "+back);
		            	}        
		        	});
	      		},

		    	eventResize: function(event) {
		        	var start = event.start.format("YYYY-MM-DD HH:mm");
		          	var back = event.backgroundColor;
		          	var allDay = event.allDay;
		          	var texto = event.observ;
		          	var opcion = 1;
		          
		         	if(event.end){
		            	var end = event.end.format("YYYY-MM-DD HH:mm");
		          	}else{
		          		var end = "NULL";
		          	}

		            $.ajax({
		            	url: '/actualizaNocheEventos',         
		              	type: "POST",
		              	beforeSend: function (xhr) {
		            		var token = $('meta[name="csrf_token"]').attr('content');

		            		if (token) {
		                		return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		            		}
		        		},
		        		data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id+'&background='+back+'&allday='+allDay+'&observ='+texto+'&opcion='+opcion,
		             
		                success: function(json) {
		                	console.log("Updated Successfully");            	
		                },
		                error: function(json){
		                	console.log("Error al actualizar evento");
		                }
		            });
	    		},

			    eventDrop: function(event, delta) {
			    	var id = event.id;
			    	var persona = "{{ $persona->id }}";
			    	var id_tipoEvento = event.id_tipoEvento;
			    	//var id_momentoDia = event.id_momentoDia;
			    	var observ = event.observ;
			    	var title = event.title;
			    	var hora = event.hora;
			    	var start = event.start.format("YYYY-MM-DD HH:mm");
			    	var allDay = event.allDay;
			    	var opcion = 1; //para filtrar los eventos que tienen mas de una tabla
			        
			        if(event.end){
			        	var end = event.end.format("YYYY-MM-DD HH:mm");
			        }else{
			        	var end="NULL";
			        }

			        var back = event.backgroundColor;
			        var texto = event.observ;

			        if(allDay == true)
			        	allDay = 1;
			        else
			        	allDay = 0;
			        
			        $.ajax({  
			        	url: '/actualizaNocheEventos',
			            type: "POST",
			            beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},
			      
			            //data: 'id_persona='+persona+'&id_tipoEvento='+tipoEvento+'&id_momentoDia='+momentoDia+'&hora='+time+'&start='+start+'&allday='+allDay+'&title='+event.title+'&background='+back+'&observ='+texto+'&end='+end,  

			            data: 'id='+id+'&id_persona='+persona+'&id_tipoEvento='+id_tipoEvento+'&hora='+hora+'&start='+start+'&end='+end+'&allday='+allDay+'&title='+title+'&background='+back+'&observ='+texto+'&opcion='+opcion, 

			            success: function(json) {
			            	console.log("ID: " +id);
			            	console.log("ALLDAY: " +allDay);
			            	console.log("Updated Successfully eventdrop");
			            },
			            error: function(json){
			            	console.log("Error al actualizar eventdrop, EL TEXTO DEL EVENTO: " +texto);
			            	console.log("ID PERSONA: " +persona);
			            	console.log("ID TIPO EVENTO: " +id_tipoEvento);
			            	console.log("HOrA: " +hora);
			            	console.log("START: " +start);
			            	console.log("END: " +end);
			            	console.log("ALLDAY: " +allDay);
			            	console.log("TITLE: " +title);
			            	console.log("TEXTO: " +texto);
			            	console.log("BACKGROUND: " +back);
			            }
			        });
		    	},

		    	//muestra cada formulario del evento al hacer click sobre el
			    eventClick: function (event) {           	
		        	$('#modalTitleInci').html(event.title);
		        	$('#numHorasInci').val(event.horasDormidas);
	    			$('#descripcionTextoInci').html(event.observ);
           	 		$('#obtenerIdInci').html(event.id);
           	 		$('#obtenerIdTipoEventoInci').html(event.id_tipoEvento);
           	 		$('#obtenerFechaIniInci').html(event.start.format("YYYY-MM-DD HH:mm"));
           	 		if(event.end)
		        		$('#obtenerFechaFinInci').html(event.end.format("YYYY-MM-DD HH:mm"));
		        	else
		        		$('#obtenerFechaFinInci').html("NULL");
		        	$('#obtenerTodoeldiaInci').html(event.allDay);
           	 		$('#obtenercolorInci').html(event.backgroundColor);
           	 		$('#obtenertituloInci').html(event.title);
           	 		$('#obtenerHorasDormidasInci').html(event.horasDormidas);
            		$('#modalIncidenciaSueño').modal();
			    },

			    //evento cuando haces click derecho sobre un evento
			    eventRender: function (event, element) {
			        element.bind('mousedown', function (e) {
			            if (e.which == 3) {
			            	//mostramos el modal con el titulo del evento y guardamos la id del evento         
			           		$('#eliminarEventoIncidencia').modal();
		           			$('#tituloEventoIncidencia').html(event.title);
			           	 	$('#obtenerIdEventoEliminarIncidencia').html(event.id);
			            }
			        });
			    },
		   
			    eventMouseover: function( event, jsEvent, view ) { 
			    	var start = (event.start.format("HH:mm"));
			        var back = event.backgroundColor;
			        var tooltip = "";
			        var texto = event.observ;
			        
			        if(event.end){
			            var end = event.end.format("HH:mm");
			        }else{
			        	var end = "No definido";
			        }

			        if(event.allDay){
			        	var allDay = "Si";
			        }else{
			        	var allDay = "No";
			        }

			        if(texto == null || texto == "0")
			        	texto = "NO";
			        else
			        	texto = "SI";
	     
			        if(end == "No definido")
			        	tooltip = '<div class="tooltipevent" style="width:200px;height:100px;color:white;background:'+back+';position:absolute;z-index:10001;">'+'<center>'+ event.title +'</center>'+'Todo el dia: '+allDay+'<br>'+ 'Inicio: '+start+'<br>'+
			        '<br>'+ 'Observaciones: '+texto+'<br>'+
			        '</div>';

			        else if(allDay == "No")
			        	tooltip = '<div class="tooltipevent" style="width:200px;height:100px;color:white;background:'+back+';position:absolute;z-index:10001;">'+'<center>'+ event.title +'</center>'+'<br>'+ 'Inicio: '+start+'<br>'+ 'Fin: '+ end +
			        '<br>'+ 'Observaciones: '+texto+'<br>'+
			        '</div>';

			        else
			        	tooltip = '<div class="tooltipevent" style="width:200px;height:100px;color:white;background:'+back+';position:absolute;z-index:10001;">'+'<center>'+ event.title +'</center>'+'Todo el dia: '+allDay+'<br>'+ 'Inicio: '+start+'<br>'+ 'Fin: '+ end +
			        '<br>'+ 'Observaciones: '+texto+'<br>'+
			        '</div>';
			        
			        $("body").append(tooltip);
			        $(this).mouseover(function(e) {
			        	$(this).css('z-index', 10000);
			         	$('.tooltipevent').fadeIn('500');
			          	$('.tooltipevent').fadeTo('10', 1.9);
			        }).mousemove(function(e) {
			          	$('.tooltipevent').css('top', e.pageY + 10);
			          	$('.tooltipevent').css('left', e.pageX + 20);
			        });            
			    },
	      
			    eventMouseout: function(calEvent, jsEvent) {
			    	$(this).css('z-index', 8);
			        $('.tooltipevent').remove();
			    },
			    
			    dayClick: function(date, jsEvent, view) {
			    	if (view.name === "month") {
			            $('#calendarNoche').fullCalendar('gotoDate', date);
			            $('#calendarNoche').fullCalendar('changeView', 'agendaDay');
			        }
			    }
			});
			
			$('#confirmarEliminarEventoIncidencia').on('click', function(e){
				var id = document.getElementById("obtenerIdEventoEliminarIncidencia").innerHTML;

				$.ajax({
	        		url: '/eliminaNocheEvento',
					type: "POST",				  
					beforeSend: function (xhr) {
			            	var token = $('meta[name="csrf_token"]').attr('content');

			            	if (token) {
			                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
			            	}
			        	},

			        data: 'id='+ id , 		     

					success: function(json) {
						console.log("ID: " +id);
			            console.log("Updated Successfully eventdrop");
			            
			            $('#calendarNoche').fullCalendar('refetchEvents');
	        			$('#calendarNoche').fullCalendar('renderEvents')      	
						$('#calendarNoche').fullCalendar({ events: { url: '/cargaNocheEventos{{ $persona->id }}' } });

			        },
		            error: function(json){
		            	console.log("ID: " +id);
		            	console.log("Error al eliminar el evento");
		            }
	        	})
	        	$('#eliminarEventoIncidencia').modal("hide");  

			});
			
			//GUARDAR INCIDENCIA
			$('#guardarIncidenciaSueño').on('click', function(e){
			e.preventDefault();

			//valores a modificar
			var id = document.getElementById("obtenerIdInci").innerHTML;
			var fechaIni = document.getElementById("obtenerFechaIniInci").innerHTML;
			var fechaFin = document.getElementById("obtenerFechaFinInci").innerHTML;
			var todoeldia = document.getElementById("obtenerTodoeldiaInci").innerHTML;
			var color = document.getElementById("obtenercolorInci").innerHTML;
			var titulo = document.getElementById("obtenertituloInci").innerHTML;
            var texto = $('#descripcionTextoInci').val(); //observacion
            var persona = "{{ $persona->id }}";
	       	var tipoEvento = document.getElementById("obtenerIdTipoEventoInci").innerHTML;
	       	var horasDormidas = $('#numHorasInci').val();
	       	var opcion = 0;

	       	$.ajax({
        		url: '/actualizaNocheEventos',
				type: "POST",				  
				beforeSend: function (xhr) {
		            	var token = $('meta[name="csrf_token"]').attr('content');

		            	if (token) {
		                	return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		            	}
		        	},

			        data: 'id='+ id+'&observ='+texto+'&id_tipoEvento='+tipoEvento+'&id_persona='+persona+'&start='+fechaIni+'&end='+fechaFin+'&allday='+todoeldia+'&background='+color+'&title='+titulo+'&horasDormidas='+horasDormidas+'&opcion='+opcion,
			  
					success: function(json) {
			            console.log("Updated Successfully");
			            console.log("HORAS DORMIDAS: " +horasDormidas);
			            console.log("TIPO EVENTO: " +tipoEvento);
			            console.log("TEXTO: " +texto);
			            $('#calendarNoche').fullCalendar('refetchEvents');
	        			$('#calendarNoche').fullCalendar('renderEvents')      	
						$('#calendarNoche').fullCalendar({ events: { url: '/cargaNocheEventos{{ $persona->id }}' } });
			        },
			        error: function(json){
		            	console.log("Error al actualizar el radio button");
		            	console.log("TEXTO: " +texto);
			        }
	        	})

	        	$('#modalIncidenciaSueño').modal("hide"); 
			});

        	if(botonActivado == false)
        	{
        		botonActivado = true;
	    		$('#menuCalendarioNoche').toggle();
	    		$('#menuCalendarioEventos').toggle();
	    	}
        	
	    });    
	});

</script>

</head>
<body class="admin-body">
	@include('admin.template.partials.nav')

	<!-- DATOS DE LOS USUARIOS -->
	{!! Form::open(['route' => 'admin.personas.index', 'method' => 'GET']) !!}
	<div class="panel panel-default">
    <!-- Content Header (Page header) -->
    <div class="panel-heading"><h2>Eventos de la persona: "{{ $persona->nombre }}"</h2></div>
    <div class="panel-body">

    <!-- MODAL ELIMINAR EVENTO -->
	<div id="eliminarEvento" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog"">
	    	<div class="modal-content">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">Confirmar eliminación</h4>
	            </div>

	            <div class="modal-body">
                	<p>Vas a eliminar el evento: </p>
                	<h4 id="tituloEvento" class="modal-title"></h4>
                	<div id="obtenerIdEventoEliminar" hidden="true"></div>
           	 	</div>

           	 	<div class="modal-body" align="center">
           	 		<h4>¿Estás seguro?</h4>
           	 	</div>

            	<div class="modal-body" align="center"> 
                	<button type="button" class="btn btn-sm btn-primary" id="confirmarEliminarEvento">Eliminar</button>
                	<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
            	</div>
	        </div>
	    </div>
	</div>

	<!-- MODAL ELIMINAR EVENTO NOCHE -->
	<div id="eliminarEventoIncidencia" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog"">
	    	<div class="modal-content">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">Confirmar eliminación</h4>
	            </div>

	            <div class="modal-body">
                	<p>Vas a eliminar el evento: </p>
                	<h4 id="tituloEventoIncidencia" class="modal-title"></h4>
                	<div id="obtenerIdEventoEliminarIncidencia" hidden="true"></div>
           	 	</div>

           	 	<div class="modal-body" align="center">
           	 		<h4>¿Estás seguro?</h4>
           	 	</div>

            	<div class="modal-body" align="center"> 
                	<button type="button" class="btn btn-sm btn-primary" id="confirmarEliminarEventoIncidencia">Eliminar</button>
                	<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
            	</div>
	        </div>
	    </div>
	</div>


    <!-- MODAL FORMULARIO CUADRO CRISIS EPILEPTICAS -->
	<div id="modal1" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle1" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">

	            <div class="form-group">
	         	   <label for="exampleInputName2" class="col-sm-2 control-label">Fecha</label>
	            	<div class="col-sm-10">
    					<input type="text" class="form-control placeFecha" id="fecha1" style="width: 25%;" placeholder="Placeholder" disabled>
    				</div>
  				</div>

  				<div class="form-group">
  					<label for="exampleInputName2" class="col-sm-2 control-label">Hora</label>
  					<div class="col-sm-10">		
    					<input type="text" class="form-control placeHora" id="hora1" style="width: 25%;" placeholder="Placeholder" disabled>
    				</div>
  				</div>

  				<div class="form-group">
					<label for="exampleInputName2" class="col-sm-2 control-label">Duración crisis (s)</label>
				    <div class="col-sm-10">
				    	<input type="text" class="form-control placeCrisis" id="crisis1" style="width: 25%;">
				    </div>
				 </div>

				 <div>.</div>

				<div class="container-fluid">				
					<div class="panel panel-default col col-md-2 " style="width: 15%;">
		 			<div class="panel-heading"><b>Perdida conciencia</b></div>
		 				<div class="panel-body">
							<div class="radio1">
						 		<label>
						    		<input type="radio" id="perdida1" name="perdida" checked> Si
						  		</label>
							</div>
							<div class="radio1">
						  		<label>
						    		<input type="radio" id="perdida2" name="perdida"> No
						  		</label>
							</div>
						</div>
					</div>

					<div class="panel panel-default col col-md-2 col-md-offset-1" style="width: 18%;">
		 			<div class="panel-heading"><b>Relajación de esfínteres</b></div>
		 				<div class="panel-body">
							<div class="radio1a1">
						 		<label>
						    		<input type="radio" id="relajacion1" name="relajacion" checked> Si
						  		</label>
							</div>
							<div class="radio1a1">
						  		<label>
						    		<input type="radio" id="relajacion2" name="relajacion"> No
						  		</label>
							</div>
						</div>
					</div>

					<div class="panel panel-default col col-md-2 col-md-offset-1" style="width: 18%;">
		 			<div class="panel-heading"><b>Convulsiones</b></div>
		 				<div class="panel-body">
							<div class="radio1b1">
						 		<label>
						    		<input type="radio" id="convulsion1" name="convulsion" checked> Si
						  		</label>
							</div>
							<div class="radio1b1">
						  		<label>
						    		<input type="radio" id="convulsion2" name="convulsion"> No
						  		</label>
							</div>
						</div>
					</div>

					<div class="panel panel-default col col-md-2 col-md-offset-1" style="width: 18%;">
		 			<div class="panel-heading"><b>Lesiones físicas</b></div>
		 				<div class="panel-body">
							<div class="radio1c1">
						 		<label>
						    		<input type="radio" id="lesion1" name="lesion" checked> Si
						  		</label>
							</div>
							<div class="radio1c1">
						  		<label>
						    		<input type="radio" id="lesion2" name="lesion"> No
						  		</label>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
  					<label for="ultimaToma" class="col-sm-2 control-label" >Última toma A.C.</label>
  						<div class="col-sm-10">		
    						<div class="form-group clockpicker">
								<input type="text" id="ultimaToma1" class="form-control col-sm-2 control-label placeReloj" value="00:00" style="width: 15%;" placeholder="Placeholder" disabled>
								<span class="input-group-addon" style="width: 55%; height: 34px"><span class="glyphicon glyphicon-time"></span></span>
							</div>
    				</div>
  				</div>

  				<br><br><br>
				<div><h4>Observaciones</h4></div>

				<div id="obtenerId1" hidden="true"></div>
	            <div id="obtenerFechaIni1" hidden="true"></div>
	            <div id="obtenerFechaFin1" hidden="true"></div>
	            <div id="obtenerTodoeldia1" hidden="true"></div>
	            <div id="obtenercolor1" hidden="true"></div>
	            <div id="obtenertitulo1" hidden="true"></div>
	            <div id="obtenerIdTipoEvento1" hidden="true"></div>

	            <div id="obtenerHora1" hidden="true"></div>
	            <div id="obtenerDurCrisis1" hidden="true"></div>
	            <div id="obtenerUltiToma1" hidden="true"></div>
	            <!-- LOS RADIO BUTTONS -->
	           	<div id="obtenerPerdida1" hidden="true"></div>
	           	<div id="obtenerPerdida2" hidden="true"></div>
	           	<div id="obtenerRelajacion1" hidden="true"></div>
	           	<div id="obtenerRelajacion2" hidden="true"></div>
	           	<div id="obtenerConvulsion1" hidden="true"></div>
	           	<div id="obtenerConvulsion2" hidden="true"></div>
	           	<div id="obtenerLesion1" hidden="true"></div>
	           	<div id="obtenerLesion2" hidden="true"></div>
	            <!-- FIN -->

            	<div class="form-group">
                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto1"></textarea>
                </div>
	            </div>
	            <div class="modal-footer">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="submitButton1">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>

	<!-- MODAL FORMULARIO ESTADO FISICO -->
	<div id="fullCalModal" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog"">
	    	<div class="modal-content">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle" class="modal-title"></h4>
	            </div>

	            <div id="modalBody" class="modal-body">
		            <h4>Observaciones</h4>

		            <div id="obtenerId" hidden="true"></div>
		            <div id="obtenertitulo" hidden="true"></div>
		            <div id="obtenerFechaIni" hidden="true"></div>
		            <div id="obtenerFechaFin" hidden="true"></div>
		            <div id="obtenerTodoeldia" hidden="true"></div>
		            <div id="obtenercolor" hidden="true"></div>
		            <div id="obtenertitulo" hidden="true"></div>

	            	<div class="form-group">
	                	<textarea class="form-control textarea-content" type="text" rows="4" id="descripcionTexto"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="submitButton">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>

	<!-- MODAL FORMULARIO APETITO-->
	<div id="modalApetito" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle3" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha3" class="col-sm-2 control-label"">Fecha</label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha3" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br><br>
	  				<div class="panel panel-default col col-md-2 " style="width: 25%;">			
	  				<div class="panel-heading"><b>Especificar Turno</b></div>
	  				<br>
						<div class="radio3">
						  <label><input type="radio" id="desayuno3" name="opRadio"> Desayuno</label>
						</div>
						<div class="radio3">
						  <label><input type="radio" id="almuerzo3" name="opRadio"> Almuerzo</label>
						</div>
						<div class="radio3">
						  <label><input type="radio" id="cena3" name="opRadio"> Cena</label>
						</div>
					<br>
					</div>

	  				<br><br><br>
					
					<div id="obtenerId3" hidden="true"></div>
		            <div id="obtenerFechaIni3" hidden="true"></div>
		            <div id="obtenerFechaFin3" hidden="true"></div>
		            <div id="obtenerTodoeldia3" hidden="true"></div>
		            <div id="obtenercolor3" hidden="true"></div>
		            <div id="obtenertitulo3" hidden="true"></div>
		            <div id="obtenerDesayuno3" hidden="true"></div>
		            <div id="obtenerAlmuerzo3" hidden="true"></div>
		            <div id="obtenerCena3" hidden="true"></div>
		            <div id="obtenerIdTipoEvento3" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto3"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarApetito">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO CUADRO DE SUEÑO MENSUAL -->
	<div id="modalSueño" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle4" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="numHoras" class="col-sm-4 control-label">Numero de horas dormidas:</label>
		            	<div class="col-sm-8">
	    					<input type="text" class="form-control" id="numHoras" style="width: 25%;">
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerId4" hidden="true"></div>
		            <div id="obtenerFechaIni4" hidden="true"></div>
		            <div id="obtenerFechaFin4" hidden="true"></div>
		            <div id="obtenerTodoeldia4" hidden="true"></div>
		            <div id="obtenercolor4" hidden="true"></div>
		            <div id="obtenertitulo4" hidden="true"></div>
		            <div id="obtenerHorasDormidas4" hidden="true"></div>
		            <div id="obtenerIdTipoEvento4" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto4"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarSueño">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO CONSULTAS Y HOSPITALIZACIONES -->
	<div id="modalHospital" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle5" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha5" class="col-sm-2 control-label">Fecha: </label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha5" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerId5" hidden="true"></div>
		            <div id="obtenerFechaIni5" hidden="true"></div>
		            <div id="obtenerFechaFin5" hidden="true"></div>
		            <div id="obtenerTodoeldia5" hidden="true"></div>
		            <div id="obtenercolor5" hidden="true"></div>
		            <div id="obtenertitulo5" hidden="true"></div>
		            <div id="obtenerIdTipoEvento5" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto5"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarHospital">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO ESTADO MENTAL -->
	<div id="modalMental" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle6" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha6" class="col-sm-2 control-label">Fecha: </label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha6" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerId6" hidden="true"></div>
		            <div id="obtenerFechaIni6" hidden="true"></div>
		            <div id="obtenerFechaFin6" hidden="true"></div>
		            <div id="obtenerTodoeldia6" hidden="true"></div>
		            <div id="obtenercolor6" hidden="true"></div>
		            <div id="obtenertitulo6" hidden="true"></div>
		            <div id="obtenerIdTipoEvento6" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto6"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarMental">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO CUADRO DE INCIDENCIAS COMPORTAMENTALES -> PROBLEMAS DE CONDUCTA EN DOS -->
	<div id="modalComportamentales" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle7" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">

	            <div class="form-group">
	         	   <label for="fecha7" class="col-sm-2 control-label">Fecha: </label>
	            	<div class="col-sm-4">
    					<input type="text" class="form-control placeFecha" id="fecha7" style="width: 50%;" placeholder="Placeholder" disabled>
    				</div>
    				<label for="usuario7" class="col-sm-2 control-label">Usuario: </label>
	            	<div class="col-sm-4">
    					<input type="text" class="form-control placeUsuario" id="usuario7"; placeholder="Placeholder" disabled>
    				</div>
  				</div>

  				<br><br>

  				<div class="form-group">
	         	   <label for="horaIncidencia" class="col-sm-2 control-label">Hora de la incidencia: </label>
	            	<div class="col-sm-4">
    					<input type="text" class="form-control placeHoraIncidencia" id="horaIncidencia7" style="width: 50%;" placeholder="Placeholder" disabled>
    				</div>
    				<label for="tipoIncidencia" class="col-sm-2 control-label">Tipo incidencia: </label>
	            	<div class="col-sm-4">
    					<input type="text" class="form-control placeTipoIncidencia" id="tipoIncidencia7"; placeholder="Placeholder" disabled>
    				</div>
  				</div>

  				<div class="form-group col-md-12">
            	<h4>¿Que ocurrió antes?</h4>
                	<textarea class="form-control" type="text" rows="4" id="queAntes"></textarea>
                </div>

  				<br><br>

  				<div class="form-group col-md-12">
            	<h4>¿Que hizo?</h4>
                	<textarea class="form-control" type="text" rows="4" id="queHizo"></textarea>
                </div>

  				<br><br>

  				<div class="form-group col-md-12">
            	<h4>¿Que ocurrió despues?</h4>
                	<textarea class="form-control" type="text" rows="4" id="queDespues"></textarea>
                </div>

  				<br><br>
				
				<div id="obtenerId7" hidden="true"></div>
	            <div id="obtenerFechaIni7" hidden="true"></div>
	            <div id="obtenerFechaFin7" hidden="true"></div>
	            <div id="obtenerTodoeldia7" hidden="true"></div>
	            <div id="obtenercolor7" hidden="true"></div>
	            <div id="obtenertitulo7" hidden="true"></div>
	            <div id="obtenerIdTipoEvento7" hidden="true"></div>

  	
	            </div>

	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardaIncidencia">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO PROBLEMA DE CONDUCTA - EN DOS CON EL ANTERIOR -->
	<div id="modalConducta" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle8" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha8" class="col-sm-2 control-label">Fecha: </label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha6" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerId8" hidden="true"></div>
		            <div id="obtenerFechaIni8" hidden="true"></div>
		            <div id="obtenerFechaFin8" hidden="true"></div>
		            <div id="obtenerTodoeldia8" hidden="true"></div>
		            <div id="obtenercolor8" hidden="true"></div>
		            <div id="obtenertitulo8" hidden="true"></div>
		            <div id="obtenerIdTipoEvento8" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto8"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarConducta">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO HIGIENE -->
	<div id="modalHigiene" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle9" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha9" class="col-sm-2 control-label"">Fecha</label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha9" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br><br>
	  				<div class="panel panel-default col col-md-2 " style="width: 25%;">			
	  				<div class="panel-heading"><b>Especificar Turno</b></div>
	  				<br>
						<div class="checkbox9">
						  <label><input type="checkbox" id="mañana9" name="turno[]" value="Mañana"> Mañana</label>
						</div>
						<div class="checkbox9">
						  <label><input type="checkbox" id="tarde9" name="turno[]" value="Tarde"> Tarde</label>
						</div>
						<div class="checkbox9">
						  <label><input type="checkbox" id="noche9" name="turno[]" value="Noche"> Noche</label>
						</div>
					<br>
					</div>

	  				<br><br><br>
					
					<div id="obtenerId9" hidden="true"></div>
		            <div id="obtenerFechaIni9" hidden="true"></div>
		            <div id="obtenerFechaFin9" hidden="true"></div>
		            <div id="obtenerTodoeldia9" hidden="true"></div>
		            <div id="obtenercolor9" hidden="true"></div>
		            <div id="obtenertitulo9" hidden="true"></div>
		            <div id="obtenerMañana9" hidden="true"></div>
		            <div id="obtenerTarde9" hidden="true"></div>
		            <div id="obtenerNoche9" hidden="true"></div>
		            <div id="obtenerIdTipoEvento9" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto9"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarHigiene">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO CONTROL DE ESFINTERES -->
	<div id="modalEsfinteres" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle10" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha10" class="col-sm-2 control-label"">Fecha</label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha9" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br><br>
	  				<div class="panel panel-default col col-md-2" style="width: 25%;">			
	  				<div class="panel-heading"><b>Especificar Turno</b></div>
	  				<br>
						<div class="checkbox10">
						  <label><input type="checkbox" id="mañana10" name="turno[]" value="Mañana"> Mañana</label>
						</div>
						<div class="checkbox10">
						  <label><input type="checkbox" id="tarde10" name="turno[]" value="Tarde"> Tarde</label>
						</div>
						<div class="checkbox10">
						  <label><input type="checkbox" id="noche10" name="turno[]" value="Noche"> Noche</label>
						</div>
					<br>
					</div>

	  				<br><br><br>
					
					<div id="obtenerId10" hidden="true"></div>
		            <div id="obtenerFechaIni10" hidden="true"></div>
		            <div id="obtenerFechaFin10" hidden="true"></div>
		            <div id="obtenerTodoeldia10" hidden="true"></div>
		            <div id="obtenercolor10" hidden="true"></div>
		            <div id="obtenertitulo10" hidden="true"></div>
		            <div id="obtenerMañana10" hidden="true"></div>
		            <div id="obtenerTarde10" hidden="true"></div>
		            <div id="obtenerNoche10" hidden="true"></div>
		            <div id="obtenerIdTipoEvento10" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto10"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarEsfinteres">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO CORRECION EN LA MESA -->
	<div id="modalMesa" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle11" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha11" class="col-sm-2 control-label"">Fecha</label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha9" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br><br>
	  				<div class="panel panel-default col col-md-2" style="width: 25%;">			
	  				<div class="panel-heading"><b>Especificar Turno</b></div>
	  				<br>
						<div class="radio11">
						  <label><input type="radio" id="desayuno11" name="opRadio"> Desayuno</label>
						</div>
						<div class="radio11">
						  <label><input type="radio" id="almuerzo11" name="opRadio"> Almuerzo</label>
						</div>
						<div class="radio11">
						  <label><input type="radio" id="cena11" name="opRadio"> Cena</label>
						</div>
					<br>
					</div>

	  				<br><br><br>
					
					<div id="obtenerId11" hidden="true"></div>
		            <div id="obtenerFechaIni11" hidden="true"></div>
		            <div id="obtenerFechaFin11" hidden="true"></div>
		            <div id="obtenerTodoeldia11" hidden="true"></div>
		            <div id="obtenercolor11" hidden="true"></div>
		            <div id="obtenertitulo11" hidden="true"></div>
		            <div id="obtenerDesayuno11" hidden="true"></div>
		            <div id="obtenerAlmuerzo11" hidden="true"></div>
		            <div id="obtenerCena11" hidden="true"></div>
		            <div id="obtenerIdTipoEvento11" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto11"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarMesa">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO OCUPACIONAL -->
	<div id="modalOcupacional" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle12" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha12" class="col-sm-2 control-label">Fecha: </label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha9" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerId12" hidden="true"></div>
		            <div id="obtenerFechaIni12" hidden="true"></div>
		            <div id="obtenerFechaFin12" hidden="true"></div>
		            <div id="obtenerTodoeldia12" hidden="true"></div>
		            <div id="obtenercolor12" hidden="true"></div>
		            <div id="obtenertitulo12" hidden="true"></div>
		           	<div id="obtenerIdTipoEvento12" hidden="true"></div>


	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto12"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarOcupacional">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO VIVIENDAS -->
	<div id="modalVivienda" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle13" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha13" class="col-sm-2 control-label">Fecha: </label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha9" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerId13" hidden="true"></div>
		            <div id="obtenerFechaIni13" hidden="true"></div>
		            <div id="obtenerFechaFin13" hidden="true"></div>
		            <div id="obtenerTodoeldia13" hidden="true"></div>
		            <div id="obtenercolor13" hidden="true"></div>
		            <div id="obtenertitulo13" hidden="true"></div>
		            <div id="obtenerIdTipoEvento13" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto13"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarVivienda">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- RELACIONES SOCIALES -->
	<div id="modalSocial" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitle14" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="fecha14" class="col-sm-2 control-label">Fecha: </label>
		            	<div class="col-sm-10">
	    					<input type="text" class="form-control placeFecha" id="fecha9" style="width: 25%;" placeholder="Placeholder" disabled>
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerId14" hidden="true"></div>
		            <div id="obtenerFechaIni14" hidden="true"></div>
		            <div id="obtenerFechaFin14" hidden="true"></div>
		            <div id="obtenerTodoeldia14" hidden="true"></div>
		            <div id="obtenercolor14" hidden="true"></div>
		            <div id="obtenertitulo14" hidden="true"></div>
		            <div id="obtenerIdTipoEvento14" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTexto14"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarsocial">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- MODAL FORMULARIO INCIDENCIA NOCHE -->
	<div id="modalIncidenciaSueño" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" style="width: 60%;">
	    	<div class="modal-content col-md-12">
	        	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 id="modalTitleInci" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
		            <div class="form-group">
		         	   <label for="numHoras" class="col-sm-4 control-label">Numero de horas dormidas:</label>
		            	<div class="col-sm-8">
	    					<input type="text" class="form-control" id="numHorasInci" style="width: 25%;">
	    				</div>
	  				</div>

	  				<br><br>
					
					<div id="obtenerIdInci" hidden="true"></div>
		            <div id="obtenerFechaIniInci" hidden="true"></div>
		            <div id="obtenerFechaFinInci" hidden="true"></div>
		            <div id="obtenerTodoeldiaInci" hidden="true"></div>
		            <div id="obtenercolorInci" hidden="true"></div>
		            <div id="obtenertituloInci" hidden="true"></div>
		            <div id="obtenerHorasDormidasInci" hidden="true"></div>
		            <div id="obtenerIdTipoEventoInci" hidden="true"></div>

	            	<div class="form-group col-md-12">
	            	<h4>Observaciones</h4>
	                	<textarea class="form-control" type="text" rows="4" id="descripcionTextoInci"></textarea>
	                </div>
	            </div>
	            <div class="modal-footer col-md-12">
                	<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                	<button type="submit" class="btn btn-primary" id="guardarIncidenciaSueño">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>	

	<!-- CALENDARIO -->
	<div class="row">
        <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border">
            	<h4 class="box-title"> </h4>
            		<div class="row justify-content-md-left">
	             		<a class="btn btn-primary" id="calendarioEventos">Calendario eventos</a>
	             		<a class="btn btn-primary" id="calendarioNoche">Calendario noche</a>
	             	</div>
	             	<br>
             
	<div id="menuCalendarioEventos">
          <!-- Single button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    Elige una categoria... <span class="caret"></span>
			  </button>

			  <ul class="dropdown-menu">
			    @foreach ($entidadTipoEventos as $key => $value)	
			    	@if ($key <= 15)			  
			    		<li><a href="#" id="{{ $key }}" class="categorias">{{ $value  }}</a></li>
			    	@endif
				@endforeach
			  </ul>
			</div>

			</div>
            <div class="box-body">
              <!-- the events -->
           		<div id="external-events" class="1" hidden="true">
           			@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 1 and $key <= 10)
				    		<div class="external-event" style="background-color:rgb(0, 166, 90)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="2" hidden="true">
            		@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 11 and $key <= 20)
				    		<div class="external-event" style="background-color:rgb(243, 156, 18)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="3" hidden="true">
	                @foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 21 and $key <= 27)
				    		<div class="external-event" style="background-color:rgb(0, 192, 239)"><font color="black">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="4" hidden="true">
	                @foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 28 and $key <= 33)
				    		<div class="external-event" style="background-color:rgb(0, 115, 183)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="5" hidden="true">
            		@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 34 and $key <= 43)
				    		<div class="external-event" style="background-color:rgb(57, 204, 204)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="6" hidden="true">
	                @foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 44 and $key <= 58)
				    		<div class="external-event" style="background-color:rgb(210, 214, 222)"><font color="black">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="7" hidden="true">
	                @foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 59 and $key <= 64)
				    		<div class="external-event" style="background-color:rgb(255, 231, 6)"><font color="black">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="8" hidden="true">
            		@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 65 and $key <= 69)
				    		<div class="external-event" style="background-color:rgb(1, 255, 112)"><font color="black">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="9" hidden="true">
	              	@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 70 and $key <= 72)
				    		<div class="external-event" style="background-color:rgb(221, 75, 57)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="10" hidden="true">
	                @foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 73 and $key <= 78)
				    		<div class="external-event" style="background-color:rgb(96, 92, 168)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="11" hidden="true">
            		@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 79 and $key <= 81)
				    		<div class="external-event" style="background-color:rgb(255, 133, 27)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="12" hidden="true">
	                @foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 82 and $key <= 90)
				    		<div class="external-event" style="background-color:rgb(210, 105, 30)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="13" hidden="true">
	                @foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 91 and $key <= 97)
				    		<div class="external-event" style="background-color:rgb(0, 139, 139)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="14" hidden="true">
            		@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 98 and $key <= 107)
				    		<div class="external-event" style="background-color:rgb(255, 20, 147)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>

            	<div id="external-events" class="15" hidden="true">
            		@foreach ($tipoEventos as $key => $value)  
           				@if ($key >= 108 and $key <= 114)
				    		<div class="external-event" style="background-color:rgb(255, 182, 193)"><font color="white">{{ $value }}</font></div>
				    	@endif
					@endforeach
            	</div>
    </div> <!-- CALENDARIO EVENTOS -->
    <!-- 
    <div id="menuCalendarioNoche" hidden="true">
		<div id="external-events">
			<div class="external-event" style="background-color:red"><font color="white">Incidencia</font></div>
		</div>
	</div> 
	-->
	
	 <div id="menuCalendarioNoche" hidden="true">
		<div id="external-events">
			@foreach ($tipoEventos as $key => $value)  
           		@if ($key >= 115 and $key <= 115)
					<div class="external-event" style="background-color:red"><font color="white">{{ $value }}</font></div>
				@endif
			@endforeach
		</div>
	</div> 
	
	<!-- CALENDARIO NOCHE -->


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
              <div id="calendarNoche"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  	</div><!-- /.panel-body -->
   	<div class="form-group">
   		<div class="container">
			<div class="row justify-content-md-center">
				<!-- {!! Form::submit('Volver', ['class' => 'btn btn-primary']) !!} -->
				<a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
			</div>
		</div>
  	</div><!-- /.panel -->
</div>
</div>

<footer class="admin-footer">
	<nav class="navbar navbar-default">
  		<div class="collapse navbar-collapse">
  			<p class="navbar-text">Todos los derechos reservados &copy {{ date('Y') }} </p>
  			<p class="navbar-text navbar-right"><b>José Castilla Benítez <br></b></p>
  		</div>
  	</nav>


  	<script type="text/javascript">
		$.ajaxSetup({
   			headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
		});
	</script>

</footer>

<script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
<script src="{{ asset('plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('plugins/trumbowyg/trumbowyg.js') }}"></script>
<!-- <script type="text/javascript" src="dist/bootstrap-clockpicker.min.js"></script> -->
<script src="{{ asset('plugins/clockpicker/dist/bootstrap-clockpicker.min.js') }}"></script>


</body>
</html>


