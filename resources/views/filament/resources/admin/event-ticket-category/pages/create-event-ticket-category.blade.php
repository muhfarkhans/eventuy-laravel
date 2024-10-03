<x-filament-panels::page>
    <x-filament-panels::form wire:submit="create">
        {{ $this->form }}

        <div class="flex flex-col md:flex-row w-full rounded-lg"
            style="height: 350px; background-color: {{ $this->data['color'] }}">
            <div class="flex-1 p-4 justify-center relative">
                <div class="w-8 h-8 rounded absolute"
                    style="background-color: {{ $this->data['color_secondary'] }} ;top: 16px; left: 16px"></div>
                <div class="w-8 h-8 rounded absolute"
                    style="background-color: {{ $this->data['color_secondary'] }} ;bottom: 16px; left: 16px"></div>
                <div class="w-8 h-8 rounded absolute"
                    style="background-color: {{ $this->data['color_secondary'] }} ;top: 16px; right: 16px"></div>
                <div class="w-8 h-8 rounded absolute"
                    style="background-color: {{ $this->data['color_secondary'] }} ;bottom: 16px; right: 16px"></div>

                <div class="flex-1 p-4 w-full h-full">
                    <h2 class="text-xl font-bold mb-1 mt-4"
                        style="margin-top: 50px; color: {{ $this->data['color_secondary'] }} ;">
                        {{ $this->parent->name }}
                    </h2>
                    <h1 class="text-3xl font-bold mb-1" style="color: {{ $this->data['color_secondary'] }} ;">
                        {{ $this->data['name'] }}
                    </h1>
                    <h4 class="text-lg font-bold mb-5" style="color: {{ $this->data['color_secondary'] }} ;">
                        <span>{{ $this->parent->start_time }}</span>
                        to
                        <span>{{ $this->parent->end_time }}</span>
                    </h4>
                    <div class="">
                        <h5 class="font-semibold" style="color: {{ $this->data['color_secondary'] }} ;">
                            {{ $this->parent->location }}
                        </h5>
                        <h5 style="color: {{ $this->data['color_secondary'] }} ;">
                            @foreach ($this->data['benefits'] as $key => $benefit)
                                <span>{{ $benefit }}</span>
                                @if ($key != count($this->data['benefits']) - 1)
                                    ,
                                @endif
                            @endforeach
                        </h5>
                    </div>
                </div>

            </div>
            <div class="hidden md:block flex-none p-4"
                style="width: 300px; height: 350px; border-left: 5px dashed white">
                <h4 class="text-xl font-bold" style="color: {{ $this->data['color_secondary'] }} ;">
                    <span>{{ $this->parent->start_time }}</span>
                    to
                    <span>{{ $this->parent->end_time }}</span>
                </h4>
                <h1 class="text-3xl font-bold" style="color: {{ $this->data['color_secondary'] }} ;">
                    {{$this->data['price']}}
                </h1>
                <div class="rounded bg-white mb-2"
                    style="height: 150px; width: 150px; margin-right: auto; margin-left: auto">
                </div>
                <p class="font-semibold" style="color: {{ $this->data['color_secondary'] }} ;">
                    {{ $this->parent->location }}
                </p>
            </div>
        </div>

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
</x-filament-panels::page>