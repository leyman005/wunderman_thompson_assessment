$(document).ready(function(){
	$.ajax({
		url:"getIds",
		type:"post",
		async:true,
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		success:function(response){
			if(response)
			{
				$.ajax({
					url:"getItems",
					type:"post",
					async:true,
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data:{'ids':response},
					success:function(res){
						console.log(res);
					}
				})
			}
		},
		error:function(response){
			console.log(response)
		}
	// })
});