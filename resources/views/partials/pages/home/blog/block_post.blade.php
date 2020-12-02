<div class="flex flex-col rounded-lg shadow-lg overflow-hidden">

    <div class="flex-shrink-0">
        <img class="h-48 w-full object-cover" src="https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80" alt="">
    </div>
    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
        <div class="flex-1">
            <p class="text-sm leading-5 font-medium text-indigo-600">
                @foreach($post->tags()->get() as $tag)
                <a href="{{ route('tags.show',$tag->id) }}" class="hover:underline">
                    {{--{{$post->post_category->name}}--}}
                    #{{$tag->tag}}
                </a>
                @endforeach
            </p>

            <a href="{{ route('posts.show',$post->id) }}" class="block">
                <h3 class="mt-2 text-xl leading-7 font-semibold text-gray-900">
                    {{$post->title}}
                </h3>
                <p class="mt-3 text-base leading-6 text-gray-500">
                    {{$post->intro_text}}
                </p>
            </a>
        </div>
        <div class="mt-6 flex items-center">
            <div class="flex-shrink-0">
                <a href="#">
                    <img class="h-10 w-10 rounded-full" src="https://source.unsplash.com/random&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                </a>
            </div>
            <div class="ml-3">
                <p class="text-sm leading-5 font-medium text-gray-900">
                    <a href="#" class="hover:underline">
                        {{$post->user->name}}
                    </a>
                </p>
                <div class="flex text-sm leading-5 text-gray-500">
                    <time datetime="{{$post->created_at->format('Y-m-d')}}">
                        {{$post->created_at->format('M j, Y')}}
                    </time>
                    <span class="mx-1">
                  &middot;
                </span>
                    <span>
                  {{$post->reading_time('minutesAndSeconds')}} read
                </span>
                </div>
            </div>
        </div>
    </div>
</div>