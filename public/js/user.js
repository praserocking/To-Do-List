$(document).ready(function(){
		$('#loading').hide();
		$("#newTaskForm").on('submit',function(){
			$('#loading').show();
			$.post(
				$(this).prop('action'),{
					"_token":$(this).find('input[name=_token]').val(),
					"task_shortname":$("#shortname").val(),
					"task_description":$("#description").val(),
					"task_deadline":$("#deadline").val(),
					"task_priority":$("#priority").val()
				},function(data){
					var markup="<ul>";
					for(i in data)
						markup+="<li>"+data[i]+"</li>";
					markup+="</ul>";
					if(markup==="<ul></ul>"){
						document.getElementById('info').innerHTML="<h4 style='color:green'>Success,Reload page to see updated tasks</h4>";
						document.getElementById('newTaskForm').reset();
					}
					else
						document.getElementById('error').innerHTML="<h4 style='color:red'>Errors</h4>"+markup;
				},'json'
				);
			$('#loading').hide();
			return false;
		});
		$("#deleteForm").on('submit',function(){
			$('#loading').show();
			$.post(
				$(this).prop('action'),{
					"_token":$(this).find('input[name=_token]').val(),
					"task_name":$("#hidtaskname").val(),
					"task_deadline":$("#hidtaskdeadline").val()
				},function(data){
					document.getElementById('info').innerHTML=data;
				},'json'
				);
			$('#loading').hide();
			return false;
		});
		$("#markForm").on('submit',function(){
			$('#loading').show();
			$.post(
				$(this).prop('action'),{
					"_token":$(this).find('input[name=_token]').val(),
					"task_name":$("#mrktaskname").val(),
					"task_deadline":$("#mrktaskdeadline").val()
				},function(data){
					document.getElementById('info').innerHTML=data;
				},'json'
				);
			$('#loading').hide();
			return false;
		});
	});
	function delTask(name,deadline){
		document.getElementById('hidtaskname').value=name;
		document.getElementById('hidtaskdeadline').value=deadline;
		$("#deleteForm").submit();
	}
	function markTask(name,deadline){
		document.getElementById('mrktaskname').value=name;
		document.getElementById('mrktaskdeadline').value=deadline;
		$("#markForm").submit();
	}