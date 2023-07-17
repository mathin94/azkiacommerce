<div class="{{ $class }}">
    @if (!empty($label))
        <span class="social-label">{{ $label }}:</span>
    @endif
    <a href="#" data-sharer="facebook"
        data-title="{{ $title }} di {{ config('app.name') }}"
        data-url="{{ $url }}" class="social-icon"
        title="Facebook"><x-bi-facebook /></a>
    <a href="#" data-sharer="twitter"
        data-title="{{ $title }} di {{ config('app.name') }}"
        data-url="{{ $url }}" class="social-icon" title="Twitter"><x-bi-twitter /></a>
    <a href="#" data-sharer="instagram"
        data-title="{{ $title }} di {{ config('app.name') }}"
        data-url="{{ $url }}" class="social-icon"
        title="Instagram"><x-bi-instagram /></a>
    <a href="#" data-sharer="pinterest"
        data-title="{{ $title }} di {{ config('app.name') }}"
        data-url="{{ $url }}" class="social-icon"
        title="Pinterest"><x-bi-pinterest /></a>
</div><!-- End .soial-icons -->
