<x-app-layout>
    <x-slot name="title">
        {{$active_campaign->name}}
    </x-slot>
    <x-slot name="description">
        {{$active_campaign->description}}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <h1 class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 text-white text-center uppercase">
                    {{__('Hi')}}, {{auth()->user()->name}}
                </h1>
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
                    @if ($active_campaign->campaign_splash_page->featured_video_url)
                    <div class="aspect-w-16 aspect-h-9 mb-6">
                        <iframe src="{{$active_campaign->campaign_splash_page->featured_video_url}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    @endif
                    @if ($active_campaign->campaign_splash_page->featured_image_url)
                    <div class="mb-6">
                        <img src="{{$active_campaign->campaign_splash_page->featured_image_url}}" alt="{{$active_campaign->name}}" title="{{$active_campaign->name}}" class="h-auto max-w-full">
                    </div>
                    @endif
                    <div class="mb-6 text-center">
                        {!! $active_campaign->campaign_splash_page->instructions !!}
                    </div>
                    <div class="my-6 text-center">
                        <x-primary-link href="{{ route('campaign.show', ['tenant' => tenant('id'), 'slug' => $active_campaign->slug]) }}"
                            class="w-full block">
                            {{ __('Start') }}
                        </x-primary-link>
                    </div>
                    @foreach ($active_campaign->content_types as $content_type)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{'background:' . $content_type->gradient_1 . '; background: linear-gradient(135deg, ' . $content_type->gradient_1 . ' 0%, ' . $content_type->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row items-center">
                                <div class="basis-2/3">
                                    <p class="pb-5 -mt-3.5 text-white font-bold sm:text-base">{{$content_type->description}}</p>
                                    <x-primary-link href="{{ route('campaign.show', ['tenant' => tenant('id'), 'slug' => $active_campaign->slug]) }}" title="{{$active_campaign->name}}" class="font-bold">
                                        {{__('Play now')}}
                                    </x-primary-link>
                                </div>
                                <div class="basis-1/3">
                                    <img src="{{$content_type->icon_active}}" alt="{{$content_type->description}}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>