<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="formInput">
        <x-filament::input.wrapper>
            <x-filament::input type="text" x-model="state" x-bind:required="required" />
        </x-filament::input.wrapper>

        <p x-text="slugify()" clas="mt-1"></p>
    </div>

    @script
    <script>
        Alpine.data('formInput', () => ({
            prependText: '',
            required: false,
            state: $wire.$entangle('{{ $getStatePath() }}'),
            init() {
                this.required = '{{ $field->getRequiredValidationRule() }}' === 'required';
                this.prependText = '{{ $field->getPrependText() }}' === '' ? '{{ env('APP_URL') }}/' : '{{ $field->getPrependText() }}';
            },
            slugify() {
                return this.prependText + this.state.toLowerCase().trim().replace(/[\s-]+/g, '-').replace(/[^\w-]+/g, '').replace(/--+/g, '-').replace(/^-+|-+$/g, '');
            }
        }))
    </script>
    @endscript
</x-dynamic-component>