@extends('layout')
@section('content')
<style>
td a{
  margin-left: 15px
}
</style>
  <div class="panel panel-body">
    <section class="content">
        <div class="panel panel-default">
            <div class="panel-heading"><b>Danh sách loại SP</b></div>
            <div class="panel-body">
                @if(Session::has('error'))
                  <div class="alert alert-danger">{{Session::get('error')}}</div>
                @endif
                <table  class="table table-hover">
                  <thead>
                    <tr>
                      <th>STT</th>
                      <th>Tên Loại</th>
                      <th width="40%">Mô tả</th>
                      <th>Hình</th>
                      <th>Tùy chọn</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $stt=1 ?>
                    @foreach($type as $loaisp)
                    <tr>
                      <td>{{$stt}}</td>
                      <td>{{$loaisp->name}}</td>
                      <td width="40%">{{$loaisp->description}}</td>
                      <td>
                          <img src="admin_theme/img/product/{{$loaisp->image}}" alt="{{$loaisp->name}}" width="150px">
                      </td>
                      <td>
                        <a href="{{route('list-product',[$loaisp->id])}}"><i class="fa fa-list fa-2x" aria-hidden="true" title="Xem sản phẩm"></i></a>
                        <a href="{{route('edit-type',$loaisp->id)}}"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true" title="Sửa"></i></a>
                        <a href="#"><i class="fa fa-trash-o fa-2x" aria-hidden="true" title="Xóa"></i> </a>

                      </td>
                    </tr>
                    <?php $stt++?>
                    @endforeach
                  </tbody>
                </table>
            </div>
        </div>
    </section>
  </div>
@endsection