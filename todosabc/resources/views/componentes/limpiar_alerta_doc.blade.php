<a href="{{ route('limpiarMsgDoc') }}" class="btn close" data-dismiss="alert" aria-label="Close"
   onclick="event.preventDefault(); document.getElementById('frm_msgDoc').submit();">
    <span aria-hidden="true">&times;</span>
</a>
<form id="frm_msgDoc" action="{{ route('limpiarMsgDoc') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>