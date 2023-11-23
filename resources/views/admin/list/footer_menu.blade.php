<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col" class="text-center" style="width: 4%">#</th>
            <th scope="col" class="text-left" style="width: 23%">Menu Category</th>
            <th scope="col" class="text-left" style="width: 23%">Menu Image</th>
            <th scope="col" class="text-left" style="width: 23%">Menu Title</th>
            <th scope="col" class="text-left" style="width: 23%">Menu Link</th>
            <th scope="col" class="text-center" style="width: 6%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($menuDataArr) > 0)
            @php $i = 1; @endphp
            @foreach($menuDataArr as $singleRec)
                @php $imagePath = $singleRec->image_path ? url('/').'/'.$singleRec->image_path : ''; @endphp
                <tr>
                    <td scope="row" class="text-center">{{ $i }}</td>
                    <td scope="row" class="text-left">@if(isset($singleRec->menuCategory)) {{ $singleRec->menuCategory['title'] }} @else - @endif</td>
                    <td scope="row" class="text-left">
                        @if($imagePath)
                            <div><img src="{{ $imagePath }}" alt="Menu Icon" style="width: 25px; height: 25px; background: gray;"></div>
                        @else
                            -
                        @endif
                    </td>
                    <td scope="row" class="text-left">{{ $singleRec->menu_title }}</td>
                    <td scope="row" class="text-left">@if($singleRec->menu_link) {{ $singleRec->menu_link }} @else - @endif</td>
                    <td scope="row" class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="menu-edit" style="cursor: pointer;" data-menu="{{ $singleRec }}">
                                <i class="icon-copy fa fa-edit m-2" aria-hidden="true"></i>
                            </div>
                            <div class="menu-delete" style="cursor: pointer;" data-id="{{ $singleRec->id }}"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></div>
                        </div>
                    </td>
                </tr>
            @php $i++; @endphp
            @endforeach
        @else
            <tr scope="row" style="text-align: center;">
                <td colspan="6">Footer Menu List Not Found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $menuDataArr->links('pagination') !!}