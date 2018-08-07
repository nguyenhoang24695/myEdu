{!! $has_primary ? "<div class='col-md-6'>" : "" !!}
<iframe id="document_{{$document->id}}"
        src="{{onlineDocumentView($document->get_download_link())}}"
        class="full-parent" style="min-height: 400px;"></iframe>

{!! $has_primary ? "</div>" : "" !!}

