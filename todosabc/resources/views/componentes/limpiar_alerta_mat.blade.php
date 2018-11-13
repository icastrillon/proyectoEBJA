<a href="{{ route('limpiarMsgMat') }}" class="btn close" data-dismiss="alert" aria-label="Close"
   onclick="event.preventDefault(); document.getElementById('frm_msgMat').submit();">
    <span aria-hidden="true">&times;</span>
</a>
<form id="frm_msgMat" action="{{ route('limpiarMsgMat') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>