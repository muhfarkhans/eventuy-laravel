<div>
    <ol style="list-style: number">
        @if ($getState() != null)
            @foreach ($getState() as $item)
                <li>{{$item}}</li>
            @endforeach
        @endif
    </ol>
</div>