<div class="jscroll col-sm-12">
    <table class="col-sm-12">
        <thead>
            <td class="col-sm-5">
                TÍTULO
            </td>
            <td>
                AUTOR
            </td>
            <td>
                CATEGORIA
            </td>
            <td>
                DATA
            </td>
        </thead>
        @if (count($newsletter->post()->get()))                             
            @foreach ($newsletter->post()->get() as $post)
                <tbody>
                    <tr>
                        <td> 
                            {{ $post->titulo }}
                            <br><br>
                            {!! link_to_action('PostController@todos',' &nbsp;  &nbsp;VER POST&nbsp;  &nbsp; ',null,['class'=>'btn btn-amarelo btn-extra-pequeno align-center']) !!}                          
                        </td>               
                        <td>
                            {{ mb_strtoupper($post->user->first_name . ' ' . $post->user->last_name) }}
                        </td>   
                        <td>
                            @foreach ($post->categoria as $categoria)
                                {{ mb_strtoupper($categoria->name) }} <br>
                                @if ($categoria->pivot->sub_categoria_id)
                                    &nbsp; <small>{{ $post->subcategoriaName($categoria->pivot->sub_categoria_id) }}</small><br>
                                @endif
                            @endforeach
                        </td>                       
                        <td class="direita-padding">                                    
                            {{ date('d/m/Y H:i',strtotime($post->publicar_em)) }} <br>
                            {{ strtoupper($post->StatusName) }}
                        </td>
                    </tr> 
                </tbody>   
            @endforeach     
        @else
            <tbody>
                <tr>
                    <td colspan="4">Não há posts</td>
                </tr>
            </tbody>
        @endif                      
    </table>
</div><!-- /.jscroll -->