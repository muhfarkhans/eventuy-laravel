<div>
    <ol style="list-style: number">
        @if (json_decode($getState()) != null)
            @foreach (json_decode($getState()) as $item)
                <li>{{$item}}</li>
            @endforeach
        @endif
    </ol>
</div>