<div class="jscroll col-sm-12 pessoas">

            <?php $n = 0; ?>      
            @if (count($newsletter->groups))   
            <div class="row">
                <div class="col-sm-12 font-arial-narrow text-bold anula-padding">
                    <div class="col-sm-6 anula-padding titulo">
                         GRUPOS
                    </div><!-- /.col-sm-12 -->
                </div><!-- /.col-sm-12 -->

                           
                @foreach ($newsletter->groups as $grupo)
                    <div class="<?php echo ($n%2) ? 'col-sm-5 col-sm-offset-1' : 'col-sm-6' ?> pessoa">     <div class="borda">                                            
                            {{ mb_strtoupper($grupo->name) }}

                            <?php 
                                $grupo_pessoas = $grupo->newsletterPessoas($newsletter->id);
                            ?>
                            @if (count($grupo_pessoas))
                                <div class="grupo-pessoas">
                                    @foreach ($grupo_pessoas as $pessoa)
                                       <small class='grupo-pessoas-nome'> - {{ mb_strtoupper($pessoa->first_name . ' ' . $pessoa->last_name) }} </small><br>
                                    @endforeach       
                                </div>       
                            @endif
                            
                            
                        </div><!-- /.top-borda -->                              
                    </div><!-- /.col-sm-8 -->
                <?php $n++; ?>      
                @endforeach     

                <hr>
                <br><br><br>
            </div><!-- /.row -->
            @endif
            
            <div class="row">
                <div class="col-sm-12 font-arial-narrow text-bold anula-padding">
                    <div class="col-sm-6 anula-padding titulo">
                         PESSOAS
                    </div><!-- /.col-sm-12 -->
                </div><!-- /.col-sm-12 -->

                <?php $n = 0; ?>      
                @if (count($newsletter->pessoa()->get()))                        
                    @foreach ($newsletter->pessoa()->get() as $pessoa)
                        @if ($pessoa->pivot->newsletter_group_id != null)
                            <?php continue; ?>
                        @endif
                        <div class="<?php echo ($n%2) ? 'col-sm-5 col-sm-offset-1' : 'col-sm-6' ?> pessoa">     <div class="borda">
                                @if ($pessoa->photo)
                                    <img src="{{ URL::to('/upload/usuario').'/'.$pessoa->id.'/'.$pessoa->photo }}" class="abre-editar-cliente imagem-usuario">
                                @endif
                                                
                                {{ mb_strtoupper($pessoa->first_name . ' ' . $pessoa->last_name) }}
                            </div><!-- /.top-borda -->                              
                        </div><!-- /.col-sm-8 -->
                    <?php $n++; ?>      
                    @endforeach     
                @else
                    <div class="col-sm-12">
                        Não há pessoas cadastradas para o cliente {{ $cliente_default->name }}</td>
                    </div><!-- /.col-sm-12 -->
                @endif  
            </div><!-- /.row -->
</div><!-- /.jscroll -->