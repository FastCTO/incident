{{-- resources/views/partials/multicam.blade.php --}}
<div style="position:relative; width:100%; height:0; padding-bottom:56.25%;">
    <iframe
        src="{{ url('/multi-stream') }}"
        style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;"
        allowfullscreen>
    </iframe>
</div>

