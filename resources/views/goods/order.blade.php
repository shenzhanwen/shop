
@include('layouts/shop')
@include('public/head')
@extends('public/top')


<!DOCTYPE html>
<html lang="zxx">
<body>

	

	
    <!-- wishlist -->

	<div class="wishlist section">
		<div class="container">
           
					<div class="row">
						<div class="col s5">
							<h5>订单编号</h5>
						</div>
						<div class="col s7">
							<h5 id='order'>{{$res->order_no}}</h5>
						</div>
					</div>
					<div class="row">
						<div class="col s5">
							<h5>状态</h5>
						</div>
						<div class="col s7">
                            <h5> <td>@if($res->state == 1)未支付@else已支付</a>@endif</td>
						</div>
					</div>
					<div class="row">
						<div class="col s5">
							<h5>价格</h5>
						</div>
						<div class="col s7">
							<h5>{{$total}}</h5>
						</div>
                    </div>
                    <div class="row">
						<div class="col s5">
							<h5>时间</h5>
						</div>
						<div class="col s7">
						<span class="f1">
						<span class="times" oid="{{$res->order_no}}"  order-state="{{$res->state}}"  end-time="{{date('Y/m/d H:i:s',$res->end_time+29500)}}"></span>后过期
						</span>
                     
						</div>
					</div>
					<div class="row">
						<div class="col s5">
							<h5>删除订单</h5>
						</div>
						<div class="col s7">
							<h5><a href="{{url('/goods/dele',['order_id'=>$res->order_id])}}"><i class="fa fa-trash"></a></i></h5>
						</div>
					</div>
					<div class="row">
						<div class="col 12">
							<a href="pay" >确认支付</a>
						</div>
					</div>
				</div>
            </div>
          
		</div>
	</div>

</body>
</html>
<script type="text/javascript">


    $(function(){


      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });


        getTime();//调用函数
		function getTime(){
			$(".times").each(function(){
			var _this = $(this);
			var end_time = _this.attr('end-time'); //结束时间
			var state = _this.attr('order-state'); //订单状态
			var oid = parseInt(_this.attr('oid'));//订单号


			var endDate = new Date(end_time);




	            endDate = endDate.getTime();//1970-截止时间  从1970年到截止时间有多少毫秒
	 
	            //获取一个现在的时间
	            var nowdate = new Date;
	            // alert(endDate)
	            nowdate = nowdate.getTime(); //现在时间-截止时间  从现在到截止时间有多少毫秒
	 
	            //获取时间差 把毫秒转换为秒
	            var diff = parseInt((endDate - nowdate) / 1000);




	            if(diff <= 0 ){
	            	//window.location.reload();
	            	
	            	// alert(oid)
	            	
	            	$.ajax({
	            			url:"{{url('home/orderdate')}}",
	            			type:"get",
	            			dataType:"json",
	            			data:{oid:oid},
	            			success:function(msg){
	            				// alert(msg);
	            			}




	            		});


	            	_this.parent('.f1').html('已过期');
					
	            }
	 
	            h = parseInt(diff / 3600);//获取还有小时
	            m = parseInt(diff / 60 % 60);//获取还有分钟
	            s = diff % 60;//获取多少秒数
	 
	            //将时分秒转化为双位数
	            h = setNum(h);
	            m = setNum(m);
	            s = setNum(s);
	            //输出时分秒
	            _this.html(m + "分" + s + "秒");
			});
			window.setTimeout(function() {
	    		getTime();
	  		}, 1000);
		}


	 //window.setTimeout(getTime, 1000);
        //设置函数 把小于10的数字转换为两位数
        function setNum(num) {
            if (num < 10) {
                num = "0" + num;
            }
            return num;
        }
  });

		  

  </script>