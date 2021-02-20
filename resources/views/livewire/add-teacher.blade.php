<div>
    {{--@include('partials.forms.select_multiple', [
        'label' => __('general.teachers'),
        'name' => 'teacher_ids',
        'placeholder' => __('event.select_teachers'),
        'records' => $teachers,
        'value_attribute_name' => 'full_name',
        'selected' => old('teacher_ids'),
        'required' => false,
        'extraClasses' => '',
    ])--}}

    <div wire:ignore class="md:grid md:grid-cols-6 md:gap-6">
        <div class="md:col-span-4">
            <label for="teacher_ids" class="block text-sm font-medium text-gray-700 inline">{{__('general.teachers')}}</label>

            <select id="teacher_ids" name="teacher_ids[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" multiple='multiple'>
                <option value="" class="text-gray-500">{{__('event.select_teachers')}}</option>

                @if(!empty($teachers))
                    @foreach ($teachers as $key => $teacher)
                        @isset($selected)
                            <option value="{{$teacher->id}}" {{ in_array($teacher->id, $selected) ? "selected":"" }}>{{$teacher->full_name}}</option>
                        @else
                            <option value="{{$teacher->id}}">{{$teacher->full_name}}</option>
                        @endif
                    @endforeach
                @endif
            </select>

            @error('teacher_ids')
            <span class="invalid-feedback text-red-500" role="alert">
                <strong>{{ $errors->first('teacher_ids') }}</strong>
            </span>
            @enderror
        </div>
        <div class="md:col-span-2 relative">
            <button wire:click.prevent="openModal" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 absolute bottom-1 left-1">
                Add teacher
            </button>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="z-10 inset-0 overflow-y-auto @if($showModal) fixed @else hidden @endif">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <!--
              Modal panel, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                    <button wire:click="close" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <!-- Heroicon name: x -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class=""> {{-- sm:flex sm:items-start --}}

                    aaaaaa
                    {{--<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-headline">
                            Add image parameters
                        </h3>

                        <div class="mt-2">
                            <label for="image_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="mt-1">
                                <input type="text" wire:model="image_description" id="image_description" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="">
                            </div>
                        </div>

                        <div>
                            <label for="image_video_url" class="block text-sm font-medium text-gray-700 mt-3">Video URL</label>
                            <div class="mt-1">
                                <input type="text" wire:model="image_video_url" id="image_video_url" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="">
                            </div>
                        </div>

                        <div>
                            <label for="image_caption" class="block text-sm font-medium text-gray-700 mt-3">Caption</label>
                            <div class="mt-1">
                                <input type="text" wire:model="image_caption" id="image_caption" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="">
                            </div>
                        </div>

                        <div class="mt-2">
                            <label for="image_gallery" class="block text-sm font-medium text-gray-700 mt-3">Gallery</label>
                            <div class="mt-1">
                                <input type="text" wire:model="image_gallery" id="image_gallery" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Name of the gallery to assign the image to">
                            </div>
                        </div>

                        <div class="mt-2">
                            <label for="snippet" class="block text-sm font-medium text-gray-700 mt-3">Snippet</label>
                            <div class="mt-1">
                                <input type="text" wire:model="snippet" id="snippet" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="">
                            </div>
                        </div>

                    </div>--}}
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button wire:click="save" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button wire:click="close" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    

</div>


@push('scripts')
    <script>
        $(document).ready(function () {
            //console.log('ciao bello');

            $('#teacher_ids').select2();
            $('#teacher_ids').on('change', function (e) {

                var data = $('#teacher_ids').select2("val");
                //console.log(data);

                @this.set('selected', data);
            });
        });

    </script>
@endpush
