@foreach ($messages as $msg)
    @php
        $isVoice = !empty($msg->audio_path);
        $isFile = !empty($msg->file_path);
        $isGallery = !empty($msg->gallery_paths) && is_array($msg->gallery_paths) && count($msg->gallery_paths) > 0;
        $isImage = $isFile && !$isGallery && preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $msg->file_name);
        $hasLink = !empty($msg->link_url);
        $audioUrl = $isVoice ? asset('storage/' . $msg->audio_path) : '';
        $fileUrl = $isFile ? asset('storage/' . $msg->file_path) : '';
    @endphp
    @if($msg->user_id === auth()->id())
        {{-- Sent message --}}
        <div class="flex items-start gap-2.5 justify-end message-item" data-id="{{ $msg->id }}">
            <div class="flex flex-col gap-1 w-full max-w-[320px]">
                <div class="flex items-center space-x-1.5 justify-end">
                    <span class="text-sm font-semibold text-slate-900">{{ $msg->user->name }}</span>
                    <span class="text-xs text-slate-500">{{ $msg->created_at->format('g:i A') }}</span>
                </div>
                <div class="flex flex-col leading-1.5 p-4 bg-[#dcf8c6] rounded-s-xl rounded-ee-xl rounded-es-xl shadow-sm">
                    @if($isVoice)
                        <div class="flex items-center gap-2 py-2.5 flex-row-reverse">
                            <button type="button" class="voice-play-btn w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-slate-700 hover:text-[#00a884] transition-colors" data-url="{{ $audioUrl }}">
                                <i class="fa-solid fa-play text-xs"></i>
                            </button>
                            <div class="flex items-end gap-[2px] h-8">
                                @foreach(range(1,24) as $i)
                                    @php $h = rand(6, 30); @endphp
                                    <div class="w-[3px] bg-slate-400 rounded-full" style="height:{{ $h }}px"></div>
                                @endforeach
                            </div>
                            <span class="text-xs text-slate-500 font-medium voice-duration">0:00</span>
                        </div>
                    @elseif($isGallery)
                        <div class="grid grid-cols-2 gap-2 my-2.5">
                            @foreach(array_slice($msg->gallery_paths, 0, 4) as $index => $galleryUrl)
                                @php $remaining = count($msg->gallery_paths) - 4; @endphp
                                <div class="group relative aspect-square overflow-hidden rounded-lg {{ $index === 3 && $remaining > 0 ? 'bg-slate-900/50' : '' }}">
                                    @if($index === 3 && $remaining > 0)
                                        <div class="absolute inset-0 flex items-center justify-center z-10">
                                            <span class="text-xl font-medium text-white">+{{ $remaining }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                                        <a href="{{ asset('storage/' . $galleryUrl) }}" download
                                           class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-white/30 hover:bg-white/50 transition-colors">
                                            <i class="fa-solid fa-download text-white text-xs"></i>
                                        </a>
                                    </div>
                                    <img src="{{ asset('storage/' . $galleryUrl) }}" class="w-full h-full object-cover rounded-lg" alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    @elseif($isImage)
                        <div class="group relative my-2.5 max-w-full">
                            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center z-10">
                                <a href="{{ $fileUrl }}" download="{{ $msg->file_name }}"
                                   class="inline-flex items-center justify-center rounded-full h-10 w-10 bg-white/30 hover:bg-white/50 focus:ring-4 focus:outline-none focus:ring-white transition-colors">
                                    <i class="fa-solid fa-download text-white"></i>
                                </a>
                            </div>
                            <img src="{{ $fileUrl }}" alt="{{ $msg->file_name }}" class="rounded-lg max-w-full h-auto object-cover">
                        </div>
                    @elseif($isFile)
                        <div class="flex items-start my-2.5 bg-slate-100 rounded-lg p-2">
                            <div class="me-1.5 flex-1 min-w-0">
                                <span class="flex items-center gap-2 text-sm font-medium text-slate-800 pb-1">
                                    <i class="fa-solid fa-file-pdf text-red-500 w-5 h-5 shrink-0"></i>
                                    <span class="truncate">{{ $msg->file_name }}</span>
                                </span>
                                <span class="flex text-xs font-normal text-slate-500 gap-2">
                                    {{ number_format($msg->file_size / 1024, 1) }} KB
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="self-center" width="3" height="4" viewBox="0 0 3 4" fill="none"><circle cx="1.5" cy="2" r="1.5" fill="#6B7280"/></svg>
                                    PDF
                                </span>
                            </div>
                            <div class="inline-flex self-center items-center gap-1">
                                @if(preg_match('/\.pdf$/i', $msg->file_name))
                                    <button type="button" class="view-pdf-btn text-[#00a884] bg-white border border-slate-200 hover:bg-[#00a884] hover:text-white font-medium rounded-lg p-2 focus:outline-none transition-colors" data-url="{{ $fileUrl }}" data-name="{{ $msg->file_name }}" title="View PDF">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                @endif
                                <a href="{{ $fileUrl }}" download="{{ $msg->file_name }}"
                                   class="text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 font-medium rounded-lg p-2 focus:outline-none transition-colors" title="Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-800">{{ $msg->body }}</p>
                        @if($hasLink)
                            <a href="{{ $msg->link_url }}" target="_blank" rel="noopener" class="block bg-slate-100 rounded-lg p-3 mb-2 hover:bg-slate-200 transition-colors mt-2">
                                @if($msg->link_image)
                                    <img src="{{ $msg->link_image }}" alt="{{ $msg->link_title }}" class="w-full h-auto rounded-lg mb-2 object-cover max-h-40">
                                @endif
                                <span class="text-sm font-medium text-slate-900 line-clamp-1">{{ $msg->link_title ?? $msg->link_url }}</span>
                                @if($msg->link_description)
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $msg->link_description }}</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-1">{{ parse_url($msg->link_url, PHP_URL_HOST) }}</p>
                            </a>
                        @endif
                    @endif
                </div>
                <div class="flex items-center justify-end gap-1">
                    @if($msg->read_at)
                        <span class="text-xs font-medium text-blue-600 status-label">Read</span>
                        <span class="relative w-3 h-3 flex items-center justify-center">
                            <i class="fa-solid fa-check text-[10px] text-blue-500 absolute -left-[3px]"></i>
                            <i class="fa-solid fa-check text-[10px] text-blue-500 absolute left-[1px]"></i>
                        </span>
                    @elseif($msg->delivered_at)
                        <span class="text-xs font-medium text-slate-500 status-label">Delivered</span>
                        <span class="relative w-3 h-3 flex items-center justify-center">
                            <i class="fa-solid fa-check text-[10px] text-slate-400 absolute -left-[3px]"></i>
                            <i class="fa-solid fa-check text-[10px] text-slate-400 absolute left-[1px]"></i>
                        </span>
                    @else
                        <span class="text-xs font-medium text-slate-500 status-label">Sent</span>
                        <i class="fa-solid fa-check text-[10px] text-slate-400"></i>
                    @endif
                </div>
            </div>
            <img class="w-8 h-8 rounded-full bg-slate-200 object-cover" src="{{ asset('logo.png') }}" alt="{{ $msg->user->name }}">
        </div>
    @else
        {{-- Received message --}}
        <div class="flex items-start gap-2.5 message-item" data-id="{{ $msg->id }}">
            <img class="w-8 h-8 rounded-full bg-slate-200 object-cover" src="{{ asset('logo.png') }}" alt="{{ $msg->user->name }}">
            <div class="flex flex-col gap-1 w-full max-w-[320px]">
                <div class="flex items-center space-x-1.5">
                    <span class="text-sm font-semibold text-slate-900">{{ $msg->user->name }}</span>
                    <span class="text-xs text-slate-500">{{ $msg->created_at->format('g:i A') }}</span>
                </div>
                <div class="flex flex-col leading-1.5 p-4 bg-white rounded-e-xl rounded-es-xl shadow-sm border border-slate-100">
                    @if($isVoice)
                        <div class="flex items-center gap-2 py-2.5">
                            <button type="button" class="voice-play-btn w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-slate-700 hover:text-[#00a884] transition-colors" data-url="{{ $audioUrl }}">
                                <i class="fa-solid fa-play text-xs"></i>
                            </button>
                            <div class="flex items-end gap-[2px] h-8">
                                @foreach(range(1,24) as $i)
                                    @php $h = rand(6, 30); @endphp
                                    <div class="w-[3px] bg-slate-400 rounded-full" style="height:{{ $h }}px"></div>
                                @endforeach
                            </div>
                            <span class="text-xs text-slate-500 font-medium voice-duration">0:00</span>
                        </div>
                    @elseif($isGallery)
                        <div class="grid grid-cols-2 gap-2 my-2.5">
                            @foreach(array_slice($msg->gallery_paths, 0, 4) as $index => $galleryUrl)
                                @php $remaining = count($msg->gallery_paths) - 4; @endphp
                                <div class="group relative aspect-square overflow-hidden rounded-lg {{ $index === 3 && $remaining > 0 ? 'bg-slate-900/50' : '' }}">
                                    @if($index === 3 && $remaining > 0)
                                        <div class="absolute inset-0 flex items-center justify-center z-10">
                                            <span class="text-xl font-medium text-white">+{{ $remaining }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                                        <a href="{{ asset('storage/' . $galleryUrl) }}" download
                                           class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-white/30 hover:bg-white/50 transition-colors">
                                            <i class="fa-solid fa-download text-white text-xs"></i>
                                        </a>
                                    </div>
                                    <img src="{{ asset('storage/' . $galleryUrl) }}" class="w-full h-full object-cover rounded-lg" alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    @elseif($isImage)
                        <div class="group relative my-2.5 max-w-full">
                            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center z-10">
                                <a href="{{ $fileUrl }}" download="{{ $msg->file_name }}"
                                   class="inline-flex items-center justify-center rounded-full h-10 w-10 bg-white/30 hover:bg-white/50 focus:ring-4 focus:outline-none focus:ring-white transition-colors">
                                    <i class="fa-solid fa-download text-white"></i>
                                </a>
                            </div>
                            <img src="{{ $fileUrl }}" alt="{{ $msg->file_name }}" class="rounded-lg max-w-full h-auto object-cover">
                        </div>
                    @elseif($isFile)
                        <div class="flex items-start my-2.5 bg-slate-100 rounded-lg p-2">
                            <div class="me-1.5 flex-1 min-w-0">
                                <span class="flex items-center gap-2 text-sm font-medium text-slate-800 pb-1">
                                    <i class="fa-solid fa-file-pdf text-red-500 w-5 h-5 shrink-0"></i>
                                    <span class="truncate">{{ $msg->file_name }}</span>
                                </span>
                                <span class="flex text-xs font-normal text-slate-500 gap-2">
                                    {{ number_format($msg->file_size / 1024, 1) }} KB
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="self-center" width="3" height="4" viewBox="0 0 3 4" fill="none"><circle cx="1.5" cy="2" r="1.5" fill="#6B7280"/></svg>
                                    PDF
                                </span>
                            </div>
                            <div class="inline-flex self-center items-center gap-1">
                                @if(preg_match('/\.pdf$/i', $msg->file_name))
                                    <button type="button" class="view-pdf-btn text-[#00a884] bg-white border border-slate-200 hover:bg-[#00a884] hover:text-white font-medium rounded-lg p-2 focus:outline-none transition-colors" data-url="{{ $fileUrl }}" data-name="{{ $msg->file_name }}" title="View PDF">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                @endif
                                <a href="{{ $fileUrl }}" download="{{ $msg->file_name }}"
                                   class="text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 font-medium rounded-lg p-2 focus:outline-none transition-colors" title="Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-700">{{ $msg->body }}</p>
                        @if($hasLink)
                            <a href="{{ $msg->link_url }}" target="_blank" rel="noopener" class="block bg-slate-100 rounded-lg p-3 mb-2 hover:bg-slate-200 transition-colors mt-2">
                                @if($msg->link_image)
                                    <img src="{{ $msg->link_image }}" alt="{{ $msg->link_title }}" class="w-full h-auto rounded-lg mb-2 object-cover max-h-40">
                                @endif
                                <span class="text-sm font-medium text-slate-900 line-clamp-1">{{ $msg->link_title ?? $msg->link_url }}</span>
                                @if($msg->link_description)
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $msg->link_description }}</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-1">{{ parse_url($msg->link_url, PHP_URL_HOST) }}</p>
                            </a>
                        @endif
                    @endif
                </div>
                <span class="text-xs text-slate-400">Received</span>
            </div>
        </div>
    @endif
@endforeach
