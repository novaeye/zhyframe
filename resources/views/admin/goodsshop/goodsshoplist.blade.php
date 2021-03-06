@extends('admin.common.zhyframe')

@section('head')
<meta name="csrf-token" content="{{csrf_token()}}">
<link href="{{asset('js/plugins/bootstrap-table/bootstrap-table.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<style>
table thead tr th{
	text-align:center;
}
table tbody tr td{
	text-align:center;
}
</style>
<body class="font16">
<div class="wrapper animated fadeInRight">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h2>商品商铺列表</h2>
		</div>
		<div class="ibox-content">
			<div class="row row-lg">
				<div class="col-sm-12">
					<!-- Example Events -->
					<div class="example-wrap">
						<div class="btn-group hidden-xs" id="tableEventsToolbar" role="group">
							
						</div>
						<table id="tableEvents" data-height="auto" data-mobile-responsive="true">
							<thead>
								<tr>
									<th data-field="goodsshop_id">商品商铺ID</th>
									<th data-field="goodsshop_remark">商铺名称</th>
									<th data-field="goodsshop_add_time_format">申请时间</th>
									<th data-field="goodsshop_confirm_time_format">处理时间</th>
									<th data-field="goodsshop_check_format">审核状态</th>
									<th data-field="goodsshop_status_format">商铺状态</th>
									<th data-field="goodsshop_remark">后台备注</th>
									<th data-field="goodsshop_operation">操作</th>
								</tr>
							</thead>
						</table>
					</div>
					<!-- End Example Events -->
				</div>
			</div>
		</div>
    </div>
</div>
@endsection

@section('footer')
<!-- Bootstrap table -->
<script src="{{asset('js/plugins/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('js/plugins/bootstrap-table/bootstrap-table-zh-CN.min.js')}}"></script>
<script src="{{asset('js/public.js')}}"></script>
<script>
var datalist = {!!$goodsshoplist!!};
(function(document, window, $) {
	'use strict';
	(function() {
		$('#tableEvents').bootstrapTable({
			data: datalist,
			search: true,
			pagination: true,
			showRefresh: true,
			showToggle: false,
			showColumns: true,
			iconSize: 'outline',
			toolbar: '#tableEventsToolbar',
			icons: {
				refresh: 'fa fa-refresh',
				toggle: 'fa fa-list-alt',
				columns: 'fa fa-list'
			}
		});
	})();
})(document, window, jQuery);
</script>
@endsection