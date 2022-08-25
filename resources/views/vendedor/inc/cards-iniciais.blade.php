     {{-- cards iniciais
     <div class="cardsIniciais">
         <div class="container">
             <div class="row d-flex justify-content-center">

                 <div class="col-lg-6 col-sm-6">
                     <div class="card-box bg-green" style="border-radius: 10px;">
                         <div class="inner">
                             <i class="bi bi-cash-coin valorBruto">
                                 Subtotal: R$
                                 {{ reais($carrinho->valor_bruto) }}
                             </i>


                         </div>
                         <div class="inner">
                            <i class="bi bi-cash-coin valorTotal">
                                     Total: R$ {{ reais($carrinho->total) }}</i>
                         </div>
                         <div class="icon">
                             <i class="bi bi-cash-coin"></i>
                         </div>
                         <a style="border-radius: 10px;" class="card-box-footer">Valores</a>
                     </div>
                 </div>
                 <div class="col-lg-6 col-sm-6">
                     <div class="card-box bg-orange" style="border-radius: 10px;">
                         <div class="inner">
                             <i class="bi bi-cash-stack valor_desconto">V. Desconto: R$&ensp;{{ reais($carrinho->valor_desconto + $carrinho->valor_desconto_sb_venda) }}
                             </i>
                         </div>
                         <div class="inner">
                       
                                 <p class="tp_desconto {{!$carrinho->tp_desconto ? 'd-none' : ''}}" style="color:black;">
                                     &ensp;{{ $carrinho->tp_desconto == 'porcento_unico' ? "Unificado em % $carrinho->qtd_desconto" : ($carrinho->tp_desconto == 'dinheiro_unico' ? "Unificado em R$ $carrinho->qtd_desconto" : 'Desconto Dado Parcialmente') }}
                                 </p>

                             @if ($carrinho->valor_desconto_sb_venda)
                                 <p class="desconto_sob_venda" style="color:black;">
                                     {{ $carrinho->tp_desconto_sb_venda == 'porcento' ? "Desconto dado sobre a venda em % $carrinho->desconto_qtd_sb_venda" : ($carrinho->tp_desconto_sb_venda == 'dinheiro' ? "Desconto dado sobre a venda em R$ $carrinho->desconto_qtd_sb_venda" : '') }}
                                 </p>
                             @endif
                         </div>

                         <div class="icon">
                             <i class="bi bi-percent"></i>

                         </div>
                         <a style="border-radius: 10px;" class="card-box-footer">Descontos</a>
                     </div>
                 </div>
             </div>
         </div>
     </div> --}}
     {{-- Fim cards iniciais --}}

     <style>
         .card {
             background-color: #fff;
             border-radius: 10px;
             border: none;
             position: relative;
             margin-bottom: 30px;
             box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
         }

         .l-bg-cherry {
             background: linear-gradient(to right, #493240, #f09) !important;
             color: #fff;
         }

         .l-bg-blue-dark {
             background: linear-gradient(to right, #373b44, #4286f4) !important;
             color: #fff;
         }

         .l-bg-green-dark {
             background: linear-gradient(to right, #0a504a, #38ef7d) !important;
             color: #fff;
         }

         .l-bg-orange-dark {
             background: linear-gradient(to right, #a86008, #ffba56) !important;
             color: #fff;
         }

         .card .card-statistic-3 .card-icon-large .fas,
         .card .card-statistic-3 .card-icon-large .far,
         .card .card-statistic-3 .card-icon-large .fab,
         .card .card-statistic-3 .card-icon-large .fal {
             font-size: 110px;
         }

         .card .card-statistic-3 .card-icon {
             text-align: center;
             line-height: 50px;
             margin-left: 15px;
             color: #000;
             position: absolute;
             right: 10px;
             top: 20px;
             opacity: 0.1;
         }

         /* .card .card-statistic-3 h5 {
            color:
         } */
         .l-bg-cyan {
             background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
             color: #fff;
         }

         .l-bg-green {
             background: linear-gradient(135deg, #16cc71 0%, #43e794 100%) !important;
             color: #fff;
         }

         .l-bg-orange {
             background: linear-gradient(to right, #f9900e, #ffba56) !important;
             color: #fff;
         }

         .l-bg-red {
             background: linear-gradient(135deg, #ff2323, #b64949) !important;
             color: #fff;
         }
     </style>
     <div class="row d-flex justify-content-center mt-5 mr-5 cardsIniciais">
         <div class="col-md-4 mr-2 animate__animated animate__fadeIn animate__delay-0.5s">
             <div class="card l-bg-green-dark mx-3">
                 <div class="card-statistic-3 p-4">

                     <div class="card-icon card-icon-large"><i style="font-size: 80px !important;" class="fas fa-ticket-alt"></i></div>

                     <div class="mb-4">
                         <h6 class="card-title mb-0 valorBruto"valorBruto">
                             Subtotal: R$
                             {{ reais($carrinho->valor_bruto) }}</h6>


                     </div>
                     <div class="row align-items-center mb-2 d-flex">
                         <div class="col-8">
                             <h6 class="card-title mb-0  valorTotal">
                                 Total: R$ {{ reais($carrinho->total) }}</i>
                             </h6>
                         </div>

                         {{-- <div class="col-4 text-right">
                            
                             <h3>10% <i class="fa fa-arrow-up"></i></h3>
                         </div> --}}
                     </div>
                     {{-- <div class="progress mt-1 " data-height="8" style="height: 8px;">
                         <div class="progress-bar l-bg-orange" role="progressbar" data-width="25%" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                     </div> --}}
                 </div>
             </div>
         </div>
         <div class="col-md-4 animate__animated animate__fadeIn animate__delay-0.5s">
             <div class="card l-bg-orange-dark mx-3">
                 <div class="card-statistic-3 p-4">
                     <div class="card-icon card-icon-large"><i style="font-size: 50px !important;" class="fas fa-percent"></i></div>
                     <div class="mb-4">
                         <h6 class="card-title mb-0 valor_desconto"> Desconto em valor:
                             R$&ensp;{{ reais($carrinho->valor_desconto) }} </h6>
                     </div>
                     <div class="row align-items-center mb-2 d-flex">
                         <div class="col-8">
                             <h6 class="card-title mb-0 tp_desconto {{ !$carrinho->tp_desconto ? 'd-none' : '' }}">
                                {{ $carrinho->tp_desconto == 'porcento_unico' ? "Único em: % $carrinho->qtd_desconto" : ($carrinho->tp_desconto == 'dinheiro_unico' ? "Único em:  R$ $carrinho->qtd_desconto" : 'Desconto Dado Parcialmente') }}
                             </h6>
                         </div>
                         {{-- <div class="col-4 text-right">
                             <span>2.5% <i class="fa fa-arrow-up"></i></span>
                         </div> --}}
                     </div>
                     {{-- <div class="progress mt-1 " data-height="8" style="height: 8px;">
                         <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                     </div> --}}
                 </div>
             </div>
         </div>
     </div>
