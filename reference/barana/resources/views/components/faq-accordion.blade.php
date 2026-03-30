@props([
    'items',
])

<div x-data="{ open: null }" class="divide-y border border-gray-200 rounded-2xl overflow-hidden bg-white">
    @foreach ($items as $i => $item)
        <div class="faq-item">
            <button
                class="faq-item__btn px-6"
                @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                :aria-expanded="open === {{ $i }}"
                aria-controls="faq-answer-{{ $i }}"
            >
                <span>{{ $item['question'] }}</span>
                <span class="faq-item__chevron">
                    <x-icon.chevron-down class="w-5 h-5" />
                </span>
            </button>
            <div class="faq-item__answer-wrap" :class="{ 'faq-item__answer-wrap--open': open === {{ $i }} }">
                <div>
                    <div id="faq-answer-{{ $i }}" class="faq-item__answer px-6">
                        {!! nl2br(e($item['answer'])) !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
