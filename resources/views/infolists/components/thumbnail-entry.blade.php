<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <img src="{{ Storage::url($getState()) }}" alt="" class="rounded"
            style="width: 100%; max-height: 24rem; object-fit: cover">
    </div>
</x-dynamic-component>