<div class="upload">
    
    {!! Form::open(array('action'=>'PostController@ajaxUpload','method'=>'POST', 'files'=>true, 'id'=>'defineUploadDropzone')) !!}
    
    	{!! Form::hidden('cliente_id',$cliente_default->id,['id'=>'ajaxupload_cliente_id']) !!}

		<div class="droparea">
	    	<p>ARRASTE IMAGENS NESSE ESPAÇO</p>
	    	<p>PARA FAZER UPLOAD</p>
	    	<p>OU</p>
	    	<a  class="btn btn-amarelo btn-grande dropzone-clickable">SELECIONE ARQUIVOS</a>
			<br>
			<br>
	    	<p class="percentage"></p>
	    	<span class="error"></span>
		</div>
    {!! Form::close() !!}

</div>