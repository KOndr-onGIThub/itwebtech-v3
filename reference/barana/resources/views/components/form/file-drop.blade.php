@props([
    'hint'     => 'JPG, PNG, PDF, TXT, DOC, DOCX, XLS, XLSX, CSV, PPT, PPTX, ZIP, RAR, 7Z, BZ2…',
    'accept'   => '.jpg,.jpeg,.png,.webp,.gif,.pdf,.txt,.doc,.docx,.xls,.xlsx,.csv,.ppt,.pptx,.zip,.rar,.7z,.bz2',
    'name'     => 'attachment[]',
    'label'    => 'Add files',
    'dragText' => 'or drag and drop here',
    'maxFiles' => 'Max. 5 files',
    'maxSize'  => 'total 20 MB',
])

<div class="form-file-wrapper"
     x-data="fileDropZone"
     @dragover.prevent="isDragOver = true"
     @dragleave.prevent="isDragOver = false"
     @drop.prevent="handleDrop($event)">

    {{-- Drop zone --}}
    <div class="form-file" :class="{ 'is-drag-over': isDragOver }">
        <x-icon.file-text class="form-file__icon" />
        <p class="form-file__label"><strong>{{ $label }}</strong> {{ $dragText }}</p>
        <p class="form-file__hint">{{ $hint }}</p>
        <p class="form-file__hint" style="margin-top: 0.125rem;">{{ $maxFiles }}, {{ $maxSize }}</p>
        <p class="form-file__error" x-show="error" x-text="error"></p>
        <input type="file"
               name="{{ $name }}"
               multiple
               accept="{{ $accept }}"
               x-ref="input"
               aria-label="Upload attachments"
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
                        :aria-label="'Remove ' + file.name">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </li>
        </template>
    </ul>

</div>
