@include('header')

@include('topo_nav')

<div class="container-fluid">
	<div class="row">

	@include('sidebar_left')
	
	@if (Session::has('message'))
		<div class="flash alert-info">
			<p>{{ Session::get('message') }}</p>
		</div>
	@endif

	@yield('content')

    </div><!-- /.row -->  
</div><!-- /.container-fluid -->   


<!-- MODAL MODALS MODAIS  -->
<!-- MODAL MODALS MODAIS  -->
<!-- MODAL MODALS MODAIS  -->

<div class="modal fade" id="modal-confirm-delete" tabindex="-1" role="dialog" aria-labelledby="Confirma Exclusão" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Excluir
            </div>
            <div class="modal-body">
                Deseja realmente excluir?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">NÃO EXCLUIR</button>
                <a class="btn btn-danger btn-ok">EXCLUIR</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="Confirma" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
            </div>
            <div class="modal-body">
                Confirma ação?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btcancel" data-dismiss="modal">NÃO</button>
                <a class="btn btn-danger btn-ok btok">SIM</a>
            </div>
        </div>
    </div>
</div>


@include('footer')