$(document).ready(function(){
	
	todoList();
	discussionWidget();
	$("#start-click").click();
	$("#in-list-click").click();
	$("#out-list-click").click();

/* ---------- Datable ---------- */

	$('.datatable').dataTable({
		"sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
		"bPaginate": false,
		"bFilter": false,
		"bLengthChange": false,
		"bInfo": false,		
	});
	
	$('.countries').dataTable({
		"sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
		"bPaginate": false,
		"bFilter": false,
		"bLengthChange": false,
		"bInfo": false,
		// Disable sorting on the first column
		"aoColumnDefs" : [ {
			'bSortable' : false,
			'aTargets' : [ 0 ]
		} ]
	});
	
	

/* ---------- Placeholder Fix for IE ---------- */

	$('input, textarea').placeholder();

/* ---------- Auto Height texarea ---------- */

	$('textarea').autosize();
	
	$('#recent a:first').tab('show');
	$('#recent a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	}); 
	
/*------- Main Calendar -------*/

	$('#external-events div.external-event').each(function() {

		// it doesn't need to have a start or end
		var eventObject = {
			title: $.trim($(this).text()) // use the element's text as the event title
		};
		
		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject);
		
		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		});
		
	});
	
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('.calendar').fullCalendar({
		header: {
			right: 'next',
			center: 'title',
			left: 'prev'
		},
		defaultView: 'month',
		editable: true,
		events: [
			{
				title: 'All Day Event',
				start: '2014-06-01'
			},
			{
				title: 'Long Event',
				start: '2014-06-07',
				end: '2014-06-10'
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: '2014-06-09 16:00:00'
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: '2014-06-16 16:00:00'
			},
			{
				title: 'Meeting',
				start: '2014-06-12 10:30:00',
				end: '2014-06-12 12:30:00'
			},
			{
				title: 'Lunch',
				start: '2014-06-12 12:00:00'
			},
			{
				title: 'Birthday Party',
				start: '2014-05-10 18:05:00'
			},
			{
				title: 'Click for Google',
				url: 'http://google.com/',
				start: '2014-06-28'
			}
		]
	});
	
	
/*------- Realtime Update Chart -------*/
	
	$(function() {

		 // we use an inline data source in the example, usually data would
	// be fetched from a server
	var data = [], totalPoints = 60;
	function getRandomData() {
		if (data.length > 0)
			data = data.slice(1);

		// do a random walk
		while (data.length < totalPoints) {
			var prev = data.length > 0 ? data[data.length - 1] : 50;
			var y = prev + Math.random() * 10 - 5;
			if (y < 0)
				y = 0;
			if (y > 100)
				y = 100;
			data.push(y);
		}

		// zip the generated y values with the x values
		var res = [];
		for (var i = 0; i < data.length; ++i)
			res.push([i, data[i]])
		return res;
	}

	// setup control widget
	var updateInterval = 3000;
	$("#updateInterval").val(updateInterval).change(function () {
		var v = $(this).val();
		if (v && !isNaN(+v)) {
			updateInterval = +v;
			if (updateInterval < 1)
				updateInterval = 1;
			if (updateInterval > 2000)
				updateInterval = 2000;
			$(this).val("" + updateInterval);
		}
	});

	
	if($("#realtime-update").length)
	{
		var options = {
			series: { shadowSize: 1 },
			lines: { fill: true, fillColor: { colors: [ { opacity: 1 }, { opacity: 0.1 } ] }},
			yaxis: { min: 0, max: 100 },
			xaxis: { show: false },
			colors: ["#34495E"],
			grid: {	tickColor: "#EEEEEE",
					borderWidth: 0 
			},
		};
		console.log([getRandomData()]);
		var plot = $.plot($("#realtime-update"), [ getRandomData() ], options);
		function update() {
			plot.setData([ getRandomData() ]);
			// since the axes don't change, we don't need to call plot.setupGrid()
			plot.draw();
			
			setTimeout(update, updateInterval);
		}

		update();
	}
	
});
})
function in_callback(data){
	var b='';
	console.log(data);
	if(data.errno==0){
		$('#admin_index_in').removeClass('hide');
		if(data.edit_auth){
			$('#edit_auth').removeClass('hide');
		}else{
			$('#edit_auth').addClass('hide');
		}
		var length=data.data.length;
		if(length==0){
		b+='<tr><td colspan="5" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+data.data[i]['name']+'</td>';
				b+='<td>'+data.data[i]['class']+'</td>';
				if(data.data[i]['status']==1){
					b+='<td>已审核</td>';
				}else{
					b+='<td>审核中</td>';
				}
				b+='<td>'+data.data[i]['create_time']+'</td>';
				if(data.edit_auth){
					b+='<td>';
					b+='<a href="'+head_url+'works/edit.html?id='+data.data[i]['id']+'" class=" bk-fg-darken"><small>编辑</small> <i class="fa  fa-pencil"></i></a>';
					b+='</td>';
				}
				b+='</tr>';
		}
		}
		$("#api-in-list").html(b);
	}else if(data.errno==404){
		$('#admin_index_in').remove();
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}
function count_callback(data){
	var b='';
	console.log(data);
	if(data.errno==0){
		$('#admin_count_index').removeClass('hide');
		if(data.data.works){
			$(".works").html(data.data.works);
		}
		if(data.data.yes_works){
			$(".yes_works").html(data.data.yes_works);
		}
		if(data.data.member){
			$(".member").html(data.data.member);
		}
		if(data.data.month_member){
			$(".month_member").html(data.data.month_member);
		}
		if(data.data.visit){
			$(".visit").html(data.data.visit);
		}
		if(data.data.yes_visit){
			$(".yes_visit").html(data.data.yes_visit);
		}
		if(data.data.check){
			$(".check").html(data.data.check);
		}
		if(data.data.yes_check){
			$(".yes_check").html(data.data.yes_check);
		}						
	}else if(data.errno==404){
		$('#admin_count_index').remove();
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}





