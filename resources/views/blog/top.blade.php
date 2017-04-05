<div class="blog-top">
	<div class="container-fluid blog">
		<div class="row">
			
			<div class="bar-search col-xs-12 col-sm-4  col-sm-push-8">
			    {!! Form::open(['action'=>['blog\HomeBlogController@busca',@$cliente_default->slug], 'class'=>'form-horizontal']) !!}                
			        <div class="form-group buscar_por col-sm-9">
			            {!! Form::text('buscar_por', null, ['class'=>'form-control','placeholder'=>'Buscar no site']) !!}
			        </div>
			        <div class="form-group search col-sm-3">
			            {!! Form::submit('Buscar', ['name'=>'buscar','class'=>'btn btn-medio align-center']) !!}
			        </div>
			    {!! Form::close() !!}
			</div>

			<div class="top-blog col-xs-12 col-sm-8 col-sm-pull-4">
					<!-- NOME DO CLIENTE  -->
					<a class="no-item-link" href="{{ action('blog\HomeBlogController@index',array($cliente_default->slug)) }}" title="{{$cliente_default->name}}"><h1 class="top-blog-titulo">{{$cliente_default->name}}</h1>
					</a>
			</div>

			

		</div><!-- /.row -->
		<hr class="blog-top-separador">

		@include('blog/menu')
	</div>
</div><!-- /.jumbotron -->