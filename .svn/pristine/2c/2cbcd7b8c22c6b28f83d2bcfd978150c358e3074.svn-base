<nav class="ebsig-page-nav">
    <ul class="pagination">

        @if(isset($pageLinks['previous']['link']))
            <li>
                <a href="{{$pageLinks['previous']['link']}}" aria-label="Previous" data-paging="{{$pageLinks['previous']['page']}}"><span aria-hidden="true">«</span></a>
            </li>
        @else
            <li class="disabled">
                <a href="javascript:;" aria-label="Previous"><span aria-hidden="true">«</span></a>
            </li>
        @endif

        @foreach ($pageLinks['link'] as $links)
            @if($pageLinks['pageIndex'] == $links['text'])
                <li class="active"><a href="javascript:;">{{$links['text']}} <span class="sr-only">(current)</span></a></li>
            @else
                @if($links['href'] == '')
                    <li><a href="javascript:;" data-paging="{{$links['text']}}">{{$links['text']}}</a></li>
                @else
                    <li><a href="{{$links['href']}}" data-paging="{{$links['text']}}">{{$links['text']}}</a></li>
                @endif
            @endif
        @endforeach

        @if(isset($pageLinks['next']['link']))
            <li>
                <a href="{{$pageLinks['next']['link']}}" aria-label="Next" data-paging="{{$pageLinks['next']['page']}}">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        @else
            <li class="disabled">
                <a href="javascript:;" aria-label="Next"><span aria-hidden="true">»</span></a>
            </li>
        @endif
    </ul>
</nav>

