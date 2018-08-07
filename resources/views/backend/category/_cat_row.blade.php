@foreach($categories as $category)
  <tr id="row_{{$category->id}}">
    <td>
      <a href="{{route('backend.category_detail', ['id' => $category->id])}}">
        {{str_repeat(\App\Http\Controllers\Backend\CategoryController::LIST_INDENT,$category->depth)}}{{$category->cat_title}}
      </a>

    </td>
    <td class="text-right">
      0
    </td>
    <td class="text-right">
                        <span style="color: {{$category->cat_active > 0 ? 'green' : 'red'}}"
                              class="glyphicon glyphicon-ok-sign"></span>
    </td>
    <td class="text-right">

      <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
         data-link="{{route('backend.category.check_moving',['id' => $category->id, 'direction' => 'left'])}}">
        <i class="fa fa-arrow-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="LEFT"></i>
      </a>
      <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
         data-link="{{route('backend.category.check_moving',['id' => $category->id, 'direction' => 'up'])}}">
        <i class="fa fa-arrow-up" data-toggle="tooltip" data-placement="top" title="" data-original-title="UP"></i>
      </a>
      <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
         data-link="{{route('backend.category.check_moving',['id' => $category->id, 'direction' => 'down'])}}">
        <i class="fa fa-arrow-down" data-toggle="tooltip" data-placement="top" title="" data-original-title="DOWN"></i>
      </a>
      <a href="javascript:void(0);" class="category-move btn btn-xs btn-default"
         data-link="{{route('backend.category.check_moving',['id' => $category->id, 'direction' => 'right'])}}">
        <i class="fa fa-arrow-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="RIGHT"></i>
      </a>

      <a href="{{route('backend.category.create', ['cat_id' => $category->id])}}" class="btn btn-xs btn-default">
        <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="ADD"></i>
      </a>
      <a href="{{route('backend.category.edit', ['id' => $category->id])}}" class="btn btn-xs btn-default">
        <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDIT"></i>
      </a>
      <a href="javascript:void(0);" class="btn btn-xs btn-danger category-delete-button"
         data-link="{{route('backend.category.delete',['id'=>$category->id])}}" >
        <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="DELETE"></i>
      </a>
      {{--EDIT/DELETE/UP/DOWN/ADD_CHILD--}}
    </td>
  </tr>
@endforeach