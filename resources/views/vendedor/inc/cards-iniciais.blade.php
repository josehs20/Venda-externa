     {{-- cards iniciais --}}
     <div class="listItemCarrinho">
         <div class="container">
             <div class="row d-flex justify-content-center">

                 <div class="col-lg-6 col-sm-6">
                     <div class="card-box bg-green">
                         <div class="inner">
                             <h6><i class="bi bi-cash-coin">
                                     Subtotal&ensp;R$<u>{{ reais($carrinho->valor_bruto) }}</u></i>
                             </h6>

                         </div>
                         <div class="inner">
                             <h6><i class="bi bi-cash-coin"> Total&ensp;<u>{{ 'R$' . reais($carrinho->total) }}</u></i>
                             </h6>
                         </div>
                         <div class="icon">
                             <i class="bi bi-cash-coin"></i>
                         </div>
                         <a href="#" class="card-box-footer">Valores</a>
                     </div>
                 </div>
                 <div class="col-lg-6 col-sm-6">
                     <div class="card-box bg-orange">
                         <div class="inner">
                             <i class="bi bi-cash-stack">&ensp;{{reais($carrinho->valor_desconto + $carrinho->valor_desconto_sb_venda)}} </i>
                         </div>
                         <div class="inner">
                             <p style="color:black;">
                                 &ensp;{{ $carrinho->tp_desconto == 'porcento_unico' ? "Unificado em % $carrinho->desconto_qtd" : ($carrinho->tp_desconto == 'dinheiro_unico' ? "Unificado em R$ $carrinho->desconto_qtd" : (!$carrinho->tp_desconto ? 'Nenhum Desconto Aplicado' : 'Desconto Dado Parcialmente')) }}
                             </p>
                             @if ($carrinho->valor_desconto_sb_venda)
                             <p style="color:black;">
                                {{ $carrinho->tp_desconto_sb_venda == 'porcento' ? "Desconto dado sobre a venda em % $carrinho->desconto_qtd_sb_venda" : ($carrinho->tp_desconto_sb_venda == 'dinheiro' ? "Desconto dado sobre a venda em R$ $carrinho->desconto_qtd_sb_venda" : '') }}
                            </p>
                             @endif
                         </div>


                         <div class="icon">
                             <i class="bi bi-percent"></i>

                         </div>
                         <a href="#" class="card-box-footer">Descontos</a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     {{-- Fim cards iniciais --}}
