<?php 
$scripts[] =  array(
        'src' => URL::to('/').elixir('blogdir/js/blog.js')
    ); 

?>

@include('header')

@include('topo_nav')

@include('blog/top')





<div class="container-fluid blog">
	<div class="row">


	

	@yield('content')
        
        
  

    </div><!-- /.row -->  
</div><!-- /.container-fluid -->   







@include('footer')