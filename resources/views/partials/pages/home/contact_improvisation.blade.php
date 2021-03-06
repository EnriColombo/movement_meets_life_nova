
<div class="mt-8 mx-auto max-w-screen-xl p-4 sm:mt-12 sm:px-6 md:mt-20 xl:mt-24">
    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left lg:col-start-7 lg:row-start-1">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 sm:text-base lg:text-sm xl:text-base">
                {{ ucfirst(trans('static_pages.home.blocks.contact.contact_improvisation')) }}
            </h2>
            <h3 class="mt-1 text-4xl tracking-tight leading-10 font-brand text-gray-900 sm:leading-none sm:text-6xl lg:text-4xl xl:text-5xl">
                @lang('static_pages.home.blocks.contact.more_than_a_dance')
            </h3>
            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                @lang('static_pages.home.blocks.contact.ci_is_liberating')
            </p>
            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                <div class="rounded-md shadow">
                    <a href="{{route('staticPages.contactImprovisation')}}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-500 focus:outline-none focus:border-primary-700 focus:ring-primary transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">
                        @lang('static_pages.home.blocks.contact.more_about_ci')
                    </a>
                </div>
                {{--<div class="mt-3 sm:mt-0 sm:ml-3">
                    <a href="{{route('staticPages.treatments')}}" class="w-full flex items-center justify-center px-8 py-3 border border-primary-600 text-base leading-6 font-medium rounded-md text-primary-700 bg-white hover:text-primary-600 hover:bg-primary-50 focus:outline-none focus:ring-primary focus:border-primary-300 transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">
                        More info about ILM
                    </a>
                </div>--}}
            </div>
        </div>
        <div class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center lg:col-start-1 lg:row-start-1">
            {{--<svg class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-8 scale-75 origin-top sm:scale-100 lg:hidden" width="640" height="784" fill="none" viewBox="0 0 640 784">
                <defs>
                    <pattern id="4f4f415c-a0e9-44c2-9601-6ded5a34a13e" x="118" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                    </pattern>
                </defs>
                <rect y="72" width="640" height="640" class="text-gray-50" fill="currentColor" />
                <rect x="118" width="404" height="784" fill="url(#4f4f415c-a0e9-44c2-9601-6ded5a34a13e)" />
            </svg>--}}

            {{-- video --}}
            {{--<div class="relative mx-auto w-full rounded-lg shadow-lg lg:max-w-md">
                <button type="button" class="relative block w-full rounded-lg overflow-hidden focus:outline-none focus:ring">
                    <img class="w-full" src="https://images.unsplash.com/photo-1556740758-90de374c12ad?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Woman making a sale">
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                        <svg class="h-20 w-20 text-primary-500" fill="currentColor" viewBox="0 0 84 84">
                            <circle opacity="0.9" cx="42" cy="42" r="42" fill="white" />
                            <path d="M55.5039 40.3359L37.1094 28.0729C35.7803 27.1869 34 28.1396 34 29.737V54.263C34 55.8604 35.7803 56.8131 37.1094 55.9271L55.5038 43.6641C56.6913 42.8725 56.6913 41.1275 55.5039 40.3359Z" />
                        </svg>
                    </div>
                </button>
            </div>

            {!! $videoIntro !!}--}}

            <div class="relative mx-auto w-full rounded-lg shadow-lg lg:max-w-md">
                <a data-fancybox href="https://www.youtube.com/embed/MelWoO9J3E8">
                    <img class="w-full" src="{{asset('images/static_pages/hp/contact_improvisation_small.jpg')}}" alt="Contact Improvisation class in Slovenia Slovenija">
                    {{--<iframe width="450" height="300" src="https://www.youtube.com/embed/MelWoO9J3E8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>--}}
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                        <svg class="h-20 w-20 text-primary-500" fill="currentColor" viewBox="0 0 84 84">
                            <circle opacity="0.9" cx="42" cy="42" r="42" fill="white" />
                            <path d="M55.5039 40.3359L37.1094 28.0729C35.7803 27.1869 34 28.1396 34 29.737V54.263C34 55.8604 35.7803 56.8131 37.1094 55.9271L55.5038 43.6641C56.6913 42.8725 56.6913 41.1275 55.5039 40.3359Z" />
                        </svg>
                    </div>
                </a>
            </div>

</div>
</div>
</div>



