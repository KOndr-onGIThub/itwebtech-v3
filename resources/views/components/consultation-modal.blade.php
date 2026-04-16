{{--
    Consultation Modal
    ──────────────────
    Opens when any element dispatches the custom event `open-consultation-modal`.
    Usage: @click="$dispatch('open-consultation-modal')"

    Video:
      - Place the MP4 file at public/videos/consultation.mp4
      - Set CONSULTATION_VIDEO_URL in .env (or leave blank to show placeholder)

    Calendly:
      - Set CALENDLY_URL in .env
      - Configure pre-questions ("Co řešíte?" + "Odhadovaný rozpočet?") in Calendly admin
        → Event types → Edit event → Questions & cancelation
--}}
@php
    $videoSrc  = env('CONSULTATION_VIDEO_URL', '');
    $calendlyUrl = env('CALENDLY_URL', '');
@endphp

<div
    x-data="consultationModal()"
    @open-consultation-modal.window="openModal()"
    x-cloak
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="consult-modal-backdrop"
        @click="closeModal()"
        aria-hidden="true"
    ></div>

    {{-- Dialog --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="consult-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="consult-modal-title"
        @keydown.escape.window="closeModal()"
    >
        {{-- Close button --}}
        <button
            class="consult-modal-close"
            @click="closeModal()"
            aria-label="{{ __('layout.modal.close') }}"
        >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        {{-- Modal heading --}}
        <h2 id="consult-modal-title" class="consult-modal-title">
            {{ __('home.modal.title') }}
        </h2>
        <p class="consult-modal-subtitle">{{ __('home.modal.subtitle') }}</p>

        {{-- Video area --}}
        <div class="consult-modal-video">
            @if ($videoSrc)
                <video
                    x-ref="video"
                    class="consult-modal-video__player"
                    :src="videoReady ? '{{ $videoSrc }}' : ''"
                    preload="none"
                    :controls="videoStarted"
                    playsinline
                ></video>
                <button
                    x-show="!videoStarted"
                    @click="startVideo()"
                    class="consult-modal-video__play-btn"
                    aria-label="{{ __('home.modal.play_video', [], app()->getLocale()) ?? 'Přehrát video' }}"
                    type="button"
                >
                    <span class="consult-modal-video__play-btn-inner" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <polygon points="6 4 20 12 6 20 6 4"/>
                        </svg>
                    </span>
                </button>
            @else
                <div class="consult-modal-video__placeholder" aria-label="{{ __('home.modal.video_placeholder') }}">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.4" aria-hidden="true">
                        <rect x="2" y="2" width="20" height="20" rx="3"/>
                        <polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none" opacity="0.6"/>
                    </svg>
                    <span>{{ __('home.modal.video_placeholder') }}</span>
                </div>
            @endif
        </div>

        {{-- Calendly CTA --}}
        <div class="consult-modal-cta">
            @if ($calendlyUrl)
                <a
                    href="{{ $calendlyUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-primary consult-modal-cta__btn"
                >
                    {{ __('home.modal.calendly_btn') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
            @else
                <a
                    href="{{ lroute('contact') }}"
                    class="btn btn-primary consult-modal-cta__btn"
                >
                    {{ __('home.modal.calendly_btn') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
            @endif
            <p class="consult-modal-cta__note">{{ __('home.modal.cta_note') }}</p>
        </div>

    </div>
</div>
