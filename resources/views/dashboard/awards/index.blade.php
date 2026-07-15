<x-app-layout>
    <x-slot name="title">
        {{ get_app_setting('awards_section_title') }}
    </x-slot>
    <x-slot name="description">
        {{ $campaign->description }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="$active_icon" />
                        
                    <div class="py-5">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight dark:text-white uppercase game-heading">
                            {{ get_app_setting('awards_section_title') }}
                        </h5>
                    </div>

                    @foreach ($award_codes as $award_code)
                        @if (isset($award_code->award->awardable))
                            @if ($award_code->award->model_type == 'clickwin')
                                <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                                style="{{'background:' . $award_code->award->awardable->gradient_1 . '; background: linear-gradient(135deg, ' . $award_code->award->awardable->gradient_1 . ' 0%, ' . $award_code->award->awardable->gradient_2 . ' 85%);'}}">
                                    <div class="flex flex-row">
                                        <div class="basis-full">
                                            <img src="{{ $award_code->award->awardable->featured_image}}" alt="{{$award_code->award->awardable->title}}" title="{{$award_code->award->awardable->title}}" class="rounded">
                                        </div>
                                    </div>
                                    <div class="flex flex-row">
                                        <div class="basis-full py-5 px-3">
                                            {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$award_code->award->awardable->title}}</p>
                                            <p class="pb-5 -mt-3.5 text-white font-bold ">{{$award_code->award->awardable->description}}</p> --}}
                                            <div class="text-start">
                                                <a href="{{route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $award_code->award])}}" 
                                                    class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $award_code->award->awardable->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $award_code->award->awardable->btn_border ? 'border: 2px solid ' . $award_code->award->awardable->btn_border_color . ';' : '' }} {{ $award_code->award->awardable->btn_text_color ? 'color: ' . $award_code->award->awardable->btn_text_color . ';' : '' }}background: {{ $award_code->award->awardable->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $award_code->award->awardable->btn_background_color_1 . ' 0%, ' . $award_code->award->awardable->btn_background_color_2 . ' 85%);' }}">
                                                    {{$award_code->award->awardable->btn_text_inactive}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($award_code->award->model_type == 'aplazogame')
                                <div class="border border-gray-200 rounded-t-lg shadow dark:bg-gray-800 dark:border-gray-700 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                                style="{{'background:' . $award_code->award->awardable->gradient_1 . '; background: linear-gradient(135deg, ' . $award_code->award->awardable->gradient_1 . ' 0%, ' . $award_code->award->awardable->gradient_2 . ' 85%);'}}">
                                    <div class="flex flex-row badis-full">
                                        <img src="{{ $award_code->award->awardable->featured_image }}" alt="" class="rounded">
                                    </div>
                                </div>
                                <div class="flex flex-row items-center bg-white rounded-b-lg mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                                    <div class="basis-1/1 py-5 px-3">
                                        <a href="{{route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $award_code->award, 'code_id' => $award_code->id])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $award_code->award->awardable->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $award_code->award->awardable->btn_border ? 'border: 2px solid ' . $award_code->award->awardable->btn_border_color . ';' : '' }} {{ $award_code->award->awardable->btn_text_color ? 'color: ' . $award_code->award->awardable->btn_text_color . ';' : '' }}background: {{ $award_code->award->awardable->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $award_code->award->awardable->btn_background_color_1 . ' 0%, ' . $award_code->award->awardable->btn_background_color_2 . ' 85%);' }}">
                                            <img src="{{ Storage::disk('public')->url('dummy_assets/shopping.svg') }}" alt="{{ __('Pay with APLAZO') }}" width="25" height="25" class="inline-block">
                                            {{$award_code->award->awardable->btn_text_inactive}}
                                        </a>
                                    </div>
                                </div>
                            @elseif ($award_code->award->model_type != 'setting')
                                <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                                style="{{'background:' . $award_code->award->awardable->gradient_1 . '; background: linear-gradient(135deg, ' . $award_code->award->awardable->gradient_1 . ' 0%, ' . $award_code->award->awardable->gradient_2 . ' 85%);'}}">
                                    <div class="flex flex-row">
                                        <div class="basis-full">
                                            <img src="{{$award_code->award->awardable->featured_image}}" alt="{{$award_code->award->awardable->title}}" title="{{$award_code->award->awardable->title}}" class="rounded">
                                        </div>
                                    </div>
                                    <div class="flex flex-row">
                                        <div class="basis-full py-5 px-3">
                                            {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$award_code->award->awardable->title}}</p>
                                            <p class="pb-5 -mt-3.5 text-white font-bold ">{{$award_code->award->awardable->description}}</p> --}}
                                            <div class="text-start">
                                                <a href="{{route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $award_code->award, 'code_id' => $award_code->id])}}" 
                                                    class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $award_code->award->awardable->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $award_code->award->awardable->btn_border ? 'border: 2px solid ' . $award_code->award->awardable->btn_border_color . ';' : '' }} {{ $award_code->award->awardable->btn_text_color ? 'color: ' . $award_code->award->awardable->btn_text_color . ';' : '' }}background: {{ $award_code->award->awardable->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $award_code->award->awardable->btn_background_color_1 . ' 0%, ' . $award_code->award->awardable->btn_background_color_2 . ' 85%);' }}">
                                                    {{$award_code->award->awardable->btn_text_inactive}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else

                            @endif
                        @endif
                    @endforeach
                    @if($code_hunter)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{'background:' . $code_hunter->award->awardable->gradient_1 . '; background: linear-gradient(135deg, ' . $code_hunter->award->awardable->gradient_1 . ' 0%, ' . $code_hunter->award->awardable->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$code_hunter->award->awardable->featured_image}}" alt="{{$code_hunter->award->awardable->title}}" title="{{$code_hunter->award->awardable->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$award_code->award->awardable->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$code_hunter->award->awardable->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $code_hunter->award])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $code_hunter->award->awardable->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $code_hunter->award->awardable->btn_border ? 'border: 2px solid ' . $code_hunter->award->awardable->btn_border_color . ';' : '' }} {{ $code_hunter->award->awardable->btn_text_color ? 'color: ' . $code_hunter->award->awardable->btn_text_color . ';' : '' }}background: {{ $code_hunter->award->awardable->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $code_hunter->award->awardable->btn_background_color_1 . ' 0%, ' . $code_hunter->award->awardable->btn_background_color_2 . ' 85%);' }}">
                                            {{$code_hunter->award->awardable->btn_text_inactive}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($tickets))
                        @foreach ($tickets as $ticket)
                            <div href="#" class="flex md:max-w-xl md:flex-row max-md:flex-col mb-4 items-center bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
                                <div class="flex-col md:w-[calc(40%)] max-md:w-full mb-2">
                                    <img class=" md:w-35 rounded-base h-64 md:h-auto max-md:w-48 mb-4 " src="{{ $ticket->img_url }}" alt="">
                                    <a  href="{{ $ticket->img_url }}" class=" inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                        {{__('Ver Ticket')}}
                                    </a>
                                </div>
                                <div class="flex flex-col justify-between md:p-4 leading-normal md:w-[calc(60%)]">
                                    <h5 class="mb-4 text-lg font-normal text-heading">Número de Transacción: <span class="font-bold">{{ $ticket->transaction_number }}</span></h5>
                                    <p class="mb-2 text-body"> Monto de transacción: ${{ $ticket->transaction_amount }}</p>
                                    <p class="mb-2 text-body"> Fecha de transacción: {{ $ticket->transaction_date }}</p>
                                </div>
                            </div>
                        @endforeach
                        {{ $tickets->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="game-navigator mb-3 text-center">
        <x-primary-link href="{{ route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug]) }}"
            class="inline-flex w-32 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m9 14l-4-4l4-4"/><path d="M5 10h11a4 4 0 1 1 0 8h-1"/></g></svg>
            {{ __('Back') }}
        </x-primary-link>
    </div>
</x-app-layout>
