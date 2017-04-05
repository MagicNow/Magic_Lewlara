@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main">
			
					Olá, <?php echo Auth::user()->first_name; ?>, você está logado(a)!
					<br />
					

					<?php					
					//Auth::user()->is_usuario();

					//Auth::user()->is_cliente();

					/*
					$user = Auth::user();

					print('<pre>');
					print_r(Auth::user()->group()->get()->toArray());
					print('</pre>');*/

					?>
		</div><!-- /.main -->
		

@endsection
