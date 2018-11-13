<a href="{{ route('limpiarMsgIE') }}" class="btn close" data-dismiss="alert" aria-label="Close"
   onclick="event.preventDefault(); document.getElementById('frm_msgIE').submit();">
    <span aria-hidden="true">&times;</span>
</a>
<form id="frm_msgIE" action="{{ route('limpiarMsgIE') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>