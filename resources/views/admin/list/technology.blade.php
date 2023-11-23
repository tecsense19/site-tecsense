<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col" class="text-center" style="width: 4%">#</th>
            <th scope="col" class="text-center" style="width: 16%">Title</th>
            <th scope="col" class="text-center" style="width: 60%">Description</th>
            <th scope="col" class="text-center" style="width: 10%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($technologyDataArr) > 0)
            @php $i = 1; @endphp
            @foreach($technologyDataArr as $singleRec)
                <tr>
                    <td rowspan="2" scope="row" class="text-center">{{ $i }}</td>
                    <td scope="row" class="text-center">{{ $singleRec->technology_name }}</td>
                    <td scope="row" class="text-left">{{ $singleRec->technology_description }}</td>
                    <td scope="row" class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="technology-edit" style="cursor: pointer;" data-technology="{{ $singleRec }}">
                                <i class="icon-copy fa fa-edit m-2" aria-hidden="true"></i>
                            </div>
                            <div class="technology-delete" style="cursor: pointer;" data-id="{{ $singleRec->id }}"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="10" scope="row" class="text-center">
                        <div class="d-flex">
                            @foreach($singleRec->technologyImages as $simg)
                                @php $imgPath = $simg->image_path ? url('/').'/'.$simg->image_path : ''; @endphp
                                <div class="flex-wrap text-center mx-2">
                                    @if($imgPath)
                                        <div><img src="{{ $imgPath }}" style="width: 25px; height: 25px;"/></div>
                                    @endif
                                    <div>{{ $simg->title }}</div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @php $i++; @endphp
            @endforeach
        @else
            <tr scope="row" style="text-align: center;">
                <td colspan="5">Technology List Not Found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $technologyDataArr->links('pagination') !!}