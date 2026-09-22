{{-- OND-256/8: texty byly natvrdo anglicky uprostřed české stránky a nativní
     <input type="file"> se vykresloval přímo v drop zóně, takže pod českým
     popiskem svítilo browserové „Vybrat soubory / No file chosen". Teď jdou
     všechny texty z lang/*/contact.php a input je schovaný za vlastní
     tlačítko — input zůstává v DOMu, protože nese soubory do FormData. --}}
@props([
    'hint'     => null,
    'accept'   => '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp,.zip',
    'name'     => 'attachment[]',
    'label'    => null,
    'dragText' => null,
    'maxFiles' => null,
    'maxSize'  => null,
])

@php
    $hint     ??= __('contact.upload.hint');
    $label    ??= __('contact.upload.label');
    $dragText ??= __('contact.upload.drag_text');
    $maxFiles ??= __('contact.upload.max_files');
    $maxSize  ??= __('contact.upload.max_size');
@endphp

<div class="form-file-wrapper"
     x-data="fileDropZone({
         tooManyFiles: @js(__('contact.upload.error_too_many')),
         tooLarge: @js(__('contact.upload.error_too_large')),
     })"
     @dragover.prevent="isDragOver = true"
     @dragleave.prevent="isDragOver = false"
     @drop.prevent="handleDrop($event)">

    {{-- Drop zone --}}
    <div class="form-file" :class="{ 'is-drag-over': isDragOver }">
        <x-icon.file-text class="form-file__icon" />
        <p class="form-file__label"><strong>{{ $label }}</strong> {{ $dragText }}</p>
        <p class="form-file__hint">{{ $hint }}</p>
        <p class="form-file__hint" style="margin-top: 0.125rem;">{{ $maxFiles }}, {{ $maxSize }}</p>
        <p class="form-file__error" x-show="error" x-text="error" x-cloak></p>

        <button type="button" class="form-file__trigger" @click="$refs.input.click()">
            {{ __('contact.upload.browse') }}
        </button>

        <input type="file"
               class="form-file__input"
               name="{{ $name }}"
               multiple
               accept="{{ $accept }}"
               x-ref="input"
               tabindex="-1"
               aria-hidden="true"
               @change="handleChange($event)">
    </div>

    {{-- Selected file list --}}
    <ul class="form-file__list" x-show="files.length > 0" x-cloak>
        <template x-for="(file, index) in files" :key="file.name + '_' + file.size">
            <li class="form-file__list-item">
                <svg class="form-file__list-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M3 3.5A1.5 1.5 0 0 1 4.5 2h6.879a1.5 1.5 0 0 1 1.06.44l4.122 4.12A1.5 1.5 0 0 1 17 7.622V16.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 16.5v-13Z"/>
                </svg>
                <span class="form-file__list-name" x-text="file.name"></span>
                <span class="form-file__list-size" x-text="formatSize(file.size)"></span>
                <button type="button"
                        class="form-file__list-remove"
                        @click="removeFile(index)"
                        :aria-label="@js(__('contact.upload.remove')) + ' ' + file.name">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </li>
        </template>
    </ul>

</div>
