<table class="table table-bordered">

    <thead>

        <tr>

            <th scope="col" class="text-center" style="width: 4%">#</th>

            <th scope="col" class="text-center" style="width: 16%">Why Choose</th>

            <th scope="col" class="text-center" style="width: 16%">Logo</th>

            <th scope="col" class="text-center" style="width: 16%">Why Choose Detail Title</th>

            <th scope="col" class="text-center" style="width: 16%">Description</th>

            <th scope="col" class="text-center" style="width: 10%">Action</th>

        </tr>

    </thead>

    <tbody>

        @if(count($whychooseDataArr) > 0)

            @php $i = 1; @endphp

            @foreach($whychooseDataArr as $singleRec)
           
                @php 
                    $why_choose_detail_Url = $singleRec->why_choose_detail_pic ? url('/').'/'.$singleRec->why_choose_detail_pic : ''; 
                @endphp
                <tr>

                    <td rowspan="2" scope="row" class="text-center">{{ $i }}</td>

                    @foreach($singleRec->serviceTitle as $title)
                    <td scope="row" class="text-center">{{ $title->service_title }}</td>
                    @endforeach

                    <td scope="row" class="text-center">
                        @if($why_choose_detail_Url)<img src="{{ $why_choose_detail_Url }}" style="width: 25px; height: 25px;" />@else - @endif
                    </td>

                    <td scope="row" class="text-center">{{ $singleRec->why_choose_detail_title }}</td>

                    <td scope="row" class="text-center">{{ $singleRec->why_choose_detail_description }}</td>

                    <td scope="row" class="text-center">

                        <div class="d-flex justify-content-center">

                            <div class="why-choose-edit" style="cursor: pointer;" data-why_choose-detail="{{ $singleRec }}">

                                <i class="icon-copy fa fa-edit m-2" aria-hidden="true"></i>

                            </div>

                            <div class="why-choose-detail-delete" style="cursor: pointer;" data-id="{{ $singleRec->id }}"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></div>

                        </div>

                    </td>

                </tr>

                <tr>

                  <td colspan="10" scope="row" class="text-center">

                        <div class="d-flex">

                            @foreach($singleRec->whyChooseDetailImages as $simg)

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

                <td colspan="6">Services List Not Found.</td>

            </tr>

        @endif

    </tbody>

</table>

<!--  -->