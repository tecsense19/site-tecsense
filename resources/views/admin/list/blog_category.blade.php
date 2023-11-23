<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col" class="text-center" style="width: 4%">#</th>
            <th scope="col" class="text-left" style="width: 30%">Title</th>
            <th scope="col" class="text-center" style="width: 6%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($categoryDataArr) > 0)
            @php $i = 1; @endphp
            @foreach($categoryDataArr as $singleRec)
                <tr>
                    <td scope="row" class="text-center">{{ $i }}</td>
                    <td scope="row" class="text-left">{{ $singleRec->title }}</td>
                    <td scope="row" class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="category-edit" style="cursor: pointer;" data-category="{{ $singleRec }}">
                                <i class="icon-copy fa fa-edit m-2" aria-hidden="true"></i>
                            </div>
                            <div class="category-delete" style="cursor: pointer;" data-id="{{ $singleRec->id }}"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></div>
                        </div>
                    </td>
                </tr>
            @php $i++; @endphp
            @endforeach
        @else
            <tr scope="row" style="text-align: center;">
                <td colspan="3">Blog Category List Not Found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $categoryDataArr->links('pagination') !!}