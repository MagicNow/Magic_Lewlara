<div class="media-library">
    <!--stylized scroll-->
    @if (!empty($directories))
        <ul class="media-directories-list">
            @foreach ($directories as $directory)
                <li class="media-directories-item" data-directory="{{ $directory }}">
                    <span class="media-directories-item-image">&nbsp;</span>
                    <span class="media-directories-item-text">{{ basename($directory) }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="gallery_scroll col-sm-12 col-md-12">
    	@foreach ($filesArray as &$filename)
    	    <div class="col-sm-4 lista-imagens">
                <div class="selected-frame hide">
                    <img src="{{ URL::to('/img/selected.jpg') }}" />
                </div>
    	    	<div class="lista-cada-imagem" data-data="{{ date ('d/m/Y à\s H:i', filemtime($filename)) }}" data-imageurl="{{ URL::to('/' . $filename) }}" data-imageuri="{{ $filename }}" style="background-image: url({{ URL::to('/' . $filename) }});">
    	    		
    	    	</div><!-- /.lista-cada-imagem -->
    	    </div>
        @endforeach
    </div>
</div>