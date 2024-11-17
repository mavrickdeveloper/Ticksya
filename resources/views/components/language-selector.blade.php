<div class="relative">
    <select
        wire:model="currentLocale"
        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
    >
        @foreach($locales as $code => $name)
            <option value="{{ $code }}" @if($currentLocale === $code) selected @endif>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>
