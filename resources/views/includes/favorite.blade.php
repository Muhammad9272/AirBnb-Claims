
<div class="ts-heart-container">
    <input type="checkbox" id="{{ $favdata->slug }}" data-type="{{$type}}" data-id="{{ $favdata->id }}" onclick="toggleFavorite($(this))" {{ $favdata->favorited_by(auth()->user()) ? 'checked' : '' }}>
    <label class="icon--heart" for="{{ $favdata->slug }}">
        <div class="ts-heart-checked">
            <i class="ri-heart-3-fill cursor-pointer"></i>
        </div>
        <div class="ts-heart-unchecked">
            <i class="ri-heart-3-line cursor-pointer"></i>
        </div>
    </label>
</div>

@pushOnce('partial_script')
 <script src="{{ asset('assets/common_assets/js/favorite.js') }}"></script>
@endPushOnce