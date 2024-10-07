<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div>
        @if ($field->getState() != null)
            <div class="flex flex-wrap gap-1">
                @foreach ($field->getState() as $item)
                    <x-filament::badge>
                        <span>{{ $item}}</span>
                    </x-filament::badge>
                @endforeach
            </div>
        @endif
    </div>
</x-dynamic-component>