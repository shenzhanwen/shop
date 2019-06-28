
@include('layouts/shop')
@include('public/head')
@extends('public/top')
	<!-- register -->
	<div class="pages section">
	<meta name="csrf-token" content="{{ csrf_token() }}"> 
		<div class="container">
			<div class="pages-head">
				<h3>REGISTER</h3>
			</div>
			<div class="register">
				<div class="row">
					<form class="col s12">
						<div class="input-field">
							<input type="text" placeholder="EMAIL" class="validate" required name='name'id="name">
						</div>
						<div class="input-field">
							<input type="text" placeholder="CODE" class="validate"  name='code'id="code">
							<button>获取验证码</button>
						</div>
						<div class="input-field">
							<input type="password" placeholder="PASSWORD" class="validate" required id="pwd"> 
						</div>
						<div class="btn button-default" id='stn'>REGISTER</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- end register -->
	<!-- loader -->
	<div id="fakeLoader"></div>
	<!-- end loader -->
</body>
</html>
<script>
	$(function(){
		$.ajaxSetup({     
        headers: 
        {         
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
            } 
            });
		$('#stn').click(function(){
			var name = $('#name').val();
			var pwd=$('#pwd').val();
			var code = $('#code').val();
			if(name == ''){
				alert('邮箱不能为空');
				return false;
			}
			if(code == ''){
				alert('验证码不能为空');
				return false;
			}
			if(pwd == ''){
				alert('密码不能为空');
				return false;

			}else{
				//唯一性验证
				$.post(
                "checkname",
                {name:name,pwd:pwd},
                function(msg){
                    if(msg.code == 1){
                        if(msg.count>=1){
                        alert('该标题已存在');
                    }else{
						//验证码验证
				$.post(
					"checkcode",
					{name:name,code:code},
					function(msg){
						if(msg.code == 1){
							alert(msg.res);
						}else{
							//注册
				$.post(
					"cate",
					{name:name,pwd:pwd},
					function(msg){
						if(msg.code==1){
							alert('注册成功');
							location.href="/login/login"
						}else{
							alert('注册成功');
						}
					});
				}
			});
		}
	}
});	
	return false;
	}		
	})
		//发送验证码
		$('button').click(function(){
			var name = $('#name').val();
			var code = $('#code').val();
			$.post(
				"check",
				{name:name},
				function(res){
					if(res.code == 1){
                            alert(res.res);
                        }else{
                            alert(res.res);
                        }
					}
				)
			})
		});
</script>

