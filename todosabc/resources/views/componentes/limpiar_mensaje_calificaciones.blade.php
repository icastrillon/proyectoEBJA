<a href="#" class="btn close" data-dismiss="alert" aria-label="Close"
   onclick="event.preventDefault(); document.getElementById('frm_msg_k').submit();">
    <span aria-hidden="true">&times;</span>
</a>
<form id="frm_msg_k" action="{{ route('borrar_mensaje') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>