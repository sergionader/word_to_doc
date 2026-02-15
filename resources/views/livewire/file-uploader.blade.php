<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload & Convert') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Success message --}}
                    @if ($result)
                        <div class="mb-6 rounded-md bg-green-50 p-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm font-medium text-green-800">{{ $result }}</p>
                            </div>
                            @if ($downloadUrl)
                                <div class="mt-3 ml-8">
                                    <a href="{{ $downloadUrl }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Download Markdown
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Error message --}}
                    @if ($error)
                        <div class="mb-6 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Upload form --}}
                    <form wire:submit="convert">
                        <div x-data="{ isDragging: false }"
                             x-on:dragover.prevent="isDragging = true"
                             x-on:dragleave.prevent="isDragging = false"
                             x-on:drop.prevent="
                                isDragging = false;
                                if ($event.dataTransfer.files.length > 0) {
                                    @this.upload('file', $event.dataTransfer.files[0]);
                                }
                             "
                             class="border-2 border-dashed rounded-lg p-12 text-center transition-colors"
                             :class="isDragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:border-gray-400'">

                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>

                            <div class="mt-4">
                                <label for="file-upload" class="cursor-pointer">
                                    <span class="text-indigo-600 font-medium hover:text-indigo-500">Upload a file</span>
                                    <span class="text-gray-500"> or drag and drop</span>
                                    <input id="file-upload" wire:model="file" type="file" class="sr-only" accept=".docx">
                                </label>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">.docx files up to 50MB</p>
                        </div>

                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($file)
                            <div class="mt-4 flex items-center justify-between bg-gray-50 rounded-md p-3">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $file->getClientOriginalName() }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ number_format($file->getSize() / 1024, 1) }} KB</span>
                            </div>
                        @endif

                        <div class="mt-6">
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    @if(!$file) disabled @endif
                                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                <span wire:loading.remove wire:target="convert">Convert to Markdown</span>
                                <span wire:loading wire:target="convert" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Converting...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
